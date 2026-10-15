#!/bin/bash
# Включает принудительный HTTPS после выпуска сертификата.
#
#   bash tools/enable-https.sh
#
# Сначала проверяет, что сертификат на azkalmaz.kz действительно валидный.
# Если нет — ничего не трогает: с редиректом на сломанный HTTPS сайт ляжет.
set -euo pipefail
cd "$(dirname "$0")/.."

HOST=185.98.5.138
U=$(grep '^- Логин:'  password.md | sed 's/.*: *//')
P=$(grep '^- Пароль:' password.md | sed 's/.*: *//')

echo "Проверяю сертификат…"
for d in azkalmaz.kz www.azkalmaz.kz; do
  if curl -s -m 15 -o /dev/null "https://$d/"; then
    echo "  $d — сертификат валидный"
  else
    echo "  $d — сертификат невалидный или HTTPS не отвечает."
    echo "  Выпустите Let's Encrypt в Plesk (с галочкой на www) и запустите скрипт снова."
    exit 1
  fi
done

echo "Включаю редирект в .htaccess…"
python3 - <<'PY'
import io
p = '.htaccess'
s = io.open(p, encoding='utf-8').read()
if '\n  RewriteCond %{HTTPS} !=on' in s:
    print('  уже включён'); raise SystemExit
for a, b in (('  # RewriteCond %{HTTPS} !=on',               '  RewriteCond %{HTTPS} !=on'),
             ('  # RewriteCond %{HTTP:X-Forwarded-Proto} !https', '  RewriteCond %{HTTP:X-Forwarded-Proto} !https'),
             ('  # RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]',
              '  RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]')):
    assert s.count(a) == 1, a
    s = s.replace(a, b)
io.open(p, 'w', encoding='utf-8').write(s)
print('  раскомментирован')
PY

curl -s --ftp-create-dirs -u "$U:$P" -T .htaccess "ftp://$HOST/httpdocs/.htaccess"
echo "Залит на сервер. Проверяю…"
sleep 2
code=$(curl -s -m 15 -o /dev/null -w '%{http_code}' http://azkalmaz.kz/)
dest=$(curl -s -m 15 -o /dev/null -w '%{redirect_url}' http://azkalmaz.kz/)
echo "  http://azkalmaz.kz/ → $code $dest"
for u in "" en/ kz/ zh/; do
  printf '  https://azkalmaz.kz/%-4s %s\n' "$u" "$(curl -s -m 15 -o /dev/null -w '%{http_code}' "https://azkalmaz.kz/$u")"
done
