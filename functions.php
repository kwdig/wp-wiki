<?php
/** Theme setup and assets. Add new features here. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function wpwiki_setup() {
    load_theme_textdomain( 'wp-wiki', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/editor.css' );
    register_nav_menus( array( 'primary' => __( 'ヘッダーメニュー', 'wp-wiki' ), 'footer' => __( 'フッターメニュー', 'wp-wiki' ) ) );
}
add_action( 'after_setup_theme', 'wpwiki_setup' );
/** 外観 → ウィジェットで右サイドバーを編集できます。 */
function wpwiki_widgets() {
    register_sidebar( array(
        'name' => __( '右サイドバー', 'wp-wiki' ),
        'id' => 'sidebar-primary',
        'description' => __( 'PCでは本文の右、スマホでは本文の下に表示されます。', 'wp-wiki' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpwiki_widgets' );
function wpwiki_assets() {
    $version = wp_get_theme()->get( 'Version' );
    $css_files = array( get_template_directory() . '/style.css' );
    if ( is_child_theme() ) { $css_files[] = get_stylesheet_directory() . '/style.css'; }
    wp_register_style( 'wpwiki-style', false, array(), $version );
    wp_enqueue_style( 'wpwiki-style' );
    foreach ( $css_files as $css_file ) {
        if ( is_readable( $css_file ) ) {
            wp_add_inline_style( 'wpwiki-style', file_get_contents( $css_file ) );
        }
    }
    if ( has_nav_menu( 'primary' ) ) {
        wp_enqueue_script( 'wpwiki-navigation', get_template_directory_uri() . '/assets/navigation.js', array(), $version, true );
    }
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_action( 'wp_enqueue_scripts', 'wpwiki_assets' );

/** A short description for search results, without adding a heavy SEO plugin. */
function wpwiki_meta_description() {
    if ( ! apply_filters( 'wpwiki_enable_meta_description', true ) || is_search() || is_404() ) { return; }
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) { return; }
    if ( is_singular() ) {
        $post = get_queried_object();
        $description = post_password_required( $post ) ? get_bloginfo( 'description' ) : ( has_excerpt( $post ) ? $post->post_excerpt : $post->post_content );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $description = term_description();
        if ( ! trim( wp_strip_all_tags( $description ) ) ) {
            $description = single_term_title( '', false ) . 'の記事一覧。' . get_bloginfo( 'name' ) . 'の最新記事をご覧いただけます。';
        }
    } else {
        $description = get_bloginfo( 'description' ) ?: get_bloginfo( 'name' );
    }
    $description = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( $description ) ) ) );
    $description = function_exists( 'mb_substr' ) ? mb_substr( $description, 0, 160 ) : substr( $description, 0, 320 );
    if ( $description ) { echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n"; }
}
add_action( 'wp_head', 'wpwiki_meta_description', 1 );

/** Generate WebP sub-sizes for future JPEG uploads when supported by the server. */
function wpwiki_image_output_format( $formats ) {
    if ( apply_filters( 'wpwiki_enable_webp', true ) && wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) && ! isset( $formats['image/jpeg'] ) ) {
        $formats['image/jpeg'] = 'image/webp';
    }
    return $formats;
}
add_filter( 'image_editor_output_format', 'wpwiki_image_output_format' );

add_action('wp_enqueue_scripts',static function(){wp_enqueue_script('wpwiki-layout',get_template_directory_uri().'/assets/wiki-layout.js',[],filemtime(get_template_directory().'/assets/wiki-layout.js'),true);});

/** Additional responsive sources for article images. */
function wpwiki_responsive_image_sizes() {
    add_image_size( 'wpwiki-small', 180, 0, false );
    add_image_size( 'wpwiki-medium', 360, 0, false );
    add_image_size( 'wpwiki-large', 672, 0, false );
}
add_action( 'after_setup_theme', 'wpwiki_responsive_image_sizes' );
function wpwiki_responsive_webp_quality( $quality, $mime, $size = array() ) {
    return 'image/webp' === $mime && ! empty( $size['width'] ) && $size['width'] <= 672 ? 52 : $quality;
}
add_filter( 'wp_editor_set_quality', 'wpwiki_responsive_webp_quality', 10, 3 );
/** Bound the transfer size of complex responsive WebP images; keep originals intact. */
function wpwiki_compress_responsive_webp( $metadata, $attachment_id ) {
    if ( empty( $metadata['sizes'] ) ) { return $metadata; }
    $source = get_attached_file( $attachment_id );
    if ( ! $source || ! is_file( $source ) ) { return $metadata; }
    foreach ( array( 'wpwiki-small', 'wpwiki-medium', 'wpwiki-large' ) as $key ) {
        if ( empty( $metadata['sizes'][$key] ) ) { continue; }
        $size = $metadata['sizes'][$key];
        if ( 'image/webp' !== ( $size['mime-type'] ?? '' ) ) { continue; }
        $path = dirname( $source ) . '/' . $size['file'];
        $budget = max( 8192, (int) ( $size['width'] * $size['height'] * 0.16 ) );
        if ( ! is_file( $path ) || filesize( $path ) <= $budget ) { continue; }
        foreach ( array( 46, 40, 34 ) as $quality ) {
            $editor = wp_get_image_editor( $source );
            if ( is_wp_error( $editor ) ) { break; }
            $resized = $editor->resize( $size['width'], $size['height'], false );
            if ( is_wp_error( $resized ) || is_wp_error( $editor->set_quality( $quality ) ) ) { break; }
            $saved = $editor->save( $path, 'image/webp' );
            if ( is_wp_error( $saved ) ) { break; }
            clearstatcache( true, $path );
            $metadata['sizes'][$key]['filesize'] = filesize( $path );
            if ( $metadata['sizes'][$key]['filesize'] <= $budget ) { break; }
        }
    }
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'wpwiki_compress_responsive_webp', 20, 2 );



/** Keep theme controls off the render-blocking path, including WordPress 6.0. */
function wpwiki_defer_control_scripts( $tag, $handle ) {
    if ( ! in_array( $handle, array( 'wpwiki-layout', 'wpwiki-navigation' ), true ) ) { return $tag; }
    return preg_replace( '/<script\b(?=[^>]*\bsrc=)(?![^>]*\bdefer\b)/i', '<script defer', $tag, 1 );
}
add_filter( 'script_loader_tag', 'wpwiki_defer_control_scripts', 10, 2 );