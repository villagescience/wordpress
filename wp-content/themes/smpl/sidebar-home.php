<?php 
/**
 * Sidebar-home Template
 *
 * This template is displayed on the homepage
 *
 * @package WooFramework
 * @subpackage Template
 */
?>	
<aside id="sidebar" class="col-right">
	<div class="inner">
		<?php if ( function_exists('dynamic_sidebar') ) dynamic_sidebar( 'homepage' );  ?> 
	</div>
</aside><!-- /#sidebar -->
