<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label><span class="screen-reader-text"><?php esc_html_e( '検索キーワード', 'wp-wiki' ); ?></span><input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'キーワードで検索', 'wp-wiki' ); ?>" required></label>
    <button type="submit"><?php esc_html_e( '検索', 'wp-wiki' ); ?></button>
</form>
