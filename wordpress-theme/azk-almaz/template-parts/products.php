<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '8 775 865 3737');
$title    = azk_field('products_title', 'Качественные нефтепродукты');
$subtitle = azk_field('products_subtitle', 'Все марки топлива поставляются напрямую с ведущих НПЗ и проходят обязательный лабораторный контроль с выдачей Паспорта качества.');
$lab_image = azk_image('lab_image', 'lab.jpg');

$products = [];
if (function_exists('have_rows') && have_rows('products')) {
    while (have_rows('products')) { the_row();
        $specs = [];
        if (have_rows('specs')) { while (have_rows('specs')) { the_row(); $specs[] = ['label' => get_sub_field('label'), 'value' => get_sub_field('value')]; } }
        $features = [];
        if (have_rows('features')) { while (have_rows('features')) { the_row(); $features[] = get_sub_field('feature'); } }
        $products[] = [
            'id' => get_sub_field('id'), 'name' => get_sub_field('name'), 'code' => get_sub_field('code'),
            'description' => get_sub_field('description'), 'specs' => $specs, 'features' => $features,
            'price_per_liter' => (float) get_sub_field('price_per_liter'), 'density' => (float) get_sub_field('density'),
        ];
    }
}
if (!$products) $products = azk_default('products');

/* Только позиции с подтверждённой оптовой ценой участвуют в калькуляторе — */
/* напр. автогаз показывается карточкой продукта, но без оптовой цены пока не согласована. */
$calc_products = array_values(array_filter($products, function ($p) { return $p['price_per_liter'] > 0; }));

