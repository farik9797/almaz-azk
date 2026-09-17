<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$title    = azk_field('services_title', 'Полный спектр услуг на рынке нефтепродуктов');
$subtitle = azk_field('services_subtitle', 'Обеспечиваем надёжное решение задач любой сложности — от разовых оптовых отгрузок до долгосрочного контрактного обслуживания и хранения.');
$fleet_image = azk_image('fleet_image', 'fleet.jpg');

$services = [];
if (function_exists('have_rows') && have_rows('services')) {
    while (have_rows('services')) { the_row();
        $services[] = ['icon' => get_sub_field('icon'), 'title' => get_sub_field('title'), 'description' => get_sub_field('description'), 'details' => get_sub_field('details')];
    }
}
if (!$services) $services = azk_default('services');
?>
<section id="services" class="py-16 bg-[#F8FAFC]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Наши услуги</div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
      <p class="mt-3 text-slate-600 text-base"><?php echo esc_html($subtitle); ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($services as $i => $s): ?>
        <div class="bg-white rounded-xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-navy hover:-translate-y-1 transition-all flex flex-col justify-between">
          <div>
            <div class="flex items-center space-x-3 mb-4"><div class="w-12 h-12 rounded-lg bg-navy flex items-center justify-center shrink-0 shadow-sm"><i data-lucide="<?php echo esc_attr($s['icon']); ?>" class="w-6 h-6 text-accent"></i></div><div><h3 class="text-lg font-bold text-navy"><?php echo esc_html($s['title']); ?></h3></div></div>
            <p class="text-sm font-medium text-slate-800 mb-2"><?php echo esc_html($s['description']); ?></p>
            <p class="text-xs text-slate-500 leading-relaxed mb-4"><?php echo esc_html($s['details']); ?></p>
          </div>
          <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="pt-3 border-t border-slate-100 text-xs font-bold text-navy hover:text-navy-light flex items-center justify-between w-full group">Позвонить по этой услуге<i data-lucide="chevron-right" class="w-4 h-4 text-accent group-hover:translate-x-1 transition-transform"></i></a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="relative rounded-2xl overflow-hidden border border-slate-800 shadow-xl bg-navy text-white p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
      <img src="<?php echo esc_url($fleet_image); ?>" alt="Автопарк бензовозов" class="absolute inset-0 w-full h-full object-cover object-center opacity-25">
      <div class="absolute inset-0 bg-gradient-to-r from-[#0A1E36] via-navy/95 to-navy-light/85"></div>
      <div class="relative z-10 md:col-span-8 space-y-3">
        <div class="inline-flex items-center space-x-2 bg-accent/20 text-accent border border-accent/40 px-3 py-1 rounded-full text-xs font-extrabold"><i data-lucide="shield-check" class="w-4 h-4"></i><span>Собственный автопарк спецтранспорта Евро-4</span></div>
        <h3 class="text-xl sm:text-2xl font-black">Гарантированная транспортировка от 2 000 до 40 000 литров по региону</h3>
        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl">Каждый бензовоз оснащён калиброванными секциями, насосами, счётчиками и спутниковым GPS-трекингом для контроля местоположения и сохранности топлива в пути.</p>
      </div>
      <div class="relative z-10 md:col-span-4 flex flex-col sm:flex-row md:flex-col gap-3 justify-center">
        <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-extrabold text-sm px-5 py-3.5 rounded-xl shadow flex items-center justify-center space-x-2 text-center"><span>Заказать перевозку</span><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
        <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-white/10 hover:bg-white/20 text-white font-bold text-sm px-5 py-3.5 rounded-xl border border-white/20 flex items-center justify-center space-x-2 text-center backdrop-blur"><i data-lucide="phone-call" class="w-4 h-4 text-accent"></i><span><?php echo esc_html($phone_cooperation); ?></span></a>
      </div>
    </div>
  </div>
</section>
