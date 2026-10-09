<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$title    = azk_field('services_title', 'Полный спектр услуг на рынке нефтепродуктов');
$subtitle = azk_field('services_subtitle', 'Обеспечиваем надёжное решение задач любой сложности — от разовых оптовых отгрузок до долгосрочного контрактного обслуживания и хранения.');

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

</section>
