<?php global $woo_options; ?>
<div class="search_main">
    <form method="get" class="searchform" action="<?php echo home_url( '/' ); ?>" >
        <input type="text" class="field s" name="s" value="<?php _e( 'Search...', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'Search...', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Search...', 'woothemes' ); ?>';}" />
        <?php if ( isset( $woo_options['woo_header_search_scope'] ) && $woo_options['woo_header_search_scope'] == 'products' && is_woocommerce_activated() ) { echo '<input type="hidden" name="post_type" value="product" />'; } ?>
		<i class="icon-search"></i>
    </form>    
    <div class="fix"></div>
</div>
