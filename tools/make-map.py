#!/usr/bin/env python3
"""
Статичная карта сети АЗС: реальные тайлы OpenStreetMap + наши маркеры.

  python3 tools/make-map.py

Координаты берутся из STATIONS ниже — это единственное, что нужно править.
Скрипт кладёт `assets/images/azs-map.jpg` и печатает позиции маркеров в процентах
для вставки в index.html.

ВАЖНО: координаты должны быть настоящими. Геокодер по адресам клиента
(«квартал 223», «с. Касымбек Датка») ошибается на километры, поэтому точки
берём либо от клиента, либо с карточек организации на картах.
"""
import io as _io, json, math, os, sys, urllib.request
from PIL import Image, ImageDraw

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT_IMG = os.path.join(ROOT, 'assets', 'images', 'azs-map.jpg')
TILE = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
UA = 'almaz-azk-site/1.0 (one-off static map build)'
WIDTH, HEIGHT = 1200, 800
PAD = 0.22          # запас вокруг крайних точек, доля от размаха

# id, подпись, широта, долгота, точность
STATIONS = [
    ('azs-1', 'АЗС-1', 42.2739, 69.5545, 'приблизительно'),
    ('azs-2', 'АЗС-2', 42.4504, 69.5354, 'приблизительно'),
    ('azs-3', 'АЗС-3', 42.4812, 69.6334, 'приблизительно'),
    ('azs-4', 'АЗС-4', 42.3642, 69.7116, 'приблизительно'),
]

def deg2num(lat, lon, z):
    n = 2.0 ** z
    x = (lon + 180.0) / 360.0 * n
    y = (1.0 - math.asinh(math.tan(math.radians(lat))) / math.pi) / 2.0 * n
    return x, y

def pick_zoom(lats, lons):
    """Максимальный зум, при котором все точки с запасом влезают в кадр."""
    for z in range(16, 5, -1):
        xs = [deg2num(la, lo, z)[0] for la, lo in zip(lats, lons)]
        ys = [deg2num(la, lo, z)[1] for la, lo in zip(lats, lons)]
        w = (max(xs) - min(xs)) * 256 * (1 + 2 * PAD)
        h = (max(ys) - min(ys)) * 256 * (1 + 2 * PAD)
        if w <= WIDTH and h <= HEIGHT:
            return z
    return 6

def fetch(z, x, y):
    url = TILE.format(z=z, x=x, y=y)
    req = urllib.request.Request(url, headers={'User-Agent': UA})
    return Image.open(_io.BytesIO(urllib.request.urlopen(req, timeout=30).read())).convert('RGB')

def main():
    lats = [s[2] for s in STATIONS]; lons = [s[3] for s in STATIONS]
    z = pick_zoom(lats, lons)
    cx, cy = deg2num((min(lats) + max(lats)) / 2, (min(lons) + max(lons)) / 2, z)

    # левый верхний угол кадра в тайловых координатах
    x0 = cx - WIDTH / 512.0
    y0 = cy - HEIGHT / 512.0
    canvas = Image.new('RGB', (WIDTH, HEIGHT), '#e8e3dc')
    tx0, ty0 = math.floor(x0), math.floor(y0)
    tx1, ty1 = math.ceil(x0 + WIDTH / 256.0), math.ceil(y0 + HEIGHT / 256.0)
    got = 0
    for tx in range(tx0, tx1):
        for ty in range(ty0, ty1):
            try:
                canvas.paste(fetch(z, tx, ty), (int((tx - x0) * 256), int((ty - y0) * 256)))
                got += 1
            except Exception as e:
                print(f'  тайл {z}/{tx}/{ty} не скачался: {e}', file=sys.stderr)
    print(f'зум {z}, тайлов загружено: {got}')

    draw = ImageDraw.Draw(canvas, 'RGBA')
    draw.rectangle([0, HEIGHT - 20, WIDTH, HEIGHT], fill=(255, 255, 255, 190))
    draw.text((8, HEIGHT - 14), '© OpenStreetMap contributors', fill=(60, 60, 60))

    positions = {}
    for sid, label, lat, lon, _ in STATIONS:
        px, py = deg2num(lat, lon, z)
        positions[sid] = {'left': round((px - x0) * 256 / WIDTH * 100, 2),
                          'top': round((py - y0) * 256 / HEIGHT * 100, 2)}

    canvas.save(OUT_IMG, 'JPEG', quality=86, optimize=True, progressive=True)
    print('карта:', os.path.relpath(OUT_IMG, ROOT), canvas.size)
    print('позиции маркеров (% от размера карты):')
    for sid, p in positions.items():
        print(f"  {sid}:  top:{p['top']}%; left:{p['left']}%;")
    json.dump(positions, open(os.path.join(ROOT, 'assets', 'images', 'azs-map.json'), 'w'), indent=2)

if __name__ == '__main__':
    main()
