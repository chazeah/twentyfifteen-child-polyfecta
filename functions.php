<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parent_style = 'twentyfifteen-style';

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}

// Remove URL field from comments.
add_filter('comment_form_default_fields', 'remove_url_comments');
function remove_url_comments($fields) {
    unset($fields['url']);
    return $fields;
}

// Remove built-in RSS feeds.
add_action('after_setup_theme', 'polyfecta_feed_setup' , 11);
function polyfecta_feed_setup() {
    remove_theme_support('automatic-feed-links');
}

// Add custom RSS link.
add_action('wp_head', 'add_polyfecta_rss_link');
function add_polyfecta_rss_link() {
    echo '<link rel="alternate" type="application/rss+xml" title="Polyfecta" href="https://polyfecta.com/feed/"' . " />\n";
}

// Remove <category> elements from RSS feeds.
add_filter('the_category_rss', '__return_empty_string');

// Show the full content in the RSS feed's "description".
add_filter('the_excerpt_rss', 'show_full_content_in_description', 10, 1);
function show_full_content_in_description($excerpt){
    return get_the_content_feed();
}

// Enable shortcodes in text widgets.
add_filter('widget_text', 'do_shortcode');

// Crazy hacks to enable SVG. (Part 1)
add_filter('wp_check_filetype_and_ext', 'enable_svg_upload', 10, 4 );
function enable_svg_upload($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype( $filename, $mimes );

    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
}

// Crazy hacks to enable SVG. (Part 2)
add_filter('upload_mimes', 'cc_mime_types');
function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

?>
