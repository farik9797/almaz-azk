<?php if (!defined('ABSPATH')) exit; ?>
<?php
$title    = azk_field('timeline_title', '5 шагов до успешной отгрузки');
$subtitle = azk_field('timeline_subtitle', 'Прозрачный и отлаженный процесс работы от первого обращения до полного документооборота.');

$steps = [];
if (function_exists('have_rows') && have_rows('timeline')) {
    while (have_rows('timeline')) { the_row();
        $steps[] = ['number' => get_sub_field('number'), 'title' => get_sub_field('title'), 'subtitle' => get_sub_field('subtitle'), 'text' => get_sub_field('text')];
    }
}
if (!$steps) $steps = azk_default('timeline');
$total = count($steps);
?>
<section class="py-16 bg-white border-t border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-block px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider mb-2">Схема сотрудничества</div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy tracking-tight"><?php echo esc_html($title); ?></h2>
      <p class="mt-3 text-slate-600 text-base"><?php echo esc_html($subtitle); ?></p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
      <?php foreach ($steps as $i => $s): ?>
        <div class="bg-[#F8FAFC] rounded-xl p-5 border border-slate-200/90 shadow-sm hover:border-navy transition-all">
          <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-navy text-accent font-black text-lg flex items-center justify-center shadow-sm"><?php echo esc_html($s['number']); ?></div>
            <?php if ($i < $total - 1): ?><i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 hidden lg:block"></i><?php endif; ?>
          </div>
          <h3 class="text-base font-bold text-navy mb-1"><?php echo esc_html($s['title']); ?></h3>
          <div class="text-xs font-semibold text-navy-light mb-2"><?php echo esc_html($s['subtitle']); ?></div>
          <p class="text-xs text-slate-500 leading-relaxed"><?php echo esc_html($s['text']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
