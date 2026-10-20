<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
    <header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1></header>
    <div class="entry-content"><?php the_content(); wp_link_pages( array( 'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'ページ内ナビゲーション', 'wp-wiki' ) . '">', 'after' => '</nav>' ) ); ?></div>
    <?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
</article>
<?php endwhile; get_footer(); ?>
