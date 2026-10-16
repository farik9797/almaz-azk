#!/usr/bin/env python3
"""
Собирает inc/translations.php для темы WordPress из словарей статики.

  python3 tools/make-theme-translations.py

Ключ — русский текст, значение — переводы. Такой словарь позволяет теме
переводить всё, что не переопределено в админке: контент в defaults.php
русский, а перевод подбирается по самому тексту. Ничего не перенабираем.
"""
import io, json, os, re, sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, 'wordpress-theme', 'azk-almaz', 'inc', 'translations.php')

def load_js(lang):
    src = io.open(os.path.join(ROOT, 'assets', 'i18n', lang + '.js'), encoding='utf-8').read()
    m = re.search(r'=\s*(\{.*\});\s*$', src, re.S)
    if not m: sys.exit(f'{lang}.js: не нашёл словарь')
    return json.loads(m.group(1))

# Обрывки фраз, разрезанные PHP-вставками в шаблонах (год, БИН, счётчики, телефон).
# В статике это цельные строки, здесь — части, поэтому переводим отдельно и
# следим, чтобы склейка с подставляемым значением читалась на каждом языке.
EXTRA = {
    'Все АЗС (':            {'en': 'All stations (',        'kk': 'Барлық АЖҚС (',     'zh': '全部加油站（'},
    'АЗС:':                 {'en': 'Stations:',             'kk': 'АЖҚС:',             'zh': '加油站：'},
    'БИН:':                 {'en': 'BIN:',                  'kk': 'БСН:',              'zh': '企业识别号（BIN）：'},
    'БИН':                  {'en': 'BIN',                   'kk': 'БСН',               'zh': '企业识别号（BIN）'},
    'Офис и нефтебаза:':    {'en': 'Office and tank farm:', 'kk': 'Кеңсе, мұнай базасы:', 'zh': '办公室及油库：'},
    'Оптовая реализация:':  {'en': 'Wholesale:',            'kk': 'Көтерме сату:',     'zh': '批发销售：'},
    # «ТОО … • с» + 1998 + «года»
    'ТОО «АЗК Алмаз» • Светлые нефтепродукты • с':
        {'en': 'AZK Almaz LLP • Light petroleum products • since',
         'kk': '«АЗК Алмаз» ЖШС • Ашық мұнай өнімдері •',
         'zh': 'AZK Almaz 有限责任公司 • 轻质油品 • 自'},
    'года':                 {'en': '',                      'kk': 'жылдан бері',       'zh': '年起'},
    'Круглосуточно, 24/7':  {'en': 'Round the clock, 24/7', 'kk': 'Тәулік бойы, 24/7', 'zh': '全天候 24/7'},
    # В статике эта фраза разбита на три части жирным выделением, в теме она одним полем.
    'Наличие собственной современной нефтебазы в г. Шымкент позволяет гарантировать непрерывность поставок, точный учёт объёмов и строгое соблюдение всех технических стандартов.':
        {'en': 'Our own modern tank farm in Shymkent allows us to guarantee uninterrupted supply, accurate volume accounting and strict compliance with all technical standards.',
         'kk': 'Компанияның Шымкент қаласындағы меншікті заманауи мұнай базасы бар — бұл жеткізілімнің үздіксіздігіне, көлемнің дәл есебіне және барлық техникалық стандарттардың қатаң сақталуына кепілдік береді.',
         'zh': '公司拥有位于奇姆肯特市的自有现代化油库，可确保供应不间断、计量准确，并严格遵守各项技术标准。'},
    'ТОО «АЗК Алмаз». Все права защищены.':
        {'en': 'AZK Almaz LLP. All rights reserved.',
         'kk': '«АЗК Алмаз» ЖШС. Барлық құқық қорғалған.',
         'zh': 'AZK Almaz 有限责任公司。保留所有权利。'},
}

ru = json.load(io.open(os.path.join(ROOT, 'assets', 'i18n', 'ru.reference.json'), encoding='utf-8'))
langs = {l: load_js(l) for l in ('en', 'kk', 'zh')}

table, skipped = {}, 0
for key, rus in ru.items():
    if not isinstance(rus, str): continue            # списки (удобства АЗС) переводятся поэлементно ниже
    rus = rus.strip()
    if not rus: continue
    row = {}
    for l, d in langs.items():
        v = d.get(key)
        if isinstance(v, str) and v.strip(): row[l] = v.strip()
    if len(row) != 3: skipped += 1; continue
    if rus in table and table[rus] != row: skipped += 1  # одинаковый русский с разным переводом — берём первый
    table.setdefault(rus, row)

# элементы списков (amenities) — тоже переводим поштучно
for key, rus in ru.items():
    if not isinstance(rus, list): continue
    for i, item in enumerate(rus):
        row = {}
        for l, d in langs.items():
            v = d.get(key)
            if isinstance(v, list) and i < len(v) and v[i].strip(): row[l] = v[i].strip()
        if len(row) == 3: table.setdefault(item.strip(), row)

table.update(EXTRA)

def q(s): return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"

lines = ["<?php", "if (!defined('ABSPATH')) exit;", "",
         "/**", " * Переводы, собранные из словарей статики (tools/make-theme-translations.py).",
         " * Ключ — русский текст, значение — переводы. Такой вид позволяет переводить",
         " * контент из defaults.php, не заводя языковое поле на каждую строку.",
         " *", " * Файл СГЕНЕРИРОВАН. Руками не править — перезапишется при следующей сборке.", " */",
         "function azk_translations() {", "    static $t = null;", "    if ($t !== null) return $t;",
         "    return $t = ["]
for rus in sorted(table):
    row = table[rus]
    lines.append("        %s => ['en' => %s, 'kk' => %s, 'zh' => %s]," %
                 (q(rus), q(row['en']), q(row['kk']), q(row['zh'])))
lines += ["    ];", "}", "",
          "/** Перевод строки по её русскому тексту. Нет перевода — возвращаем как есть. */",
          "function azk_translate($text, $lang = null) {",
          "    $lang = $lang ?: azk_lang();",
          "    if ($lang === 'ru' || !is_string($text) || $text === '') return $text;",
          "    $t = azk_translations();",
          "    $k = trim($text);",
          "    return isset($t[$k][$lang]) ? $t[$k][$lang] : $text;",
          "}", "",
          "/**",
          " * Печатает зашитую в шаблон строку с учётом языка.",
          " *",
          " * Перевода нет — печатаем исходник как есть, вместе с сущностями вроде &amp;.",
          " * Перевод есть — экранируем его.",
          " */",
          "function azk_e($text) {",
          "    $plain = html_entity_decode($text, ENT_QUOTES, 'UTF-8');",
          "    $out = azk_translate($plain);",
          "    echo ($out === $plain) ? $text : esc_html($out);",
          "}", ""]
io.open(OUT, 'w', encoding='utf-8').write("\n".join(lines))
print(f'строк в таблице: {len(table)} (пропущено без полного набора переводов: {skipped})')
print('→', os.path.relpath(OUT, ROOT))
