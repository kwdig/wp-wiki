<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
<div class="post-card-body">
    <h2><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ?: __( '無題の記事', 'wp-wiki' ) ); ?></a></h2>
    <?php if ( 'post' === get_post_type() ) : ?>
    <div class="post-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>　<span class="post-categories">カテゴリー：<?php the_category( ', ' ); ?></span>
        <?php if ( has_tag() ) : ?><span class="post-tags">　タグ：<?php the_tags( '', ', ', '' ); ?></span><?php endif; ?></div>
    <?php endif; ?>
    <?php the_excerpt(); ?>
    <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( '続きを読む', 'wp-wiki' ); ?><span class="screen-reader-text">：<?php the_title(); ?></span></a>
    </div>
</article>
