<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$title = azk_field('promo_title', 'Выгодные акции и бонусные программы на АЗС');

$cards = [];
if (function_exists('have_rows') && have_rows('promo_cards')) {
    while (have_rows('promo_cards')) { the_row();
        $cards[] = ['icon' => get_sub_field('icon'), 'title' => get_sub_field('title'), 'text' => get_sub_field('text'), 'tagline' => get_sub_field('tagline')];
    }
}
if (!$cards) $cards = azk_default('promo_cards');
?>
<section class="py-12 bg-gradient-to-r from-navy via-navy-light to-navy-dark text-white border-t border-b border-slate-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-700/80 pb-6">
      <div class="space-y-1">
        <div class="inline-flex items-center space-x-2 text-accent text-xs font-bold uppercase tracking-wider"><i data-lucide="gift" class="w-4 h-4"></i><span>Программы лояльности ALMAZ</span></div>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight"><?php echo esc_html($title); ?></h2>
      </div>
      <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-extrabold text-sm px-5 py-3 rounded-lg shadow flex items-center justify-center space-x-2 self-start md:self-auto"><span>Оформить топливную карту</span><i data-lucide="arrow-right" class="w-4 h-4"></i></a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($cards as $c): ?>
        <div class="bg-white/5 backdrop-blur-md rounded-xl p-6 border border-white/10 space-y-3 hover:border-accent/60 transition-colors">
          <div class="w-10 h-10 rounded-lg bg-accent/20 text-accent flex items-center justify-center"><i data-lucide="<?php echo esc_attr($c['icon']); ?>" class="w-5 h-5"></i></div>
          <h3 class="text-lg font-bold"><?php echo esc_html($c['title']); ?></h3>
          <p class="text-xs text-slate-300 leading-relaxed"><?php echo esc_html($c['text']); ?></p>
          <div class="pt-2 text-xs font-bold text-accent"><?php echo esc_html($c['tagline']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
