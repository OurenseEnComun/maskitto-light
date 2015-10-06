<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Maskitto Light
 */
get_header(); ?>

<div class="page-404"></div>
<div class="page-section">
	<div class="container">
		<h4 class="text-center"><?php _e( 'The page you are looking for does not exist', 'maskitto-light' ); ?></h4>
		<a href="<?php echo get_home_url(); ?>" class="btn btn-default"><i class="fa fa-angle-right"></i><?php _e( 'Back to home page', 'maskitto-light' ); ?></a>
	</div>
</div>

<?php get_footer(); ?>