$calc_data = [];
foreach ($calc_products as $p) {
    $calc_data[$p['id']] = ['name' => $p['name'], 'code' => $p['code'], 'price' => $p['price_per_liter'], 'density' => $p['density']];
}
?>
<section id="products" class="py-16 bg-white border-t border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Продукция Евро-5</div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
      <p class="mt-3 text-slate-600 text-base"><?php echo esc_html($subtitle); ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <?php foreach ($products as $p): ?>
        <div class="bg-[#F8FAFC] rounded-2xl border-2 border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-navy transition-all flex flex-col justify-between">
          <div>
            <div class="bg-navy text-white p-6">
              <div class="flex items-center justify-between mb-2"><span class="text-2xl font-black text-accent"><?php echo esc_html($p['name']); ?></span><span class="text-xs bg-white/10 text-slate-300 px-2.5 py-1 rounded border border-white/20 font-mono"><?php echo esc_html($p['code']); ?></span></div>
              <?php if ($p['specs']): ?>
                <div class="inline-flex items-center space-x-1.5 bg-leaf/20 border border-leaf/50 px-3 py-1 rounded text-xs font-bold text-leaf mt-1"><i data-lucide="shield-check" class="w-3.5 h-3.5"></i><span>Соответствует требованиям качества</span></div>
              <?php endif; ?>
            </div>
            <div class="p-6 space-y-4">
              <p class="text-sm text-slate-600 leading-relaxed min-h-[60px]"><?php echo esc_html($p['description']); ?></p>
              <?php if ($p['specs']): ?>
              <div class="space-y-2 pt-2 border-t border-slate-200">
                <div class="text-xs font-bold text-navy uppercase tracking-wider">Технические характеристики:</div>
                <?php foreach ($p['specs'] as $i => $spec): ?>
                  <div class="flex justify-between items-center text-xs py-1<?php echo $i < count($p['specs']) - 1 ? ' border-b border-slate-100' : ''; ?>"><span class="text-slate-500"><?php echo esc_html($spec['label']); ?></span><span class="font-bold text-slate-800"><?php echo esc_html($spec['value']); ?></span></div>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              <div class="space-y-1.5 pt-2">
                <?php foreach ($p['features'] as $f): ?>
                  <div class="flex items-center space-x-2 text-xs text-slate-700"><i data-lucide="check" class="w-3.5 h-3.5 text-leaf shrink-0"></i><span><?php echo esc_html($f); ?></span></div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="p-6 pt-0 space-y-2">
            <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="w-full bg-navy hover:bg-navy-light text-white font-bold text-sm py-3 px-4 rounded-lg flex items-center justify-center space-x-2 shadow"><span>Запросить оптовую цену</span><i data-lucide="arrow-right" class="w-4 h-4 text-accent"></i></a>
            <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="w-full text-xs text-slate-500 hover:text-navy font-medium text-center py-1 flex items-center justify-center space-x-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i><span>Запросить Паспорт качества</span></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-md bg-gradient-to-r from-navy via-navy-light to-navy-dark text-white p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
      <div class="md:col-span-8 space-y-3">
        <div class="inline-flex items-center space-x-2 bg-leaf/20 text-leaf border border-leaf/40 px-3 py-1 rounded-full text-xs font-extrabold"><i data-lucide="microscope" class="w-4 h-4"></i><span>100% Входной &amp; Выходной Лабораторный Контроль</span></div>
        <h3 class="text-xl sm:text-2xl font-black">Каждая партия сопровождается химическим анализом и Паспортом Качества</h3>
        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl">Топливо проходит многоступенчатую проверку плотности, октанового/цетанового числа, отсутствия механических примесей и воды на оборудовании собственного лабораторного комплекса.</p>
        <div class="pt-2"><a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-extrabold text-xs px-4 py-2.5 rounded-lg shadow inline-flex items-center space-x-2"><span>Позвонить за паспортами качества</span><i data-lucide="arrow-right" class="w-4 h-4"></i></a></div>
      </div>
      <div class="md:col-span-4 h-48 md:h-full min-h-[160px] rounded-xl overflow-hidden border border-slate-700/80 relative">
        <img src="<?php echo esc_url($lab_image); ?>" alt="Лаборатория контроля качества" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0A1E36]/80 via-transparent to-transparent"></div>
        <div class="absolute bottom-2 left-3 text-[11px] font-bold text-white bg-navy/80 px-2 py-0.5 rounded backdrop-blur">Лаборатория АЗК Алмаз</div>
      </div>
    </div>

    <div class="bg-navy text-white rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6" data-calc-products="<?php echo esc_attr(wp_json_encode($calc_data)); ?>">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 rounded-xl bg-accent text-navy flex items-center justify-center shadow-md"><i data-lucide="calculator" class="w-6 h-6 stroke-[2.5]"></i></div>
          <div><h3 class="text-xl font-bold">Калькулятор оптовой стоимости топлива</h3><p class="text-xs text-slate-300">Быстрый ориентировочный расчёт объёма и логистики для юрлиц и ИП</p></div>
        </div>
        <div class="text-xs bg-white/10 px-3 py-1.5 rounded-md border border-white/15 text-slate-300 flex items-center space-x-2"><i data-lucide="info" class="w-4 h-4 text-accent"></i><span>Окончательная цена зависит от объёма и базиса отгрузки</span></div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 space-y-4">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">1. Выберите марку топлива:</label>
            <div class="grid grid-cols-3 gap-2" id="calc-fuel-buttons">
              <?php foreach ($calc_products as $i => $p): ?>
                <button data-fuel="<?php echo esc_attr($p['id']); ?>" class="calc-fuel-btn py-2.5 px-3 rounded-lg text-sm font-bold border text-center <?php echo $i === 0 ? 'bg-accent text-navy border-accent shadow' : 'bg-navy-light text-slate-200 border-slate-700 hover:border-slate-500'; ?>"><?php echo esc_html($p['name']); ?></button>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <div class="flex justify-between items-center mb-2">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-300">2. Объём партии:</label>
                <div class="inline-flex rounded border border-slate-700 p-0.5 bg-navy-dark">
                  <button id="unit-liters" class="px-2 py-0.5 text-xs font-bold rounded bg-accent text-navy">Литры</button>
                  <button id="unit-tons" class="px-2 py-0.5 text-xs font-bold rounded text-slate-400">Тонны</button>
                </div>
              </div>
              <input type="number" id="calc-volume" min="1000" step="1000" value="10000" class="w-full bg-navy-light border border-slate-700 text-white font-bold text-lg px-4 py-2.5 rounded-lg focus:outline-none focus:border-accent">
              <div class="text-[11px] text-slate-400 mt-1">Минимальный оптовый заказ: 1 000 литров</div>
            </div>
            <div>
              <label class="flex items-center h-[26px] text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">3. Базис поставки / Пункт назначения:</label>
              <select id="calc-destination" class="w-full bg-navy-light border border-slate-700 text-white font-medium text-sm px-3 py-3.5 rounded-lg focus:outline-none focus:border-accent">
                <option>г. Шымкент (в пределах города)</option>
                <option>Самовывоз с нефтебазы (г. Шымкент)</option>
                <option>г. Туркестан (доставка бензовозом)</option>
                <option>Сайрамский район</option>
                <option>г. Кентау / г. Арыс</option>
                <option>Другой район Туркестанской области</option>
              </select>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5 bg-navy-light rounded-xl p-6 border border-slate-700 flex flex-col justify-between space-y-4">
          <div class="space-y-3">
            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Предварительный расчёт:</div>
            <div class="flex justify-between items-baseline py-1 border-b border-slate-700/80"><span class="text-xs text-slate-300">Продукт:</span><span id="calc-out-product" class="text-sm font-bold text-white">—</span></div>
            <div class="flex justify-between items-baseline py-1 border-b border-slate-700/80"><span class="text-xs text-slate-300">Расчётный объём:</span><span id="calc-out-volume" class="text-sm font-bold text-white">—</span></div>
            <div class="pt-2">
              <div class="text-xs text-slate-400 mb-0.5">Ориентировочная сумма:</div>
              <div id="calc-out-total" class="text-3xl font-black text-accent">—</div>
              <div id="calc-out-rate" class="text-[11px] text-slate-400 mt-1"></div>
            </div>
          </div>
          <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="w-full bg-accent hover:bg-accent-dark text-navy font-extrabold py-3.5 px-4 rounded-lg text-sm shadow flex items-center justify-center space-x-2"><span>Оформить заявку на этот объём</span><i data-lucide="arrow-right" class="w-4 h-4"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
