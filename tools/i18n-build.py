#!/usr/bin/env python3
"""
Сборка языковых страниц /en/, /kz/, /zh/ из index.html и словарей.

  python3 tools/i18n-build.py

Русская версия остаётся в index.html и правится вручную — это источник истины.
Три остальные страницы генерируются: текст подставляется из assets/i18n/<lang>.js,
пути к ассетам и ссылки переключателя переписываются на уровень выше.
Править файлы в en/, kz/, zh/ руками нельзя — их перезапишет следующая сборка.

Полный цикл после правки контента:
  python3 tools/i18n-extract.py && python3 tools/i18n-check.py && python3 tools/i18n-build.py
"""
import html as htmlmod
import importlib.util
import io, json, os, re, sys

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)
SRC = os.path.join(ROOT, 'index.html')
SITE = 'https://farik9797.github.io/almaz-azk/'
# код языка -> папка. У казахского папка kz: так привычнее посетителям, код остаётся kk.
DIRS = {'en': 'en', 'kk': 'kz', 'zh': 'zh'}

spec = importlib.util.spec_from_file_location('extract', os.path.join(HERE, 'i18n-extract.py'))
extract = importlib.util.module_from_spec(spec); spec.loader.exec_module(extract)


def load_dict(lang):
    src = io.open(os.path.join(ROOT, 'assets', 'i18n', lang + '.js'), encoding='utf-8').read()
    m = re.search(r'=\s*(\{.*\});\s*$', src, re.S)
    if not m: sys.exit(f'{lang}.js: не нашёл объект словаря')
    return json.loads(m.group(1))


def render(src, lang, d):
    p, emap = extract.parse(src)
    edits = []

    for e in p.elems:
        key = e['attrs'].get('data-i18n')
        if not key or key not in d: continue
        raw = src[e['inner_start']:e['inner_end']]
        lead = raw[:len(raw) - len(raw.lstrip())]
        trail = raw[len(raw.rstrip()):]
        value = htmlmod.escape(str(d[key]), quote=False)
        edits.append((e['inner_start'], e['inner_end'], lead + value + trail))

    # атрибуты (alt, title, aria-label, content, ...) — по data-i18n-<attr>
    for tag in re.finditer(r'<[a-zA-Z][^>]*\sdata-i18n-[a-z-]+="[^"]+"[^>]*>', src):
        text = tag.group(0); new = text
        for attr, key in re.findall(r'data-i18n-([a-z-]+)="([^"]+)"', text):
            if key not in d: continue
            new = re.sub(r'(\s%s=")[^"]*(")' % re.escape(attr),
                         lambda m: m.group(1) + htmlmod.escape(str(d[key]), quote=True) + m.group(2),
                         new, count=1)
        if new != text:
            edits.append((tag.start(), tag.end(), new))

    out = src
    for start, end, repl in sorted(edits, key=lambda x: -x[0]):
        out = out[:start] + repl + out[end:]

    folder = DIRS[lang]
    htmllang = {'en': 'en', 'kk': 'kk', 'zh': 'zh-Hans'}[lang]
    # Отмечаем, какой язык уже свёрстан в файле: движок по нему решает, прятать страницу или нет.
    out = out.replace('<html lang="ru">', f'<html lang="{htmllang}" data-i18n-baked="{lang}">', 1)
    out = out.replace(f'<link rel="canonical" href="{SITE}">',
                      f'<link rel="canonical" href="{SITE}{folder}/">', 1)
    # страница лежит на уровень глубже — ассеты и ссылки переключателя ведут наверх
    out = out.replace('"assets/', '"../assets/').replace("'assets/", "'../assets/")
    out = re.sub(r'(<a href=")(\./|en/|kz/|zh/)(" data-langlink)', r'\1../\2\3', out)
    out = out.replace('<a href=".././" data-langlink', '<a href="../" data-langlink')
    out = ('<!-- Сгенерировано tools/i18n-build.py из index.html. Руками не править. -->\n' + out)
    return out


def main():
    src = io.open(SRC, encoding='utf-8').read()
    if 'data-langlink' not in src:
        sys.exit('index.html: не нашёл ссылки переключателя (data-langlink)')
    for lang, folder in DIRS.items():
        d = load_dict(lang)
        path = os.path.join(ROOT, folder, 'index.html')
        os.makedirs(os.path.dirname(path), exist_ok=True)
        io.open(path, 'w', encoding='utf-8').write(render(src, lang, d))
        print(f'/{folder}/  ←  {lang} ({len(d)} строк)')


if __name__ == '__main__':
    main()
