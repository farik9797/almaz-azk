<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$title    = azk_field('azs_title', 'Фирменная сеть АЗС ALMAZ');
$subtitle = azk_field('azs_subtitle', 'Современные автозаправочные комплексы на ключевых магистралях Шымкента и Туркестанской области. Круглосуточный точный налив и высокий сервис.');
$map_image = azk_image('azs_map_image', 'azs-map.jpg');

$stations = [];
if (function_exists('have_rows') && have_rows('stations')) {
    while (have_rows('stations')) { the_row();
        $fuels = [];
        if (have_rows('fuels')) { while (have_rows('fuels')) { the_row(); $fuels[] = azk_sub('fuel'); } }
        $amenities = [];
        if (have_rows('amenities')) { while (have_rows('amenities')) { the_row(); $amenities[] = azk_sub('amenity'); } }
        $stations[] = [
            'slug' => azk_sub('slug'), 'label' => azk_sub_raw('label'), 'name' => azk_sub('name'),
            'region' => azk_sub_raw('region'), 'address' => azk_sub('address'), 'direction' => azk_sub('direction'),
            'phone' => azk_sub('phone'), 'hours' => azk_sub('hours'),
            'marker_top' => azk_sub('marker_top'), 'marker_left' => azk_sub('marker_left'),
            'fuels' => $fuels, 'amenities' => $amenities,
        ];
    }
}
if (!$stations) $stations = azk_default('stations', true);  // сырые: регион нужен для фильтра

$regions = [];
foreach ($stations as $s) { if (!in_array($s['region'], $regions, true)) $regions[] = $s['region']; }
$region_counts = array_count_values(array_column($stations, 'region'));

