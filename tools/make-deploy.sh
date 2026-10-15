#!/bin/bash
# Собирает архив статики для заливки на хостинг.
#
#   bash tools/make-deploy.sh
#
# Перед сборкой поменяйте SITE в tools/i18n-build.py на боевой домен
# и прогоните python3 tools/i18n-build.py — от него зависят canonical,
# hreflang, robots.txt и sitemap.xml.
set -euo pipefail
cd "$(dirname "$0")/.."

OUT="deploy/almaz-azk-$(date +%Y%m%d-%H%M).zip"
mkdir -p deploy

python3 tools/i18n-build.py >/dev/null
python3 tools/i18n-check.py >/dev/null || { echo "словари или страницы разошлись — сборка остановлена"; exit 1; }

rm -f "$OUT"
zip -qr "$OUT" \
  index.html 404.html robots.txt sitemap.xml .htaccess \
  assets en kz zh \
  -x '*.DS_Store' 'assets/i18n/ru.reference.json' 'assets/images/azs-map.json'

echo "архив: $OUT  ($(du -h "$OUT" | cut -f1))"
echo "в нём:"
unzip -l "$OUT" | awk 'NR>3 && NF>3 {print "  " $4}' | sed 's|/.*||' | sort -u | grep -v '^$'
