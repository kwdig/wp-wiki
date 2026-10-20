<?php if ( post_password_required() ) { return; } ?>
<section id="comments" class="comments-area">
<?php if ( have_comments() ) : ?>
    <h2><?php esc_html_e( 'コメント', 'wp-wiki' ); ?></h2>
    <ol class="comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 40 ) ); ?></ol>
    <?php the_comments_pagination( array( 'prev_text' => __( '前へ', 'wp-wiki' ), 'next_text' => __( '次へ', 'wp-wiki' ) ) ); ?>
<?php endif; ?>
<?php if ( ! comments_open() && get_comments_number() ) : ?><p><?php esc_html_e( 'コメントの受付は終了しました。', 'wp-wiki' ); ?></p><?php endif; ?>
<?php comment_form(); ?>
</section>
