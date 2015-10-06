<?php
/**
 * The template for displaying the footer.
 *
 * @package Maskitto Light
 */
global $maskitto_light;
?>

</div>
	<footer class="bottom">
		<div class="container">
		
			<?php if( !isset($maskitto_light['footer-logo']) || $maskitto_light['footer-logo'] == 1 ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
					<?php if(isset($maskitto_light['logo-image']['url']) && $maskitto_light['logo-image']['url']) : ?>
						<img src="<?php echo esc_url($maskitto_light['logo-image']['url']); ?>" alt="" />
					<?php elseif( get_header_image() ) : ?>
						<img src="<?php echo esc_url( header_image() ); ?>" alt="" />
					<?php endif; ?>
				</a>
			<?php endif; ?>


			<?php /*
			*
			*  Plase support this theme developers efforts to remove or change copyrights!
			*
			*/ $author_website = 'http://shufflehound.com'; ?>
				<div class="copyrights">
					<span class="thank-you-for-your-support">
						&#169; <?php echo date("Y"); ?> <?php _e( 'Theme by', 'maskitto-light' ); ?> <a href="<?php echo esc_url($author_website); ?>"><?php _e( 'Shufflehound', 'maskitto-light' ); ?></a>. <?php _e( 'All rights reserved', 'maskitto-light' ); ?>. 
					</span>
					<?php echo esc_attr($maskitto_light['footer-text']); ?>
				</div>
			<?php /*
			*
			*  Plase support this theme developers efforts to remove or change copyrights!
			*
			*/ ?>

		</div>
	</footer>

	<?php if( $maskitto_light['back-to-top']) { ?>
		<div class="back-top"><i class="fa fa-angle-up"></i></div>
	<?php } ?>

<?php wp_footer(); ?>

	<?php if( $maskitto_light['page-layout'] == 2 || $maskitto_light['page-layout'] == 3 || $maskitto_light['page-layout'] == 4 ) { ?>
		</div>
	<?php } ?>

<?php echo maskitto_light_generate_js(); ?>

<?php global $wp_customize;
if ( isset( $wp_customize ) ) : ?>
	<div class="live-customizer-mode-enabled"></div>	    
<?php endif; ?>

</body>
</html>