$first = $stations[0];
$station_js = [];
foreach ($stations as $s) {
    $station_js[$s['slug']] = [
        'name' => azk_translate($s['name']), 'address' => azk_translate($s['address']),
        'direction' => azk_translate($s['direction']), 'amenities' => azk_translate_value($s['amenities']),
        // адрес для карты всегда русский: по переведённому геокодер не находит точку
        'route' => $s['address'],
    ];
}
?>
<section id="azs" class="py-16 bg-[#F8FAFC]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-6">
      <div>
        <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2"><?php azk_e('Розница и обслуживание'); ?></div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
        <p class="mt-2 text-slate-600 text-sm max-w-2xl"><?php echo esc_html($subtitle); ?></p>
      </div>
      <div class="flex items-center space-x-2 bg-white p-1 rounded-lg border border-slate-200 text-xs font-bold" id="azs-filters">
        <button data-filter="all" class="azs-filter-btn px-3 py-1.5 rounded-md bg-navy text-white"><?php echo esc_html(azk_translate('Все АЗС (' . count($stations) . ')')); ?></button>
        <?php foreach ($regions as $r): ?>
          <?php /* Подпись короче значения фильтра: в кнопке «Шымкент», а фильтруем по «г. Шымкент». */ ?>
          <?php $r_label = ['г. Шымкент' => 'Шымкент', 'Туркестанская область' => 'Туркестанская обл.'][$r] ?? $r; ?>
          <button data-filter="<?php echo esc_attr($r); ?>" class="azs-filter-btn px-3 py-1.5 rounded-md text-slate-600 hover:text-navy"><?php echo esc_html(azk_translate($r_label . ' (' . (int) $region_counts[$r] . ')')); ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      <div class="lg:col-span-6 bg-navy rounded-2xl overflow-hidden border border-slate-800 shadow-xl relative min-h-[420px] flex flex-col justify-between">
        <div class="p-4 bg-navy-dark border-b border-slate-800 flex items-center justify-between text-xs text-slate-300">
          <div class="flex items-center space-x-2"><i data-lucide="map-pin" class="w-4 h-4 text-accent"></i><span class="font-bold text-white"><?php azk_e('Шымкент &amp; Туркестанская область'); ?></span></div>
          <span class="text-leaf font-semibold flex items-center space-x-1"><span class="w-2 h-2 rounded-full bg-leaf"></span><span><?php azk_e('Все станции работают 24/7'); ?></span></span>
        </div>
        <div class="relative w-full bg-navy-dark overflow-hidden">
          <?php /* Карта — склейка реальных тайлов OpenStreetMap (tools/make-map.py).
                   Маркеры позиционируются в процентах от размера картинки, поэтому
                   она должна показываться целиком: w-full h-auto, без object-cover.
                   АЗС-2 и АЗС-3 стоят в 150 м друг от друга — на этом масштабе
                   кружки сливаются, поэтому разведены сдвигом в экранных пикселях. */ ?>
          <img src="<?php echo esc_url($map_image); ?>" alt="<?php echo esc_attr(azk_translate('Карта сети АЗС ALMAZ в Шымкенте и Туркестанской области')); ?>" class="w-full h-auto block" width="1200" height="800" loading="lazy">
          <?php $nudge = ['azs-2' => ' margin-left:-22px;', 'azs-3' => ' margin-left:22px;']; ?>
          <?php foreach ($stations as $i => $s): ?>
            <button data-station="<?php echo esc_attr($s['slug']); ?>" class="marker absolute -translate-x-1/2 -translate-y-1/2 flex flex-col items-center z-10" style="top:<?php echo esc_attr($s['marker_top']); ?>%; left:<?php echo esc_attr($s['marker_left']); ?>%;<?php echo $nudge[$s['slug']] ?? ''; ?>">
              <span class="w-11 h-11 rounded-full flex items-center justify-center font-black text-xs shadow-xl border-2 <?php echo $i === 0 ? 'bg-accent text-navy border-white' : 'bg-navy text-white border-accent'; ?>"><?php echo esc_html(azk_translate($s['label'], null, 'marker')); ?></span>
            </button>
          <?php endforeach; ?>
          <div class="absolute bottom-3 left-3 bg-navy/90 backdrop-blur p-2.5 rounded-lg border border-slate-800 text-[11px] text-slate-300">
            <span class="font-bold text-white block mb-0.5"><?php azk_e('Кликните по маркеру АЗС:'); ?></span><span><?php azk_e('чтобы увидеть адрес и проложить маршрут'); ?></span>
          </div>
        </div>

        <div class="relative p-5 bg-navy-light border-t border-slate-800 text-white space-y-3 overflow-hidden" id="station-detail">
          <img src="<?php echo esc_url($map_image); ?>" alt="АЗС ALMAZ" class="absolute inset-0 w-full h-full object-cover object-center opacity-20">
          <div class="absolute inset-0 bg-gradient-to-r from-navy-light via-navy-light/95 to-navy/90"></div>
          <div class="relative z-10 flex justify-between items-start">
            <div>
              <span class="text-[10px] bg-accent text-navy px-2 py-0.5 rounded font-black uppercase"><?php azk_e('Выбрана на карте'); ?></span>
              <h4 id="station-name" class="text-base font-bold text-white mt-1"><?php echo esc_html(azk_translate($first['name'])); ?></h4>
              <p id="station-address" class="text-xs text-slate-300"><?php echo esc_html(azk_translate($first['address'])); ?></p>
              <p id="station-direction" class="text-xs text-accent font-semibold mt-0.5"><?php echo esc_html(azk_translate($first['direction'])); ?></p>
            </div>
            <a id="station-route" href="https://yandex.ru/maps/?text=<?php echo rawurlencode($first['address']); ?>" target="_blank" rel="noopener" class="bg-accent hover:bg-accent-dark text-navy text-xs font-bold px-3 py-2 rounded flex items-center space-x-1 shadow shrink-0">
              <i data-lucide="navigation" class="w-3.5 h-3.5"></i><span><?php azk_e('Маршрут'); ?></span><i data-lucide="external-link" class="w-3 h-3"></i>
            </a>
          </div>
          <div id="station-amenities" class="relative z-10 flex flex-wrap gap-1.5 pt-1 text-xs">
            <?php foreach ($first['amenities'] as $a): ?><span class="bg-navy/90 text-slate-200 px-2 py-1 rounded border border-slate-700"><?php echo esc_html(azk_translate($a)); ?></span><?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="lg:col-span-6 space-y-4" id="station-cards">
        <?php foreach ($stations as $i => $s): ?>
          <div class="station-card <?php echo $i === 0 ? 'active border-navy' : 'border-slate-200 hover:border-slate-400'; ?> cursor-pointer rounded-2xl p-6 transition-all border-2 shadow-sm bg-white" data-station="<?php echo esc_attr($s['slug']); ?>" data-region="<?php echo esc_attr($s['region']); ?>">
            <div class="flex items-start justify-between gap-3 mb-3">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl font-black text-sm flex items-center justify-center shadow-sm <?php echo $i === 0 ? 'bg-navy text-accent' : 'bg-slate-100 text-navy'; ?>"><?php echo esc_html(azk_translate($s['label'], null, 'station_card')); ?></div>
                <div><h3 class="text-lg font-bold text-navy"><?php echo esc_html(azk_translate($s['name'])); ?></h3><span class="text-xs font-bold text-leaf bg-leaf/10 px-2 py-0.5 rounded"><?php echo esc_html(azk_translate($s['region'])); ?></span></div>
              </div>
              <span class="text-xs text-slate-500 font-semibold flex items-center space-x-1"><i data-lucide="clock" class="w-3.5 h-3.5 text-accent"></i><span><?php echo esc_html(azk_translate($s['hours'])); ?></span></span>
            </div>
            <div class="text-xs text-slate-700 mb-4 flex items-start space-x-2"><i data-lucide="map-pin" class="w-4 h-4 text-navy shrink-0 mt-0.5"></i><div><span class="font-bold text-slate-900"><?php echo esc_html(azk_translate($s['address'])); ?></span><p class="text-slate-500 font-medium"><?php echo esc_html(azk_translate($s['direction'])); ?></p></div></div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
              <div class="flex items-center space-x-2"><i data-lucide="fuel" class="w-4 h-4 text-navy"></i><span class="font-bold text-slate-700"><?php azk_e('Топливо:'); ?></span><div class="flex space-x-1"><?php foreach ($s['fuels'] as $f): ?><span class="bg-navy text-accent font-extrabold px-2 py-0.5 rounded"><?php echo esc_html(azk_translate($f)); ?></span><?php endforeach; ?></div></div>
              <a href="tel:<?php echo esc_attr(azk_tel($s['phone'])); ?>" class="text-navy font-bold hover:underline flex items-center space-x-1"><i data-lucide="phone" class="w-3.5 h-3.5"></i><span><?php echo esc_html($s['phone']); ?></span></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script id="azk-stations-data" type="application/json"><?php echo wp_json_encode($station_js); ?></script>
