#!/usr/bin/env python3
"""
Оборачивает зашитые в шаблоны темы русские строки в <?php azk_e('…'); ?>.

  python3 tools/wrap-theme-strings.py --check   # показать, что будет сделано
  python3 tools/wrap-theme-strings.py           # применить

Разовая операция: повторный запуск ничего не найдёт, потому что текст уже внутри PHP.
"""
import glob, io, os, re, sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
THEME = os.path.join(ROOT, 'wordpress-theme', 'azk-almaz')
CYR = re.compile(r'[А-Яа-яЁё]')
FILES = sorted(glob.glob(os.path.join(THEME, 'template-parts', '*.php'))) + \
        [os.path.join(THEME, f) for f in ('header.php', 'footer.php', 'front-page.php')]

def php_mask(src):
    """PHP-острова заменяем на placeholder той же длины — смещения не плывут."""
    out = list(src)
    for m in re.finditer(r'<\?php.*?\?>|<\?php.*$', src, re.S):
        for i in range(m.start(), m.end()): out[i] = '\x00'
    return ''.join(out)

def spans(src):
    """Границы текстовых узлов с кириллицей (вне тегов и вне PHP)."""
    masked = php_mask(src)
    res, pos = [], 0
    for tag in re.finditer(r'<[^>]*>', masked):
        res += chunk_spans(masked, pos, tag.start())
        pos = tag.end()
    res += chunk_spans(masked, pos, len(masked))
    return res

def chunk_spans(masked, a, b):
    out = []
    for part in re.finditer(r'[^\x00]+', masked[a:b]):
        s, e = a + part.start(), a + part.end()
        text = masked[s:e]
        stripped = text.strip()
        if not stripped or not CYR.search(stripped): continue
        lead = len(text) - len(text.lstrip())
        out.append((s + lead, s + lead + len(stripped)))
    return out

def main():
    check = '--check' in sys.argv
    total = 0
    for path in FILES:
        if not os.path.exists(path): continue
        src = io.open(path, encoding='utf-8').read()
        sp = spans(src)
        if not sp: continue
        total += len(sp)
        if check:
            print(f'{os.path.relpath(path, ROOT)}: {len(sp)}')
            for a, b in sp[:3]: print('    ', repr(src[a:b][:60]))
            continue
        out = src
        for a, b in sorted(sp, key=lambda x: -x[0]):
            text = out[a:b]
            lit = text.replace('\\', '\\\\').replace("'", "\\'")
            out = out[:a] + "<?php azk_e('" + lit + "'); ?>" + out[b:]
        io.open(path, 'w', encoding='utf-8').write(out)
        print(f'{os.path.relpath(path, ROOT)}: обёрнуто {len(sp)}')
    print(('нашлось' if check else 'обёрнуто') + f' строк: {total}')

if __name__ == '__main__':
    main()
