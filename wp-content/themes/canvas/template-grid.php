<?php
/**
 * Template Name: Grid
 *
 * The magazine page template displays your posts with a "magazine"-style
 * content slider at the top and a grid of posts below it. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $post; 
 get_header();

 if ( is_paged() ) $is_paged = true; else $is_paged = false;
 
 $page_template = woo_get_page_template();
 $page_id     = get_queried_object_id();
?>

    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full magazine">
    
    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section>
            	<?php woo_loop_before(); ?>   
             	<?php if ( $woo_options['woo_slider_magazine'] == 'true' && ! $is_paged ) { if ( get_option( 'woo_exclude' ) ) update_option( 'woo_exclude', '' ); woo_slider_magazine(); } ?>
             	<div class="fix"></div>

		<div class="content">
		<?php
		$post = get_page($page_id); 
		$content = apply_filters('the_content', $post->post_content);
		echo $content;
		?>
		</div>

<?php
//for each category, show 5 posts
$cat_args=array(
  'orderby' => 'name',
  'order' => 'ASC'
   );
$categories=get_categories($cat_args);
  foreach($categories as $category) { 
    $args=array(
      'showposts' => 5,
      'category__in' => array($category->term_id),
      'caller_get_posts'=>1
    );
    $posts=get_posts($args);
      if (($posts) && ($category->name !== "Uncategorized")) {
        echo '<li class="category-block" style="background-image: url(/wp-content/themes/canvas/images/categories/' . $category->name . '.jpg);"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </li> ';
      } // if ($posts
    } // foreach($categories
?>            

</section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
            
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
    
		
<?php get_footer(); ?>
