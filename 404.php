<?php get_header(); ?>
<section class="entry">
    <h1><?php esc_html_e( 'ページが見つかりません', 'wp-wiki' ); ?></h1>
    <p><?php esc_html_e( '検索するか、トップページからお探しください。', 'wp-wiki' ); ?></p>
    <?php get_search_form(); ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'トップページへ', 'wp-wiki' ); ?></a>
</section>
<?php get_footer(); ?>
