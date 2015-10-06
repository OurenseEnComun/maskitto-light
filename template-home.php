<?php 
	/*template name: Front page */
	get_header();
?>

	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		
		<?php echo do_shortcode( apply_filters( 'the_content', get_the_content() ) ); ?>

	<?php endwhile; endif; ?>

<?php
	get_footer();
?>