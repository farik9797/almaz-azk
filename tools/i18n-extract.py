#!/usr/bin/env python3
"""
Разметка index.html ключами data-i18n и выгрузка русского текста в assets/i18n/ru.reference.json.

Запуск:
  python3 tools/i18n-extract.py --annotate   # один раз: проставить ключи в index.html
  python3 tools/i18n-extract.py              # перевыгрузить ru.reference.json из уже размеченного HTML

Русский текст живёт только в index.html. ru.reference.json — слепок для сверки переводов:
после правок клиента перевыгрузите его и сравните с en/kk/zh, чтобы найти устаревшие строки
(python3 tools/i18n-check.py).
"""
import re, json, sys, html as htmlmod
from html.parser import HTMLParser

SRC = 'index.html'
OUT = 'assets/i18n/ru.reference.json'
CYR = re.compile(r'[А-Яа-яЁёӘәҒғҚқҢңӨөҰұҮүҺһІі]')
VOID = {'meta','link','img','br','hr','input','source','area','base','col','embed','param','track','wbr'}
I18N_ATTRS = ('alt','title','aria-label','placeholder','content')

def parse(src):
    starts = [0]
    for line in src.split('\n')[:-1]:
        starts.append(starts[-1] + len(line) + 1)
    def off(p): return starts[p[0]-1] + p[1]

    class S(HTMLParser):
        def __init__(self):
            super().__init__(convert_charrefs=False)
            self.stack, self.elems, self.texts, self.attrs = [], [], [], []
            self.sec = 'misc'
        def handle_comment(self, data):
            # секции размечены комментариями в первой колонке: <!-- HERO -->
            if self.getpos()[1] == 0:
                name = data.strip().lower().replace(' ', '-')
                if re.fullmatch(r'[a-z-]+', name): self.sec = name
        def handle_starttag(self, tag, attrs):
            a = dict(attrs); o = off(self.getpos()); tt = self.get_starttag_text()
            for an in I18N_ATTRS:
                if an in a and CYR.search(a[an]):
                    self.attrs.append(dict(attr=an, val=a[an], so=o, tt=tt, sec=self.sec, attrs=a))
            if tag in VOID: return
            self.stack.append(dict(tag=tag, so=o, tt=tt, inner_start=o+len(tt), sec=self.sec, attrs=a))
        def handle_startendtag(self, tag, attrs): self.handle_starttag(tag, attrs)
        def handle_endtag(self, tag):
            while self.stack and self.stack[-1]['tag'] != tag: self.stack.pop()
            if not self.stack: return
            e = self.stack.pop(); e['inner_end'] = off(self.getpos()); self.elems.append(e)
        def handle_data(self, d):
            if self.stack and self.stack[-1]['tag'] not in ('script','style') and CYR.search(d):
                self.texts.append(dict(so=off(self.getpos()), raw=d, parent=self.stack[-1]['so']))
    p = S(); p.feed(src)
    return p, {e['so']: e for e in p.elems}

def add_attr(tagtext, attr, value):
    """Вставить атрибут перед закрывающей скобкой тега."""
    m = re.match(r'^<\s*([a-zA-Z0-9-]+)', tagtext)
    insert = f' {attr}="{value}"'
    if tagtext.rstrip().endswith('/>'):
        i = tagtext.rstrip().rfind('/>')
        return tagtext[:i] + insert + tagtext[i:]
    i = tagtext.rfind('>')
    return tagtext[:i] + insert + tagtext[i:]

def annotate(src):
    p, emap = parse(src)
    counters, edits, ru = {}, [], {}
    def newkey(sec):
        counters[sec] = counters.get(sec, 0) + 1
        return f'{sec}.{counters[sec]}'

    handled_parents = set()
    for t in p.texts:
        e = emap.get(t['parent'])
        if e is None: continue
        inner = src[e['inner_start']:e['inner_end']]
        if '<' not in inner:
            # весь внутренний текст элемента — одна переводимая единица
            if e['so'] in handled_parents: continue
            handled_parents.add(e['so'])
            if 'data-i18n' in e['attrs']: continue
            key = newkey(e['sec'])
            ru[key] = htmlmod.unescape(inner.strip())
            edits.append((e['so'], e['so'] + len(e['tt']), add_attr(e['tt'], 'data-i18n', key)))
        else:
            # смешанное содержимое — оборачиваем сам текстовый узел
            key = newkey(e['sec'])
            stripped = t['raw'].strip()
            ru[key] = htmlmod.unescape(stripped)
            lead_len = len(t['raw']) - len(t['raw'].lstrip())
            a = t['so'] + lead_len
            b = a + len(stripped)
            edits.append((a, b, f'<span data-i18n="{key}">{stripped}</span>'))

    for a in p.attrs:
        key = newkey(a['sec'] + '-attr')
        ru[key] = htmlmod.unescape(a['val'])
        edits.append((a['so'], a['so'] + len(a['tt']),
                      add_attr(a['tt'], 'data-i18n-' + a['attr'], key)))

    out = src
    for start, end, repl in sorted(edits, key=lambda x: -x[0]):
        out = out[:start] + repl + out[end:]
    return out, ru

