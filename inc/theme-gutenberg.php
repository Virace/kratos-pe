<?php
function create_block_bootstrap_4_block_init()
{

    $dep = array('dependencies' => array('wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill'), 'version' => '2473c43c62a6793fb880efa928a3e30d');
    $dir = dirname(__FILE__);
    $index_js = '/inc/assets/guntenberg/index.js';
    wp_register_script(
        'kratos-block',
        ASSET_PATH . $index_js,
        $dep['dependencies'],
        $dep['version']
    );
    wp_set_script_translations('kratos-block', 'bootstrap4');

    $editor_css = '/assets/guntenberg/index.css';
    if (is_admin()) {
        wp_register_style(
            'kratos-block',
            ASSET_PATH . '/inc/' . $editor_css,
            array(),
            filemtime("$dir/$editor_css")
        );
    }
//    $style_css = 'build/style-index.css';
//    wp_register_style(
//        'kratos-block',
//        plugins_url( $style_css, __FILE__ ),
//        array(),
//        filemtime( "$dir/$style_css" )
//    );

    register_block_type('kratos/alert', array(
        'editor_script' => 'kratos-block',
        'editor_style' => 'kratos-block',
        'style' => 'kratos-block',
    ));
    register_block_type('kratos/collapse', array(
        'style' => 'kratos-block',
    ));
    register_block_type('kratos/accordion', array(
        'style' => 'kratos-block',
    ));
    register_block_type('kratos/bilibili', array(
        'style' => 'kratos-block',
    ));
}

add_action('init', 'create_block_bootstrap_4_block_init');
