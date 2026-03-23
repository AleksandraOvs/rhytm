<?php

/**
 * TYPOGRAPHY MODULE VIA FOLDER SCAN
 * Скрипт для плагина: выбираем шрифты из папки assets/fonts
 */

if (!defined('ABSPATH')) exit;

// ==============================
// 1. Добавляем страницу в админку
// ==============================
add_action('admin_menu', function () {

    add_submenu_page(
        'theme-resources',        // родительский slug (ВАЖНО!)
        'Типографика',            // title страницы
        'Типографика',            // название в меню
        'manage_options',
        'theme-resources-fonts',  // slug подстраницы
        'render_plugin_fonts_page'
    );
});

// ==============================
// 2. Сканируем папку fonts
// ==============================
function plugin_scan_fonts()
{
    $folder = THEME_RESOURCES_PATH . 'assets/fonts/';

    error_log('=== FONT SCAN START ===');
    error_log('Scanning folder: ' . $folder);

    if (!file_exists($folder)) {
        error_log('ERROR: Folder does NOT exist!');
        return [];
    }

    if (!is_dir($folder)) {
        error_log('ERROR: Path exists but is NOT a directory!');
        return [];
    }

    $files = glob($folder . '*.{ttf,otf,woff,woff2}', GLOB_BRACE);

    if (!$files) {
        error_log('No font files found.');
    } else {
        error_log('Found files:');
        foreach ($files as $file) {
            error_log(' - ' . $file);
        }
    }

    $fonts = [];

    if ($files) {
        foreach ($files as $file) {
            $filename = basename($file);
            $fonts[$filename] = THEME_RESOURCES_URL . 'assets/fonts/' . $filename;
        }
    }

    error_log('=== FONT SCAN END ===');

    return $fonts;
}

// ==============================
// 3. Рендер страницы админки
// ==============================
function render_plugin_fonts_page()
{
    $fonts = plugin_scan_fonts();
    $options = get_option('plugin_fonts_selection', []);

    $sections = [
        'heading' => 'Заголовки H1–H3',
        'body' => 'Основной текст',
        'accent' => 'Акцентный текст',
    ];
?>
    <div class="wrap">
        <h1>Типографика темы</h1>
        <form method="post" action="options.php">
            <?php settings_fields('plugin_fonts_group'); ?>
            <?php do_settings_sections('plugin_fonts_group'); ?>

            <?php foreach ($sections as $key => $label): ?>
                <h2><?php echo esc_html($label); ?></h2>
                <select name="plugin_fonts_selection[<?php echo $key; ?>]">
                    <option value="">— не выбран —</option>
                    <?php foreach ($fonts as $file => $url): ?>
                        <option value="<?php echo esc_attr($file); ?>" <?php selected($options[$key] ?? '', $file); ?>>
                            <?php echo esc_html($file); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endforeach; ?>

            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// ==============================
// 4. Сохраняем настройки
// ==============================
add_action('admin_init', function () {
    register_setting('plugin_fonts_group', 'plugin_fonts_selection');
});

// ==============================
// 5. Подключаем выбранные шрифты на фронтенде
// ==============================
add_action('wp_head', function () {
    $options = get_option('plugin_fonts_selection', []);
    if (!$options) return;

    $fonts = plugin_scan_fonts();
    $css = '';

    foreach ($options as $key => $file) {
        if (!isset($fonts[$file])) continue;

        $font_family = pathinfo($file, PATHINFO_FILENAME);

        $css .= "@font-face{
            font-family:'{$font_family}';
            src:url('{$fonts[$file]}') format('" . pathinfo($file, PATHINFO_EXTENSION) . "');
            font-weight:normal;
            font-style:normal;
            font-display:swap;
        }";

        // Применяем к нужной секции
        switch ($key) {
            case 'heading':
                $css .= "h1,h2{font-family:'{$font_family}',sans-serif;}";
                break;
            case 'body':
                $css .= "body{font-family:'{$font_family}',sans-serif;}";
                break;
            case 'accent':
                $css .= ".accent, .button, strong{font-family:'{$font_family}',sans-serif;}";
                break;
        }
    }

    if ($css) {
        echo "<style>{$css}</style>";
    }
});
