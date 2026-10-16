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
# Одна русская строка может переводиться по-разному в разных местах (меню, подвал,
# подпись маркера). Для таких случаев в таблицу кладётся ключ «контекст|текст»,
# а шаблон передаёт контекст вторым аргументом azk_e().
CONTEXTS = {
    'nav':          ['header.4','header.5','header.6','header.7','header.8','header.9'],
    'nav_mobile':   ['header.14','header.15','header.16','header.17','header.18','header.19'],
    'footer':       ['footer.5','footer.6','footer.7','footer.8','footer.9','footer.10'],
    'marker':       ['azs-network.9','azs-network.10','azs-network.11','azs-network.12'],
    'station_card': ['azs-network.20','azs-network.29','azs-network.39','azs-network.49'],
}

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
    # Части фразы «Наличие <жирным> позволяет…» — в статике это три отдельных ключа.
    'Наличие':                                            {'en': 'Our',  'kk': 'Компанияның', 'zh': '公司拥有'},
    'собственной современной нефтебазы в г. Шымкент':     {'en': 'own modern tank farm in Shymkent',
                                                           'kk': 'Шымкент қаласындағы меншікті заманауи мұнай базасы',
                                                           'zh': '位于奇姆肯特市的自有现代化油库。'},
    ' позволяет гарантировать непрерывность поставок, точный учёт объёмов и строгое соблюдение всех технических стандартов.':
        {'en': 'allows us to guarantee uninterrupted supply, accurate volume accounting and strict compliance with all technical standards.',
         'kk': 'бар — бұл жеткізілімнің үздіксіздігіне, көлемнің дәл есебіне және барлық техникалық стандарттардың қатаң сақталуына кепілдік береді.',
         'zh': '这确保供应不间断、计量准确，并严格遵守各项技术标准。'},
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

# подписи фильтров по региону: в статике это целые строки со счётчиком, в теме — части
EXTRA.update({
    # фильтры переводятся вместе со счётчиком: в китайском у них свой счётный суффикс
    'Все АЗС (4)':              {'en': 'All stations (4)', 'kk': 'Барлық АЖҚС (4)', 'zh': '全部加油站（4座）'},
    'Шымкент (3)':              {'en': 'Shymkent (3)',     'kk': 'Шымкент (3)',     'zh': '奇姆肯特（3座）'},
    'Туркестанская обл. (1)':   {'en': 'Turkistan Region (1)', 'kk': 'Түркістан обл. (1)', 'zh': '突厥斯坦州（1座）'},
    'Оптовая реализация: +7 (707) 729-40-51':
        {'en': 'Wholesale: +7 (707) 729-40-51', 'kk': 'Көтерме сату: +7 (707) 729-40-51',
         'zh': '批发销售：+7 (707) 729-40-51'},
    'БИН: 011240001881': {'en': 'BIN: 011240001881', 'kk': 'БСН: 011240001881',
                          'zh': '企业识别号（BIN）：011240001881'},
    'ТОО «АЗК Алмаз» • Светлые нефтепродукты • с 1998 года':
        {'en': 'AZK Almaz LLP • Light petroleum products • since 1998',
         'kk': '«АЗК Алмаз» ЖШС • Ашық мұнай өнімдері • 1998 жылдан бері',
         'zh': 'AZK Almaz 有限责任公司 • 轻质油品 • 自1998年起'},
    'АЗС: Круглосуточно, 24/7': {'en': 'Stations: round the clock, 24/7',
                                 'kk': 'АЖҚС: Тәулік бойы, 24/7', 'zh': '加油站：全天候 24/7'},
    '© 1998 - 2026 ТОО «АЗК Алмаз». Все права защищены.':
        {'en': '© 1998 – 2026 AZK Almaz LLP. All rights reserved.',
         'kk': '© 1998 - 2026 «АЗК Алмаз» ЖШС. Барлық құқық қорғалған.',
         'zh': '© 1998 - 2026 AZK Almaz 有限责任公司。保留所有权利。'},
    'БИН 011240001881': {'en': 'BIN 011240001881', 'kk': 'БСН 011240001881',
                         'zh': '企业识别号（BIN）011240001881'},
    'filter|Шымкент':           {'en': 'Shymkent',        'kk': 'Шымкент',          'zh': '奇姆肯特'},
    'filter|Туркестанская обл.': {'en': 'Turkistan Region', 'kk': 'Түркістан обл.',  'zh': '突厥斯坦州'},
    # график в верхней полосе: у казахского он сжат, иначе полоса переносится
    'Офис и нефтебаза: Пн - Пт: 08:00 - 17:00, Сб: 08:00 - 13:00':
        {'en': 'Office and tank farm: Mon – Fri: 08:00 – 17:00, Sat: 08:00 – 13:00',
         'kk': 'Кеңсе, мұнай базасы: Дс-Жм: 08:00-17:00, Сн: 08:00-13:00',
         'zh': '办公室及油库：周一至周五：08:00 - 17:00，周六：08:00 - 13:00'},
    'topbar|Пн - Пт: 08:00 - 17:00, Сб: 08:00 - 13:00':
        {'en': 'Mon – Fri: 08:00 – 17:00, Sat: 08:00 – 13:00',
         'kk': 'Дс-Жм: 08:00-17:00, Сн: 08:00-13:00',
         'zh': '周一至周五：08:00 - 17:00，周六：08:00 - 13:00'},
})

# контекстные варианты из ключей статики
for ctx, keys in CONTEXTS.items():
    for k in keys:
        rus = ru.get(k)
        if not isinstance(rus, str): continue
        row = {l: langs[l].get(k) for l in ('en','kk','zh')}
        if all(isinstance(v, str) and v.strip() for v in row.values()):
            table[ctx + '|' + rus.strip()] = {l: v.strip() for l, v in row.items()}

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
          "/**",
          " * Перевод строки по её русскому тексту.",
          " *",
          " * $ctx различает места, где один и тот же русский переводится по-разному:",
          " * «О компании» в меню это About, а в подвале — About the company.",
          " * Нет перевода — возвращаем текст как есть.",
          " */",
          "function azk_translate($text, $lang = null, $ctx = '') {",
          "    $lang = $lang ?: azk_lang();",
          "    if ($lang === 'ru' || !is_string($text) || $text === '') return $text;",
          "    $t = azk_translations();",
          "    $k = trim($text);",
          "    if ($ctx !== '' && isset($t[$ctx . '|' . $k][$lang])) return $t[$ctx . '|' . $k][$lang];",
          "    return isset($t[$k][$lang]) ? $t[$k][$lang] : $text;",
          "}", "",
          "/**",
          " * Печатает зашитую в шаблон строку с учётом языка.",
          " *",
          " * Перевода нет — печатаем исходник как есть, вместе с сущностями вроде &amp;.",
          " * Перевод есть — экранируем его.",
          " */",
          "function azk_e($text, $ctx = '') {",
          "    $plain = html_entity_decode($text, ENT_QUOTES, 'UTF-8');",
          "    $out = azk_translate($plain, null, $ctx);",
          "    echo ($out === $plain) ? $text : esc_html($out);",
          "}", ""]
io.open(OUT, 'w', encoding='utf-8').write("\n".join(lines))
print(f'строк в таблице: {len(table)} (пропущено без полного набора переводов: {skipped})')
print('→', os.path.relpath(OUT, ROOT))
