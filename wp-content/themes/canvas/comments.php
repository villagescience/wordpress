<?php
/**
 * Comments Template
 *
 * This template file handles the display of comments, pingbacks and trackbacks.
 *
 * External functions are used to display the various types of comments.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 // Do not delete these lines
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) { die ( __( 'Please do not load this page directly. Thanks!', 'woothemes' ) ); }

 // Password is required so don't display comments.
if ( post_password_required() ) { ?><p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'woothemes' ); ?></p><?php return; }

/**
 * Comment Output.
 *
 * This is where our comments display is generated.
 */
 
 $comments_by_type = &separate_comments( $comments );
 
 // You can start editing here -- including this comment!
  
	if ( have_comments() ) {

		echo '<div id="comments">';
 
	 	if ( ! empty( $comments_by_type['comment'] ) ) { ?>
		 	<h3 id="comments-title"><?php printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'woothemes' ), number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' ); ?></h3>
		 	<ol class="commentlist">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use custom_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define custom_comment() and that will be used instead.
					 * See custom_comment() in /includes/theme-comments.php for more.
					 */
					wp_list_comments( array( 'callback' => 'custom_comment', 'type' => 'comment', 'avatar_size' => 40 ) );
				?>
			</ol>
		 	<?php
		 	// Comment pagination.
		 	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<div class="navigation">
				<div class="nav-previous fl"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'woothemes' ) ); ?></div>
				<div class="nav-next fr"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'woothemes' ) ); ?></div>
				<div class="fix"></div><!--/.fix-->
			</div><!-- .navigation -->
			<?php } // End IF Statement
	
		} // End empty($comments_by_type['comment'] IF Statement
	
			if ( ! empty( $comments_by_type['pings'] ) ) { ?>
			 	<h3 id="comments-title"><?php  _e( 'Trackbacks/Pingbacks', 'woothemes' ); ?></h3>
			 	<ol class="commentlist">
					<?php
						/* Loop through and list the pings. Tell wp_list_comments()
						 * to use list_pings() to format the pings.
						 * If you want to overload this in a child theme then you can
						 * define list_pings() and that will be used instead.
						 * See list_pings() in /includes/theme-comments.php for more.
						 */
						wp_list_comments( array( 'callback' => 'list_pings', 'type' => 'pings' ) );
					?>
				</ol>
			<?php }
 
		echo '</div>';

	} else {
 
		echo '<div id="comments">';
		
		// If there are no comments and comments are closed, let's leave a little note, shall we?
		if ( ! comments_open() && is_singular() ) { ?><h5 class="nocomments"><?php _e( 'Comments are closed.', 'woothemes' ); ?></h5><?php }
		else { ?><h5 class="nocomments"><?php _e( 'No comments yet.', 'woothemes' ); ?></h5><?php }

		echo '</div>';

	} // End IF Statement
  
/**
 * Respond Form.
 *
 * This is where the comment form is generated.
 */
 
 comment_form();
?>