<?php if (!defined('ABSPATH')) exit; ?>
<?php
$title = azk_field('about_title', 'ТОО «АЗК Алмаз» — устойчивое развитие и глубокий опыт с 1998 года');
$text1 = azk_field('about_text1', 'Более 25 лет ТОО «АЗК Алмаз» является ключевым участником рынка нефтепродуктов Южного Казахстана. Мы специализируемся на поставках качественного топлива: АИ-92, АИ-95, ДТ — для промышленных, сельскохозяйственных, транспортных предприятий и розничных автовладельцев.');
$text2 = azk_field('about_text2', 'Наличие собственной современной нефтебазы в г. Шымкент, химической лаборатории и автопарка спецтранспорта позволяет гарантировать непрерывность поставок, точный учёт объёмов и строгое соблюдение всех технических стандартов.');

$principles = [];
if (function_exists('have_rows') && have_rows('principles')) {
    while (have_rows('principles')) { the_row();
        $principles[] = ['title' => get_sub_field('title'), 'text' => get_sub_field('text')];
    }
}
if (!$principles) $principles = azk_default('principles');

$gallery_tabs = [];
if (function_exists('have_rows') && have_rows('gallery_tabs')) {
    while (have_rows('gallery_tabs')) { the_row();
        $gallery_tabs[] = [
            'icon' => get_sub_field('icon'), 'tab_label' => get_sub_field('tab_label'),
            'image' => get_sub_field('image'), 'badge' => get_sub_field('badge'),
            'title' => get_sub_field('title'), 'subtitle' => get_sub_field('subtitle'),
        ];
    }
}
if (!$gallery_tabs) {
    $gallery_tabs = array_map(function ($row) {
        $row['image'] = AZK_THEME_URI . '/assets/images/' . $row['image'];
        return $row;
    }, azk_default('gallery_tabs'));
}

$stats = [];
if (function_exists('have_rows') && have_rows('stats')) {
    while (have_rows('stats')) { the_row();
        $stats[] = ['value' => get_sub_field('value'), 'label' => get_sub_field('label')];
    }
}
if (!$stats) $stats = azk_default('stats');
?>
<section id="about" class="py-16 bg-[#F8FAFC]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
      <div class="space-y-6">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded bg-navy/10 text-navy text-xs font-extrabold uppercase tracking-wider">
          <i data-lucide="building-2" class="w-3.5 h-3.5"></i><span>О компании</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-navy tracking-tight leading-tight"><?php echo esc_html($title); ?></h2>
        <p class="text-slate-700 text-base leading-relaxed"><?php echo esc_html($text1); ?></p>
        <p class="text-slate-600 text-sm leading-relaxed"><?php echo esc_html($text2); ?></p>

        <div class="pt-2">
          <h3 class="text-sm font-bold text-navy uppercase tracking-wider mb-3">Фундаментальные принципы компании:</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <?php foreach ($principles as $p): ?>
              <div class="flex items-start space-x-2.5 p-3 rounded-lg bg-white border border-slate-200 shadow-sm hover:border-navy transition-colors">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-leaf shrink-0 mt-0.5"></i>
                <div><span class="font-bold text-navy block"><?php echo esc_html($p['title']); ?></span><span class="text-xs text-slate-500"><?php echo esc_html($p['text']); ?></span></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="space-y-3">
        <div class="flex items-center justify-between bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm text-xs font-bold gap-1 overflow-x-auto" id="gallery-tabs">
          <?php foreach ($gallery_tabs as $i => $t): ?>
            <button
              data-gallery="<?php echo esc_attr($i); ?>"
              data-image="<?php echo esc_url($t['image']); ?>"
              data-badge="<?php echo esc_attr($t['badge']); ?>"
              data-title="<?php echo esc_attr($t['title']); ?>"
              data-subtitle="<?php echo esc_attr($t['subtitle']); ?>"
              class="tab-btn <?php echo $i === 0 ? 'active' : 'text-slate-600 hover:text-navy hover:bg-slate-100'; ?> flex items-center space-x-1.5 px-3 py-2 rounded-lg whitespace-nowrap"
            ><i data-lucide="<?php echo esc_attr($t['icon']); ?>" class="w-4 h-4"></i><span><?php echo esc_html($t['tab_label']); ?></span></button>
          <?php endforeach; ?>
        </div>

        <?php $first = $gallery_tabs[0]; ?>
        <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-navy h-80 sm:h-96">
          <img id="gallery-img" src="<?php echo esc_url($first['image']); ?>" alt="<?php echo esc_attr($first['title']); ?>" class="w-full h-full object-cover object-center transition-opacity duration-300">
          <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-[#0A1E36] via-[#0A1E36]/85 to-transparent p-6 text-white">
            <div class="flex items-center space-x-2 text-accent text-xs font-bold uppercase tracking-wider mb-1"><i data-lucide="award" class="w-4 h-4"></i><span id="gallery-badge"><?php echo esc_html($first['badge']); ?></span></div>
            <h4 id="gallery-title" class="text-base font-bold text-white"><?php echo esc_html($first['title']); ?></h4>
            <p id="gallery-subtitle" class="text-xs text-slate-300 mt-1"><?php echo esc_html($first['subtitle']); ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-navy rounded-2xl p-8 border border-slate-800 shadow-xl text-white">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
        <?php foreach ($stats as $i => $s): ?>
          <div class="pt-4 md:pt-0">
            <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-accent tracking-tight"><?php echo esc_html($s['value']); ?></div>
            <div class="mt-2 text-xs sm:text-sm font-semibold text-slate-300 uppercase tracking-wide"><?php echo esc_html($s['label']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
