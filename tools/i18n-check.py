#!/usr/bin/env python3
"""
Сверка переводов с русским эталоном.

  python3 tools/i18n-extract.py   # сначала перевыгрузить эталон из index.html
  python3 tools/i18n-check.py     # затем сверить en/kk/zh

Показывает: ключи без перевода, лишние ключи, несовпадение типов (строка/список)
и строки, где перевод дословно совпал с русским (обычно — забытый перевод).
"""
import importlib.util, io, json, os, re, sys

REF = 'assets/i18n/ru.reference.json'
LANGS = ['en', 'kk', 'zh']
CYR = re.compile(r'[А-Яа-яЁё]')

def load_js(path):
    src = open(path, encoding='utf-8').read()
    m = re.search(r'=\s*(\{.*\});\s*$', src, re.S)
    if not m: raise SystemExit(f'{path}: не нашёл объект словаря')
    return json.loads(m.group(1))

def check_built_pages():
    """Сверить en/, kz/, zh/ с тем, что собралось бы из текущего index.html."""
    here = os.path.dirname(os.path.abspath(__file__))
    spec = importlib.util.spec_from_file_location('build', os.path.join(here, 'i18n-build.py'))
    build = importlib.util.module_from_spec(spec); spec.loader.exec_module(build)
    src = io.open(build.SRC, encoding='utf-8').read()
    stale = []
    for lang, folder in build.DIRS.items():
        path = os.path.join(build.ROOT, folder, 'index.html')
        want = build.render(src, lang, build.load_dict(lang))
        have = io.open(path, encoding='utf-8').read() if os.path.exists(path) else None
        if have != want: stale.append(folder)
    if stale:
        print('страницы устарели: ' + ', '.join('/%s/' % f for f in stale)
              + '  → запустите python3 tools/i18n-build.py')
    else:
        print('страницы /en/, /kz/, /zh/ собраны из текущего index.html')
    return bool(stale)


def main():
    ref = json.load(open(REF, encoding='utf-8'))
    bad = False
    for lang in LANGS:
        path = f'assets/i18n/{lang}.js'
        if not os.path.exists(path):
            print(f'{lang}: файла нет'); bad = True; continue
        d = load_js(path)
        missing = [k for k in ref if k not in d]
        extra   = [k for k in d if k not in ref]
        types   = [k for k in ref if k in d and isinstance(ref[k], list) != isinstance(d[k], list)]
        # русский текст, оставшийся в переводе (кроме имён собственных и того, что и в оригинале латиницей)
        same    = [k for k in ref if k in d and isinstance(d[k], str)
                   and d[k].strip() == str(ref[k]).strip() and CYR.search(str(ref[k]))]
        print(f'{lang}: ключей {len(d)}/{len(ref)}'
              f' | без перевода {len(missing)} | лишних {len(extra)}'
              f' | тип не совпал {len(types)} | совпало с русским {len(same)}')
        for label, items, fatal in (('без перевода', missing, True), ('лишние', extra, True),
                                    ('тип не совпал', types, True),
                                    # не ошибка: часть терминов в казахском пишется так же (ГОСТ, АИ-92, Минимаркет)
                                    ('совпало с русским', same, False)):
            if items:
                bad = bad or fatal
                for k in items[:25]: print(f'    {label}: {k} = {str(ref.get(k))[:60]}')
                if len(items) > 25: print(f'    … и ещё {len(items)-25}')
    bad = check_built_pages() or bad
    return 1 if bad else 0

if __name__ == '__main__':
    sys.exit(main())
