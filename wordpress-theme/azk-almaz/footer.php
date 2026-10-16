<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '+7 (775) 865-37-37');
$phone_wholesale   = azk_setting('phone_wholesale', '+7 (707) 729-40-51');
$phone_general     = azk_setting('phone_general', '+7 (7252) 357-444');
$phone_accounting  = azk_setting('phone_accounting', '+7 (771) 061-10-66');
$address_full      = azk_setting('address_full', 'г. Шымкент, район Тұран, проспект Абая, 1А');
$bin               = azk_setting('bin', '011240001881');
$founding_year     = azk_setting('founding_year', '1998');
?>

<footer class="bg-navy-dark text-slate-300 border-t border-slate-800 pt-12 pb-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-slate-800">
      <div class="lg:col-span-5 space-y-4">
        <div class="flex items-center space-x-3">
          <img src="<?php echo esc_url(AZK_THEME_URI . '/assets/images/logo/logo-horizontal.png'); ?>" alt="ALMAZ" class="h-9 w-auto">
          <p class="text-[10px] text-slate-400 font-bold uppercase"><?php echo esc_html(azk_translate('ТОО «АЗК Алмаз» • Светлые нефтепродукты • с ' . $founding_year . ' года')); ?></p>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed"><?php azk_e('Надёжные оптовые и розничные поставки высококачественного бензина (АИ-92, АИ-95) и дизельного топлива Евро-4 в Шымкенте и Туркестанской области.'); ?></p>
        <div class="text-xs text-slate-400"><div class="flex items-center space-x-2 text-white font-semibold"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-accent"></i><span><?php echo esc_html($address_full); ?></span></div></div>
      </div>
      <div class="lg:col-span-3 space-y-3">
        <div class="text-xs font-bold text-white uppercase tracking-wider"><?php azk_e('Навигация'); ?></div>
        <ul class="space-y-2 text-xs">
          <li><a href="#about" class="hover:text-accent transition-colors"><?php azk_e('О компании', 'footer'); ?></a></li>
          <li><a href="#services" class="hover:text-accent transition-colors"><?php azk_e('Услуги', 'footer'); ?></a></li>
          <li><a href="#products" class="hover:text-accent transition-colors"><?php azk_e('Продукция (Евро-4)', 'footer'); ?></a></li>
          <li><a href="#azs" class="hover:text-accent transition-colors"><?php azk_e('Сеть АЗС', 'footer'); ?></a></li>
          <li><a href="#faq" class="hover:text-accent transition-colors"><?php azk_e('Частые вопросы', 'footer'); ?></a></li>
          <li><a href="#contacts" class="hover:text-accent transition-colors"><?php azk_e('Контакты', 'footer'); ?></a></li>
        </ul>
      </div>
      <div class="lg:col-span-4 space-y-3">
        <div class="text-xs font-bold text-white uppercase tracking-wider"><?php azk_e('Телефоны отделов'); ?></div>
        <div class="space-y-2 text-xs">
          <div><span class="text-slate-400 block text-[11px]"><?php azk_e('По вопросам оптовой реализации:'); ?></span><a href="tel:<?php echo esc_attr(azk_tel($phone_wholesale)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_wholesale); ?></a></div>
          <div><span class="text-slate-400 block text-[11px]"><?php azk_e('По вопросам розничной реализации через сеть АЗС:'); ?></span><a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_cooperation); ?></a></div>
          <div><span class="text-slate-400 block text-[11px]"><?php azk_e('По общим вопросам:'); ?></span><a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_general); ?></a></div>
          <div><span class="text-slate-400 block text-[11px]"><?php azk_e('Бухгалтерия:'); ?></span><a href="tel:<?php echo esc_attr(azk_tel($phone_accounting)); ?>" class="font-bold text-white hover:text-accent"><?php echo esc_html($phone_accounting); ?></a></div>
        </div>
      </div>
    </div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400">
      <div class="flex items-center space-x-4"><?php /* Переводим целыми строками: по частям в китайском появляются лишние пробелы,
         а в английском тире набирается иначе. */ ?>
        <span class="font-semibold"><?php echo esc_html(azk_translate('© ' . $founding_year . ' - ' . date('Y') . ' ТОО «АЗК Алмаз». Все права защищены.')); ?></span><span class="hidden sm:inline">|</span><span class="hidden sm:inline text-slate-500"><?php echo esc_html(azk_translate('БИН ' . $bin)); ?></span></div>
      <div class="flex items-center space-x-6">
        <button data-policy="privacy" class="policy-btn hover:text-white transition-colors"><?php azk_e('Политика конфиденциальности'); ?></button>
        <button data-policy="terms" class="policy-btn hover:text-white transition-colors"><?php azk_e('Условия использования'); ?></button>
      </div>
    </div>
  </div>
</footer>

<?php get_template_part('template-parts/policy-modal'); ?>

<?php wp_footer(); ?>
</body>
</html>
