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
			$categories = maskitt_light_get_terms_per_post_type( 'category', array( 'post_type' => 'post' ) );
			foreach ($categories as $category) : ?>
				<?php if( $category->term_id == get_query_var('cat') ) { ?>
					<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item active-category"><?php echo $category->name; ?></a>
				<?php } else { ?>
					<a href="<?php echo get_category_link( $category->term_id ); ?>" class="category-item"><?php echo $category->name; ?></a>
				<?php } ?>
			<?php endforeach; ?>
			<?php if( count( $categories ) > 6 ) { ?>
				<a href="#" class="category-item category-show-all"><?php _e( '(more)', 'maskitto-light' ); ?></a>
			<?php } ?>
		</div>
	</div>
<?php endif; ?>


<div class="page-section page-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-<?php echo ( !isset( $maskitto_light['blog-widgets'] ) || $maskitto_light['blog-widgets'] == 1 ) ? '8' : '12'; ?> blog-column-left">
				<div class="row blog-list">

				<?php
					$cat = (get_query_var('cat')) ? get_query_var('cat') : '';
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					query_posts( array(
						'post_type' => 'post',
						'paged' => $paged,
						'cat' => $cat
					) );

					if ( have_posts() ) :
				?>

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

			<?php if( !isset( $maskitto_light['blog-widgets'] ) || $maskitto_light['blog-widgets'] == 1 ) : ?>
			<div class="col-md-4 blog-column-right">

				<?php get_sidebar(); ?>

			</div>
			<?php endif; ?>
			
		</div>
	</div>
</div>

<?php get_footer(); ?>