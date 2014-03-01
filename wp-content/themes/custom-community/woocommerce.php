<?php get_header(); ?>

	<div id="content" class="span8">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<?php woocommerce_content(); ?>

		</div><!-- .page -->
		<?php cc_list_posts_on_page(); ?> 
		
		<div class="clear"></div>
		
		<?php do_action( 'bp_after_blog_page' ) ?>
		
		<?php edit_post_link( __( 'Edit this page.', 'cc' ), '<p class="edit-link">', '</p>'); ?>
		
		<!-- instead of comment_form() we use comments_template(). If you want to fall back to wp, change this function call ;-) -->
		<?php comments_template(); ?>
		
		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer(); ?>