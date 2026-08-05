<?php if (!defined('ABSPATH')) exit; ?>
<?php
$privacy_title = azk_field('privacy_title', 'Политика конфиденциальности ТОО «АЗК Алмаз»');
$privacy_body  = azk_field('privacy_body', azk_policy_default('privacy'));
$terms_title   = azk_field('terms_title', 'Условия использования и публичная оферта');
$terms_body    = azk_field('terms_body', azk_policy_default('terms'));
?>
<div id="policy-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
  <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 relative space-y-4 max-h-[85vh] flex flex-col">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <div class="flex items-center space-x-2 text-navy"><i data-lucide="lock" class="w-5 h-5 text-accent"></i><h3 id="policy-title" class="text-lg font-bold">Политика конфиденциальности</h3></div>
      <button id="policy-close" class="text-slate-400 hover:text-slate-600 p-1"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <div id="policy-body" class="overflow-y-auto text-xs text-slate-600 space-y-3 leading-relaxed pr-2"></div>
    <button id="policy-close-2" class="w-full bg-navy text-white font-bold py-2.5 rounded-lg text-xs">Понятно / Закрыть</button>
  </div>
</div>

<script id="azk-policy-data" type="application/json">
<?php
echo wp_json_encode([
    'privacy' => ['title' => $privacy_title, 'body' => wp_kses_post($privacy_body)],
    'terms'   => ['title' => $terms_title, 'body' => wp_kses_post($terms_body)],
]);
?>
</script>
