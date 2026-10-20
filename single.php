<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="post-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>　<span class="post-categories">カテゴリー：<?php the_category( ', ' ); ?></span>
        <?php if ( has_tag() ) : ?><span class="post-tags">　タグ：<?php the_tags( '', ', ', '' ); ?></span><?php endif; ?></div>
    </header>
    <div class="entry-content"><?php the_content(); wp_link_pages( array( 'before' => '<nav class="page-links" aria-label="' . esc_attr__( '記事内ページ', 'wp-wiki' ) . '">', 'after' => '</nav>' ) ); ?></div>
    <?php get_template_part('template-parts/related-posts'); ?>
    <?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
</article>
<?php endwhile; get_footer(); ?>
