<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Maskitto Light
 */
global $maskitto_light;
?>

<div id="secondary" class="widget-area <?php if( !isset($maskitto_light['blog-layout']) || $maskitto_light['blog-layout'] == 1 ) { echo'widget-area-2'; } ?>" role="complementary">

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>
		<aside id="no-widgets-found" class="widget widget_recent_entries">
			<h1 class="widget-title" style="margin-bottom: 0; padding-bottom: 0; border: 0; text-transform: none;">
				<?php if( current_user_can( 'manage_options' ) ) : ?>
					<a href="<?php echo admin_url(); ?>/widgets.php"><?php _e( 'Please assign your widgets here', 'maskitto-light' ) ?></a>
				<?php else : ?>
					<?php _e( 'No widget is assigned', 'maskitto-light' ) ?>
				<?php endif; ?>
			</h1>
		</aside>
	<?php endif; ?>

</div>