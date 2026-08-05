<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_general = azk_setting('phone_general', '8 (7252) 357-444');
$title = azk_field('faq_title', 'Частые вопросы');

$items = [];
if (function_exists('have_rows') && have_rows('faq_items')) {
    while (have_rows('faq_items')) { the_row();
        $items[] = ['question' => get_sub_field('question'), 'answer' => get_sub_field('answer')];
    }
}
if (!$items) $items = azk_default('faq');
?>
<section id="faq" class="py-16 bg-white border-t border-slate-200/80">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="text-center space-y-3">
      <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-navy/10 text-navy text-xs font-black uppercase tracking-wider"><i data-lucide="help-circle" class="w-4 h-4 text-accent"></i><span>Вопросы и ответы</span></div>
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-navy"><?php echo esc_html($title); ?></h2>
      <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto">Отвечаем на вопросы клиентов</p>
    </div>

    <div class="space-y-4">
      <?php foreach ($items as $i => $item): ?>
        <details class="rounded-2xl border border-slate-200 bg-white hover:border-slate-300 shadow-sm overflow-hidden"<?php echo $i === 0 ? ' open' : ''; ?>>
          <summary class="p-5 sm:p-6 flex items-center justify-between gap-4"><span class="font-extrabold text-navy text-base sm:text-lg"><?php echo esc_html($item['question']); ?></span><i data-lucide="chevron-down" class="w-5 h-5 shrink-0 transition-transform"></i></summary>
          <div class="px-5 pb-5 sm:px-6 sm:pb-6 pt-0 text-slate-700 text-sm sm:text-base leading-relaxed border-t border-slate-200/60 mt-1"><p class="pt-3"><?php echo esc_html($item['answer']); ?></p></div>
        </details>
      <?php endforeach; ?>
    </div>

    <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="space-y-1 text-center sm:text-left"><h4 class="font-extrabold text-navy text-base sm:text-lg">Остались другие вопросы?</h4><p class="text-xs sm:text-sm text-slate-600">Свяжитесь с отделом продаж ТОО «АЗК Алмаз» для консультации.</p></div>
      <a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="inline-flex items-center space-x-2 bg-navy hover:bg-navy-light text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl"><i data-lucide="phone-call" class="w-4 h-4 text-accent"></i><span><?php echo esc_html($phone_general); ?></span></a>
    </div>
  </div>
</section>
