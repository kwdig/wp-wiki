<?php get_header(); ?>
<div class="wiki-page-tabs"><span>記事一覧</span><a href="#wiki-articles">項目へ移動</a></div><header class="archive-header">
<?php if ( is_search() ) : ?>
    <h1><?php printf( esc_html__( '検索結果：%s', 'wp-wiki' ), esc_html( get_search_query() ) ); ?></h1>
<?php elseif ( is_archive() ) : ?>
    <?php if ( is_category() ) : ?>
        <h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
    <?php elseif ( is_tag() ) : ?>
        <h1><?php echo esc_html( single_tag_title( '', false ) ); ?></h1>
    <?php else : the_archive_title( '<h1>', '</h1>' ); endif; ?>
    <?php the_archive_description( '<div>', '</div>' ); ?>
<?php else : ?>
    <h1><?php echo esc_html( is_home() && ! is_front_page() ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'メインページ', 'wp-wiki' ) ); ?></h1>
<?php endif; ?>
<?php if(is_home()): ?><p>カテゴリーやキーワードから、知りたい項目を探せます。</p><?php endif; ?></header>
<?php if ( have_posts() ) : ?>
    <div id="wiki-articles" class="wiki-list-heading"><h2>記事一覧</h2><span>最新の投稿</span></div><div class="post-grid">
    <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; ?>
    </div>
    <?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => __( '前へ', 'wp-wiki' ), 'next_text' => __( '次へ', 'wp-wiki' ) ) ); ?>
<?php else : get_template_part( 'template-parts/content', 'none' ); endif; ?>
<?php get_footer(); ?>
