<?php
/**
 * @package Maskitto Light
 */

global $maskitto_light;
get_header();
?>


<?php
	$blog_slide_height = 280;
	if( isset( $maskitto_light['blog-thumb-height'] ) && $maskitto_light['blog-thumb-height'] >= 120 ) :
		$blog_slide_height = esc_attr( $maskitto_light['blog-thumb-height'] );
	endif;

	if( isset( $maskitto_light['blog-thumb-status'] ) && $maskitto_light['blog-thumb-status'] == 1 ) : ?>
	<div class="blog-large-thumb" style="background-image: url(<?php echo esc_url( $maskitto_light['blog-thumb-url']['url'] ); ?>); height: <?php echo $blog_slide_height; ?>px;">
		<div class="container">
			<div class="slide-details">
				<div class="slide-title"><?php echo esc_attr( $maskitto_light['blog-thumb-title'] ); ?></div>
			</div>
		</div>
	</div>
<?php endif; ?>


<?php if( !isset( $maskitto_light['blog-categories'] ) || $maskitto_light['blog-categories'] == 1 ) : ?>
	<div class="page-category">
		<div class="container">
			<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="category-item<?php if( !get_query_var('cat') ) { echo ' active-category'; } ?>"><?php _e( 'All', 'maskitto-light' ); ?></a>
			<?php

			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'count',
				'order'                    => 'desc',
				'hide_empty'               => 1,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false 
			);

			$categories = get_categories( $args );
			foreach ($categories as $category) { ?>
				<?php if( $category->cat_ID == get_query_var('cat') ) { ?>
					<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item active-category"><?php echo $category->cat_name; ?></a>
				<?php } else { ?>
					<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item"><?php echo $category->cat_name; ?></a>
				<?php } ?>
			<?php } ?>
			<?php if( count( $categories ) > 6 ) { ?>
				<a href="#" class="category-item category-show-all"><?php _e( '(more)', 'maskitto-light' ); ?></a>
			<?php } ?>
		</div>
	</div>
<?php endif; ?>


<div class="page-section page-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-8 blog-column-left">
				<div class="row blog-list">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

				</div>

				<div class="comment-navigation grey-light">
					<?php echo maskitto_light_paginate_links(); ?>
				</div>
			</div>
			<div class="col-md-4 blog-column-right">

				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>