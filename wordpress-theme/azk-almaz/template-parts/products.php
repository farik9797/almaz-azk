<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$phone_wholesale   = azk_setting('phone_wholesale', '+7 (707) 729-40-51');
$title    = azk_field('products_title', 'Качественные нефтепродукты');
$subtitle = azk_field('products_subtitle', 'Топливо поставляется напрямую с Шымкентского НПЗ — ТОО «ПетроКазахстан Ойл Продактс» — с паспортом качества завода-изготовителя на каждую партию.');

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
?>
<section id="products" class="py-16 bg-white border-t border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Продукция Евро-4</div>
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
            <a href="tel:<?php echo esc_attr(azk_tel($phone_wholesale)); ?>" class="w-full bg-navy hover:bg-navy-light text-white font-bold text-sm py-3 px-4 rounded-lg flex items-center justify-center space-x-2 shadow"><span>Запросить оптовую цену</span><i data-lucide="arrow-right" class="w-4 h-4 text-accent"></i></a>
            <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="w-full text-xs text-slate-500 hover:text-navy font-medium text-center py-1 flex items-center justify-center space-x-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i><span>Запросить Паспорт качества</span></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-md bg-gradient-to-r from-navy via-navy-light to-navy-dark text-white p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
      <div class="md:col-span-12 space-y-3">
        <div class="inline-flex items-center space-x-2 bg-leaf/20 text-leaf border border-leaf/40 px-3 py-1 rounded-full text-xs font-extrabold"><i data-lucide="file-check" class="w-4 h-4"></i><span>Паспорт качества завода-изготовителя</span></div>
        <h3 class="text-xl sm:text-2xl font-black">Качество ГСМ соответствует стандарту Евро-4</h3>
        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl">Топливо закупается напрямую у завода-изготовителя — ТОО «ПетроКазахстан Ойл Продактс» (Шымкентский нефтеперерабатывающий завод). На каждую партию предоставляется паспорт качества производителя.</p>
        <div class="pt-2"><a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-extrabold text-xs px-4 py-2.5 rounded-lg shadow inline-flex items-center space-x-2"><span>Позвонить за паспортами качества</span><i data-lucide="arrow-right" class="w-4 h-4"></i></a></div>
      </div>
    </div>

    <div class="bg-navy text-white rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-xl flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex items-center gap-4 flex-1">
        <div class="w-12 h-12 rounded-xl bg-accent text-navy flex items-center justify-center shadow-md shrink-0"><i data-lucide="phone-call" class="w-6 h-6 stroke-[2.5]"></i></div>
        <div>
          <h3 class="text-xl font-bold">Оптовая стоимость и наличие ГСМ</h3>
          <p class="text-sm text-slate-300 mt-1">Для расчёта оптовой стоимости и уточнения наличия ГСМ обращайтесь по телефону оптового отдела</p>
        </div>
      </div>
      <a href="tel:<?php echo esc_attr(azk_tel($phone_wholesale)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-black text-lg px-6 py-4 rounded-xl shadow flex items-center justify-center gap-2 shrink-0 transition-colors">
        <i data-lucide="phone" class="w-5 h-5"></i><span><?php echo esc_html($phone_wholesale); ?></span>
      </a>
    </div>
  </div>

</section>
