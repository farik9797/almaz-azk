<?php if (!defined('ABSPATH')) exit; ?>
<?php
$title = azk_field('contacts_title', 'Контактная информация и отделы');
$address_full  = azk_setting('address_full', 'г. Шымкент, район Тұран, проспект Абая, 1А');
$hours_office  = azk_setting('hours_office', 'Пн - Пт: 08:00 - 17:00, Сб: 08:00 - 13:00 (Офис)');
$hours_depot   = azk_setting('hours_depot', 'Круглосуточно, 24/7');
$bin           = azk_setting('bin', '011240001881');
$legal_entity  = azk_setting('legal_entity', 'ТОО «АЗК Алмаз»');

$cards = [];
if (function_exists('have_rows') && have_rows('phone_cards')) {
    while (have_rows('phone_cards')) { the_row();
        $phones = [];
        if (have_rows('phones')) { while (have_rows('phones')) { the_row(); $phones[] = get_sub_field('phone'); } }
        $cards[] = ['icon' => get_sub_field('icon'), 'department' => get_sub_field('department'), 'title' => get_sub_field('title'), 'phones' => $phones];
    }
}
if (!$cards) $cards = azk_default('phone_cards');
?>
<section id="contacts" class="py-16 bg-[#F8FAFC]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Связь с нами</div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
      <p class="mt-3 text-slate-600 text-base">Выберите нужный отдел и позвоните напрямую — ответим на все вопросы.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($cards as $c): ?>
        <div class="bg-white rounded-2xl p-6 border-2 border-slate-200/90 shadow-sm hover:border-navy transition-all flex flex-col justify-between space-y-4">
          <div class="flex-1 flex flex-col justify-between">
            <div class="flex items-center space-x-3 mb-3"><div class="w-10 h-10 rounded-xl bg-navy flex items-center justify-center shrink-0"><i data-lucide="<?php echo esc_attr($c['icon']); ?>" class="w-5 h-5 text-accent"></i></div><div><span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block"><?php echo esc_html($c['department']); ?></span><h3 class="text-base font-bold text-navy leading-snug"><?php echo esc_html($c['title']); ?></h3></div></div>
            <div class="space-y-2 pt-2">
              <?php foreach ($c['phones'] as $p): ?><a href="tel:<?php echo esc_attr(azk_tel($p)); ?>" class="text-xl font-black text-navy hover:text-accent block tracking-tight"><?php echo esc_html($p); ?></a><?php endforeach; ?>
            </div>
          </div>
          <a href="tel:<?php echo esc_attr(azk_tel($c['phones'][0] ?? '')); ?>" class="w-full bg-navy hover:bg-navy-light text-white text-xs font-bold py-2.5 rounded-lg text-center block">Позвонить в отдел</a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="pt-6 border-t border-slate-200">
      <div class="max-w-2xl mx-auto bg-navy text-white rounded-2xl p-8 space-y-6 shadow-xl">
        <div class="flex items-center space-x-3"><div class="w-10 h-10 rounded-lg bg-accent text-navy flex items-center justify-center"><i data-lucide="building" class="w-5 h-5"></i></div><div><span class="text-xs text-slate-300 uppercase tracking-wider block font-medium">Центральный офис</span><h3 class="text-lg font-bold"><?php echo esc_html($legal_entity); ?></h3></div></div>
        <div class="space-y-4 text-sm">
          <div class="flex items-start space-x-3 p-3 rounded-lg bg-navy-light border border-slate-700"><i data-lucide="map-pin" class="w-5 h-5 text-accent shrink-0 mt-0.5"></i><div><span class="text-xs text-slate-400 block font-semibold">Адрес головного офиса:</span><span class="font-bold"><?php echo esc_html($address_full); ?></span></div></div>
          <div class="flex items-start space-x-3 p-3 rounded-lg bg-navy-light border border-slate-700"><i data-lucide="clock" class="w-5 h-5 text-leaf shrink-0 mt-0.5"></i><div><span class="text-xs text-slate-400 block font-semibold">Режим работы офиса и нефтебазы:</span><span class="font-bold"><?php echo esc_html($hours_office); ?></span><p class="text-xs text-slate-300 mt-0.5">АЗС: <?php echo esc_html($hours_depot); ?></p></div></div>
        </div>
        <div class="pt-2 text-xs text-slate-400 border-t border-slate-700/80 flex justify-between"><span>БИН: <?php echo esc_html($bin); ?></span><span>г. Шымкент</span></div>
      </div>
    </div>
  </div>
</section>
