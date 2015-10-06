<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package Maskitto Light
 */
?>

<section class="no-results not-found <?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) { echo'widget-area-2'; } ?>">
	<aside id="recent-posts-2" class="widget widget_recent_entries">
		<h1 class="widget-title"><?php _e( 'Nothing Found', 'maskitto-light' ); ?></h1>

		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'maskitto-light' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'maskitto-light' ); ?></p>

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'maskitto-light' ); ?></p>

		<?php endif; ?>
	</aside>
</section><!-- .no-results -->
