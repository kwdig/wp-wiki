<?php
/** Related posts: same category first, then matching tags, within this site. */
if (!defined('ABSPATH') || !is_singular('post')) { return; }
$current_id = get_the_ID();
$related_ids = [];
foreach (['category', 'post_tag'] as $taxonomy) {
    $terms = wp_get_post_terms($current_id, $taxonomy, ['fields'=>'ids']);
    if (is_wp_error($terms) || !$terms || count($related_ids)>=5) { continue; }
    $matches = get_posts([
        'post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>5-count($related_ids),
        'post__not_in'=>array_merge([$current_id],$related_ids), 'ignore_sticky_posts'=>true,
        'orderby'=>['modified'=>'DESC','ID'=>'DESC'], 'fields'=>'ids',
        'tax_query'=>[['taxonomy'=>$taxonomy,'field'=>'term_id','terms'=>$terms]],
    ]);
    $related_ids = array_merge($related_ids,$matches);
}
if (!$related_ids) { return; }
?>
<section class="wiki-related" aria-labelledby="wiki-related-title">
    <h2 id="wiki-related-title"><?php esc_html_e('関連記事','wp-wiki'); ?></h2>
    <ul>
    <?php foreach ($related_ids as $related_id) : ?>
        <li><a href="<?php echo esc_url(get_permalink($related_id)); ?>"><?php echo esc_html(get_the_title($related_id) ?: __('無題の記事','wp-wiki')); ?></a><time datetime="<?php echo esc_attr(get_the_modified_date(DATE_W3C,$related_id)); ?>"><?php echo esc_html(get_the_modified_date(get_option('date_format'),$related_id)); ?></time></li>
    <?php endforeach; ?>
    </ul>
</section>