def collect(src):
    """Собрать ru-словарь из уже размеченного HTML."""
    p, emap = parse(src)
    ru = {}
    for e in p.elems:
        k = e['attrs'].get('data-i18n')
        if k: ru[k] = htmlmod.unescape(src[e['inner_start']:e['inner_end']].strip())
    class A(HTMLParser):
        def handle_starttag(self, tag, attrs):
            a = dict(attrs)
            for an in I18N_ATTRS:
                k = a.get('data-i18n-' + an)
                if k: ru[k] = htmlmod.unescape(a.get(an, ''))
        def handle_startendtag(self, tag, attrs): self.handle_starttag(tag, attrs)
    A().feed(src)
    return ru


# --- строки, которые живут в JS (данные АЗС, галерея, калькулятор, тексты оферты) ---

def collect_js(src):
    """Русские значения из inline-скрипта. Ключи те же, что запрашивает AZK.t()."""
    out = {}

    def fields(chunk, names):
        got = {}
        for n in names:
            m = re.search(n + r":\s*'((?:[^'\\]|\\.)*)'", chunk)
            if m: got[n] = m.group(1).replace("\\'", "'")
        return got

    m = re.search(r'const galleryData = \[(.*?)\n  \];', src, re.S)
    if m:
        for i, line in enumerate(re.findall(r'\{[^\n]*\}', m.group(1))):
            for k, v in fields(line, ['badge', 'title', 'subtitle']).items():
                out[f'gallery.{i}.{k}'] = v

    m = re.search(r'const stations = \{(.*?)\n  \};', src, re.S)
    if m:
        for sid, body in re.findall(r"'(azs-\d+)':\s*(\{[^\n]*\})", m.group(1)):
            for k, v in fields(body, ['name', 'address', 'direction']).items():
                out[f'station.{sid}.{k}'] = v
            am = re.search(r'amenities:\s*\[(.*?)\]', body)
            if am:
                out[f'station.{sid}.amenities'] = re.findall(r"'([^']*)'", am.group(1))

    m = re.search(r'const policyContent = \{(.*?)\n  \};', src, re.S)
    if m:
        for name, body in re.findall(r'\n    (privacy|terms):\s*\{(.*?)\n    \}', m.group(1), re.S):
            t = re.search(r"title:\s*'((?:[^'\\]|\\.)*)'", body)
            b = re.search(r'body:\s*`(.*?)`', body, re.S)
            if t: out[f'policy.{name}.title'] = t.group(1)
            if b: out[f'policy.{name}.body'] = b.group(1)

    for call in re.findall(r"AZK\.t\('([a-z.0-9]+)',\s*'((?:[^'\\]|\\.)*)'\)", src):
        out[call[0]] = call[1]
    for fuel in re.findall(r'data-fuel="(\w+)"[^>]*data-code="([^"]+)"', src):
        out['calc.code.' + fuel[0]] = fuel[1]

    return out

if __name__ == '__main__':
    src = open(SRC, encoding='utf-8').read()
    if '--annotate' in sys.argv:
        if 'data-i18n=' in src:
            sys.exit('index.html уже размечен — повторный --annotate запрещён. Используйте запуск без флага.')
        out, ru = annotate(src)
        open(SRC, 'w', encoding='utf-8').write(out)
        ru.update(collect_js(out))
        print(f'размечено ключей: {len(ru)}')
    else:
        ru = collect(src); ru.update(collect_js(src))
        print(f'собрано ключей: {len(ru)}')
    def sk(k):
        head, _, tail = k.rpartition('.')
        return (head, 0, int(tail)) if tail.isdigit() else (head, 1, tail)
    json.dump({k: ru[k] for k in sorted(ru, key=sk)}, open(OUT, 'w', encoding='utf-8'),
              ensure_ascii=False, indent=2)
    print('→', OUT)
