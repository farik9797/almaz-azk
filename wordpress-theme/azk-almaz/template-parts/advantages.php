<?php if (!defined('ABSPATH')) exit; ?>
<?php
$title    = azk_field('advantages_title', 'Почему выбирают ТОО «АЗК Алмаз»');
$subtitle = azk_field('advantages_subtitle', 'За 25+ лет работы на рынке нефтепродуктов мы выстроили безупречную систему поставок, контроля качества и сервиса.');

$rows = [];
if (function_exists('have_rows') && have_rows('advantages')) {
    while (have_rows('advantages')) {
        the_row();
        $rows[] = [
            'icon' => get_sub_field('icon'),
            'number' => get_sub_field('number'),
            'title' => get_sub_field('title'),
            'text' => get_sub_field('text'),
        ];
    }
}
if (!$rows) $rows = azk_default('advantages');
?>
<section class="py-16 bg-white border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-12">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Наши преимущества</div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
      <p class="mt-3 text-slate-600 text-base"><?php echo esc_html($subtitle); ?></p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($rows as $row): ?>
        <div class="bg-[#F8FAFC] p-6 rounded-xl border border-slate-200/80 hover:border-accent/60 hover:shadow-md transition-all">
          <div class="w-14 h-14 rounded-lg bg-white border border-slate-200 flex items-center justify-center mb-5 shadow-sm"><i data-lucide="<?php echo esc_attr($row['icon']); ?>" class="w-8 h-8 text-accent"></i></div>
          <div class="text-xs font-bold text-slate-400 mb-1"><?php echo esc_html($row['number']); ?></div>
          <h3 class="text-xl font-bold text-navy mb-3"><?php echo esc_html($row['title']); ?></h3>
          <p class="text-sm text-slate-600 leading-relaxed"><?php echo esc_html($row['text']); ?></p>
          <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center text-xs font-semibold text-navy"><span class="w-2 h-2 rounded-full bg-accent mr-2"></span>Гарантия АЗК Алмаз</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
