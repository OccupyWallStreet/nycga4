<?php
/*
Template Name: NYCGA Homepage
*/
?>
<?php get_header() ?>
<?php locate_template( array( 'leftsidebar.php' ), true ) ?>
<div id="content" class="grid_15">
	<?php do_action( 'bp_before_blog_page' ) ?>
	<div class="page" id="blog-page" role="main">
		<?php 
			$pages = get_pages('');
			if($pages): 
			foreach($pages as $page):
		?>
		<h2 class="pagetitle"><?php the_title(); ?></h2>
		<div id="post-<?php the_ID(); ?>" <?php post_class();?>>
			<div class="entry">
				<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>
			</div>
		</div>
		<?php comments_template();?>
		<?php
			endforeach;
			endif;
		?>
	</div><!-- .page -->
	<?php do_action( 'bp_after_blog_page' ); ?>
</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>


