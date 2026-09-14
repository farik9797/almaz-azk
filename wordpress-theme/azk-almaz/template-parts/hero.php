<?php if (!defined('ABSPATH')) exit; ?>
<?php
$phone_cooperation = azk_setting('phone_cooperation', '8 775 865 3737');
$phone_general     = azk_setting('phone_general', '8 (7252) 357-444');
$address_full      = azk_setting('address_full', 'г. Шымкент, район Тұран, проспект Абая, 1А');

$badge         = azk_field('hero_badge', 'ТОО «АЗК Алмаз» • 25+ лет стабильной работы');
$title         = azk_field('hero_title', 'Надёжные поставки нефтепродуктов');
$title_accent  = azk_field('hero_title_accent', 'с 1998 года');
$subtitle      = azk_field('hero_subtitle', 'Оптовая и розничная реализация высококачественного бензина (АИ-92, АИ-95) и дизельного топлива стандарта Евро-5. Собственная нефтебаза в г. Шымкент, автопарк бензовозов и сеть современных АЗС.');
$hero_image    = azk_image('hero_image', 'hero-depot.jpg');
?>
<section id="top" class="relative bg-navy-dark text-white pt-8 pb-16 md:pt-16 md:pb-24 overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="<?php echo esc_url($hero_image); ?>" alt="АЗС ALMAZ" class="w-full h-full object-cover object-center opacity-30">
    <div class="absolute inset-0 bg-gradient-to-r from-[#0A1E36] via-[#0F2A47]/95 to-[#13315C]/85"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
      <div class="lg:col-span-8 space-y-6">
        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/15 text-xs sm:text-sm font-semibold text-accent">
          <span class="w-2 h-2 rounded-full bg-leaf"></span>
          <span><?php echo esc_html($badge); ?></span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12]">
          <?php echo esc_html($title); ?> <span class="text-accent whitespace-nowrap"><?php echo esc_html($title_accent); ?></span>
        </h1>

        <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-3xl leading-relaxed"><?php echo esc_html($subtitle); ?></p>

        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
          <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-extrabold text-base px-6 py-4 rounded-md shadow-lg transition-colors flex items-center justify-center space-x-2 border border-accent">
            <i data-lucide="truck" class="w-5 h-5 stroke-[2.5]"></i><span>Оптовые поставки</span><i data-lucide="arrow-right" class="w-5 h-5"></i>
          </a>
          <a href="#azs" class="bg-transparent hover:bg-white/10 text-white border-2 border-slate-400 hover:border-white font-bold text-base px-6 py-3.5 rounded-md transition-colors flex items-center justify-center space-x-2">
            <i data-lucide="fuel" class="w-5 h-5 text-accent"></i><span>АЗС и розница</span>
          </a>
        </div>

        <div class="pt-6 border-t border-slate-800/80 grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs sm:text-sm text-slate-300">
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded bg-white/5 border border-white/10 flex items-center justify-center text-leaf shrink-0"><i data-lucide="shield-check" class="w-4 h-4"></i></div>
            <div><div class="font-bold text-white">Стандарт Евро-5</div><div class="text-slate-400 text-[11px]">100% соответствие ГОСТ</div></div>
          </div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0"><i data-lucide="truck" class="w-4 h-4"></i></div>
            <div><div class="font-bold text-white">Собственные бензовозы</div><div class="text-slate-400 text-[11px]">Доставка от 2,000 литров</div></div>
          </div>
          <div class="col-span-2 sm:col-span-1 flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0"><i data-lucide="award" class="w-4 h-4"></i></div>
            <div><div class="font-bold text-white">Собственная нефтебаза</div><div class="text-slate-400 text-[11px]">г. Шымкент, р-н Тұран</div></div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-4 bg-navy-light/90 backdrop-blur-md rounded-xl p-6 border border-slate-700/80 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-slate-700/80 pb-4">
          <div><h3 class="text-lg font-bold uppercase tracking-wider">Прямой контакт</h3><p class="text-xs text-slate-300">Отдел продаж нефтепродуктов</p></div>
          <div class="px-2.5 py-1 rounded bg-leaf/20 text-leaf text-xs font-bold border border-leaf/30">На связи</div>
        </div>
        <div class="space-y-3 text-sm">
          <div class="p-3 rounded-lg bg-navy border border-slate-800 hover:border-accent/50 transition-colors">
            <div class="text-xs text-slate-400 mb-0.5">Опт, розница, поставки на АЗС:</div>
            <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="text-lg font-black text-accent hover:underline"><?php echo esc_html($phone_cooperation); ?></a>
          </div>
          <div class="p-3 rounded-lg bg-navy border border-slate-800 hover:border-slate-600 transition-colors">
            <div class="text-xs text-slate-400 mb-0.5">Офис:</div>
            <a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="text-lg font-black text-white hover:text-accent"><?php echo esc_html($phone_general); ?></a>
          </div>
          <div class="p-3 rounded-lg bg-navy border border-slate-800 flex items-start space-x-2.5 text-xs text-slate-300">
            <i data-lucide="map-pin" class="w-4 h-4 text-accent mt-0.5 shrink-0"></i>
            <div><span class="font-semibold text-white block">Офис ТОО «АЗК Алмаз»:</span><span><?php echo esc_html($address_full); ?></span></div>
          </div>
        </div>
        <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="w-full text-xs text-slate-300 hover:text-white underline text-center block pt-1 font-medium">Позвонить и запросить паспорта качества ГОСТ</a>
      </div>
    </div>
  </div>
</section>
