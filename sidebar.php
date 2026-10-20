<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<aside id="wiki-sidebar" class="site-sidebar" aria-label="<?php esc_attr_e( 'サイドバー', 'wp-wiki' ); ?>">
<nav class="wiki-category-nav" aria-label="カテゴリー"><a class="wiki-home-link" href="<?php echo esc_url(home_url('/')); ?>">メインページ</a><h2>カテゴリー</h2><ul class="wiki-category-tree"><?php wp_list_categories(['title_li'=>'','show_count'=>false,'hierarchical'=>true]); ?></ul></nav>
<?php
$wiki_widget_filter = static function($instance,$widget) {
 if ($widget->id_base==='block' && isset($instance['content']) && str_contains($instance['content'],'wp:categories')) return false;
 return $instance;
};
add_filter('widget_display_callback',$wiki_widget_filter,10,2);
?><?php if ( is_active_sidebar( 'sidebar-primary' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-primary' ); ?>
<?php else : ?>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( '検索', 'wp-wiki' ); ?></h2>
        <?php get_search_form(); ?>
    </section>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( '最近の記事', 'wp-wiki' ); ?></h2>
        <ul>
        <?php foreach ( get_posts( array( 'numberposts' => 5, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) as $recent_post ) : ?>
            <li><a href="<?php echo esc_url( get_permalink( $recent_post ) ); ?>"><?php echo esc_html( get_the_title( $recent_post ) ?: __( '無題の記事', 'wp-wiki' ) ); ?></a></li>
        <?php endforeach; ?>
        </ul>
    </section>
    <section class="widget">
        <h2 class="widget-title"><?php esc_html_e( 'カテゴリー', 'wp-wiki' ); ?></h2>
        <ul><?php wp_list_categories( array( 'title_li' => '', 'show_count' => false ) ); ?></ul>
    </section>
<?php endif; ?>
<?php
// Published pages from this site only; custom widgets do not hide these links.
$wpwiki_pages = wp_list_pages( array(
    'title_li'    => '',
    'echo'        => false,
    'post_status'=> 'publish',
    'sort_column'=> 'menu_order,post_title',
) );
if ( $wpwiki_pages ) : ?>
    <section class="widget widget-pages">
        <h2 class="widget-title"><?php esc_html_e( '固定ページ', 'wp-wiki' ); ?></h2>
        <ul><?php echo $wpwiki_pages; // WordPress generates escaped page links. ?></ul>
    </section>
<?php endif; ?>
<?php remove_filter('widget_display_callback',$wiki_widget_filter,10); ?>
</aside>
