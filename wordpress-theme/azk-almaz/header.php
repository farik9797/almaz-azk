<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="ALMAZ — сеть АЗС и оптовые поставки нефтепродуктов в Шымкенте и Туркестанской области: бензин АИ-92, АИ-95 и дизельное топливо оптом и в розницу, с доставкой. Топливные карты, талоны и карта лояльности на АЗС ALMAZ рядом с вами.">

<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(AZK_THEME_URI . '/assets/images/logo/favicon-32.png'); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(AZK_THEME_URI . '/assets/images/logo/favicon-180.png'); ?>">

<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
        colors: {
          navy: { DEFAULT: '#0F2A47', dark: '#0A1E36', light: '#13315C' },
          accent: { DEFAULT: '#F5B301', dark: '#e0a300' },
          leaf: '#2E9E5B',
          ink: '#2B2F36',
        },
      },
    },
  };
</script>
<script src="https://unpkg.com/lucide@latest"></script>

<?php wp_head(); ?>
</head>
<body <?php body_class('antialiased'); ?>>
<?php wp_body_open(); ?>

<?php
$phone_cooperation = azk_setting('phone_cooperation', '8 775 865 3737');
$phone_wholesale   = azk_setting('phone_wholesale', '8 707 729 4051');
$phone_general     = azk_setting('phone_general', '8 (7252) 357-444');
$address_short     = azk_setting('address_short', 'г. Шымкент, проспект Абая, 1А');
$hours_office      = azk_setting('hours_office', 'Пн - Пт: 08:00 - 17:00, Сб: 08:00 - 13:00');
?>

<header class="sticky top-0 z-40 w-full">
  <div class="bg-[#0A1E36] text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden lg:block">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex items-center space-x-6">
        <div class="flex items-center space-x-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-accent"></i><span><?php echo esc_html($address_short); ?></span></div>
        <div class="flex items-center space-x-2"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-leaf"></i><span>Официальные поставки Евро-4 с 1998 года</span></div>
      </div>
      <div class="flex items-center space-x-6">
        <span class="text-slate-400">Офис и нефтебаза: <?php echo esc_html($hours_office); ?> | АЗС 24/7</span>
        <a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="flex items-center space-x-1.5 text-accent font-semibold hover:underline">
          <i data-lucide="phone" class="w-3.5 h-3.5"></i><span><?php echo esc_html($phone_general); ?></span>
        </a>
        <a href="tel:<?php echo esc_attr(azk_tel($phone_cooperation)); ?>" class="flex items-center space-x-1.5 text-accent font-semibold hover:underline">
          <i data-lucide="phone" class="w-3.5 h-3.5"></i><span><?php echo esc_html($phone_cooperation); ?></span>
        </a>
      </div>
    </div>
  </div>

  <div class="w-full bg-white py-4 border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center space-x-3 shrink-0">
        <img src="<?php echo esc_url(AZK_THEME_URI . '/assets/images/logo/logo-horizontal.png'); ?>" alt="ALMAZ" class="h-8 sm:h-9 w-auto max-w-none">
      </a>

      <nav class="hidden lg:flex items-center space-x-1 lg:space-x-2 text-sm font-semibold text-navy">
        <a href="#about" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">О компании</a>
        <a href="#services" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">Услуги</a>
        <a href="#products" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">Продукция</a>
        <a href="#azs" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">Сеть АЗС</a>
        <a href="#faq" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">Вопросы</a>
        <a href="#contacts" class="px-3 py-2 rounded-md hover:text-accent-dark hover:bg-slate-100 transition-colors">Контакты</a>
      </nav>

      <div class="hidden sm:flex items-center space-x-3">
        <a href="tel:<?php echo esc_attr(azk_tel($phone_wholesale)); ?>" class="text-xs font-bold text-navy hover:text-white hover:bg-navy px-3 py-2 rounded border border-slate-300 hover:border-navy transition-colors">Оптовые цены</a>
        <a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="bg-accent hover:bg-accent-dark text-navy font-bold text-sm px-4 py-2.5 rounded-md shadow-md transition-all flex items-center space-x-2">
          <i data-lucide="phone" class="w-4 h-4"></i><span>Связаться</span>
        </a>
      </div>

      <button id="menu-btn" class="lg:hidden text-navy p-2 rounded-md hover:bg-slate-100" aria-label="Открыть меню">
        <i data-lucide="menu" class="w-6 h-6"></i>
      </button>
    </div>
  </div>

  <nav id="mobile-menu" class="hidden lg:hidden bg-white border-b border-slate-200 shadow-md text-navy px-4 pt-3 pb-6 space-y-3">
    <div class="text-xs text-slate-500 pb-2 border-b border-slate-200 flex justify-between items-center">
      <span><?php echo esc_html($address_short); ?></span><span class="text-accent-dark font-semibold">АЗС 24/7</span>
    </div>
    <div class="flex flex-col space-y-1 pt-1">
      <a href="#about" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">О компании <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
      <a href="#services" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">Услуги <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
      <a href="#products" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">Продукция <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
      <a href="#azs" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">Сеть АЗС <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
      <a href="#faq" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">Вопросы <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
      <a href="#contacts" class="mobile-link flex items-center justify-between px-3 py-2.5 rounded-md text-base font-medium hover:bg-slate-100 hover:text-accent-dark">Контакты <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i></a>
    </div>
    <div class="pt-3 border-t border-slate-200 space-y-2">
      <a href="tel:<?php echo esc_attr(azk_tel($phone_wholesale)); ?>" class="flex items-center justify-between p-3 rounded bg-navy text-sm font-semibold text-white">
        <span class="flex items-center space-x-2"><i data-lucide="phone" class="w-4 h-4 text-accent"></i><span>Оптовая реализация: <?php echo esc_html($phone_wholesale); ?></span></span>
        <span class="text-xs text-accent">Позвонить</span>
      </a>
      <a href="tel:<?php echo esc_attr(azk_tel($phone_general)); ?>" class="mobile-link w-full block bg-accent text-navy font-bold py-3 rounded-md text-center text-sm shadow-md">Позвонить нам</a>
    </div>
  </nav>
</header>
