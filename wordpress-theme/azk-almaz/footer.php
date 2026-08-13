<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '8 775 865 3737');
$phone_general     = azk_setting('phone_general', '8 (7252) 357-444');
$phone_accounting  = azk_setting('phone_accounting', '8 771 061 10 66');
$address_full      = azk_setting('address_full', 'г. Шымкент, район Тұран, проспект Абая, 1А');
$bin               = azk_setting('bin', '011240001881');
$founding_year     = azk_setting('founding_year', '1998');
?>

<footer class="bg-navy-dark text-slate-300 border-t border-slate-800 pt-12 pb-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-slate-800">
      <div class="lg:col-span-5 space-y-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-lg bg-accent text-navy font-black text-xl flex items-center justify-center shadow"><i data-lucide="fuel" class="w-6 h-6 stroke-[2.5]"></i></div>
          <div><span class="text-2xl font-black text-white tracking-tight uppercase">АЗК АЛМАЗ</span><p class="text-[10px] text-slate-400 font-bold uppercase">ТОО «АЗК Алмаз» • с <?php echo esc_html($founding_year); ?> года</p></div>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed">Надёжные оптовые и розничные поставки высококачественного бензина (АИ-92, АИ-95) и дизельного топлива Евро-5 в Шымкенте и Туркестанской области.</p>
        <div class="text-xs text-slate-400"><div class="flex items-center space-x-2 text-white font-semibold"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-accent"></i><span><?php echo esc_html($address_full); ?></span></div></div>
      </div>
      <div class="lg:col-span-3 space-y-3">
        <div class="text-xs font-bold text-white uppercase tracking-wider">Навигация</div>
        <ul class="space-y-2 text-xs">
          <li><a href="#about" class="hover:text-accent transition-colors">О компании</a></li>
          <li><a href="#services" class="hover:text-accent transition-colors">Услуги</a></li>
          <li><a href="#products" class="hover:text-accent transition-colors">Продукция (Евро-5)</a></li>
          <li><a href="#azs" class="hover:text-accent transition-colors">Сеть АЗС</a></li>
          <li><a href="#faq" class="hover:text-accent transition-colors">Частые вопросы</a></li>
          <li><a href="#contacts" class="hover:text-accent transition-colors">Контакты</a></li>
        </ul>
      </div>
      <div class="lg:col-span-4 space-y-3">
        <div class="text-xs font-bold text-white uppercase tracking-wider">Телефоны отделов</div>
        <div class="space-y-2 text-xs">
          <div><span class="text-slate-400 block text-[11px]">Офис:</span><a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_general); ?></a></div>
          <div><span class="text-slate-400 block text-[11px]">По вопросам сотрудничества:</span><a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_cooperation); ?></a></div>
          <div><span class="text-slate-400 block text-[11px]">Бухгалтерия:</span><a href="tel:<?php echo esc_attr(azk_tel($phone_accounting)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_accounting); ?></a></div>
        </div>
      </div>
    </div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400">
      <div class="flex items-center space-x-4"><span class="font-semibold">© <?php echo esc_html($founding_year); ?> - <?php echo esc_html(date('Y')); ?> ТОО «АЗК Алмаз». Все права защищены.</span><span class="hidden sm:inline">|</span><span class="hidden sm:inline text-slate-500">БИН <?php echo esc_html($bin); ?></span></div>
      <div class="flex items-center space-x-6">
        <button data-policy="privacy" class="policy-btn hover:text-white transition-colors">Политика конфиденциальности</button>
        <button data-policy="terms" class="policy-btn hover:text-white transition-colors">Условия использования</button>
      </div>
    </div>
  </div>
</footer>

<?php get_template_part('template-parts/policy-modal'); ?>

<?php wp_footer(); ?>
</body>
</html>
