<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php 
	global $product;
	extract(etheme_get_single_product_sidebar());
?>

<?php
	/**
	 * Single Product Content
	 *
	 * @author 		WooThemes
	 * @package 	WooCommerce/Templates
	 * @version     2.1.2
	 */
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class('single-product-page'); ?>>
	
	<div class="row product-info sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">
		<?php
			/**
			 * woocommerce_before_single_product_summary hook
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
		?>

		<?php if($single_product_sidebar && ($position == 'left' || ($responsive == 'top' && $position == 'right'))): ?>
			<div class="span3 sidebar sidebar-left single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>

		<div class="span<?php echo $images_span; ?>">
			<?php woocommerce_show_product_images(); ?>
		</div>
		<div class="span<?php echo $meta_span; ?> product_meta">
			<?php if (etheme_get_option('show_name_on_single')): ?>
				<h2 class="product-name"><?php the_title(); ?></h2>
			<?php endif; ?>
			<div style="display:none;" itemprop="name"><?php the_title(); ?></div>
			
			<h4 style="display:none;"><?php _e('Product Information', ETHEME_DOMAIN) ?></h4>
			
			<?php woocommerce_template_loop_rating(); ?>
			
			<span class="artist_name_wrapper"><?php adelman_product_artist(); ?></span>
			
			<?php $version_short = $product->get_attribute( 'pa_version-short' ); ?>
			<?php if($version_short): ?>
				<span class="short_description_meta">Version: <?php echo $version_short; ?></span>
			<?php endif; ?>

			<?php $dimensions = $product->get_dimensions(); ?>
			<?php if ($dimensions): ?>
				<span class="short_description_meta">Dimensions: <?php echo $dimensions; ?></span>
			<?php endif; ?>
			
			<?php
				$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
				echo $product->get_categories( ', ', '<span class="short_description_meta">' . _n( 'Category:', 'Categories:', $size, 'woocommerce' ) . ' ', '</span>' );
			?>
			
			<?php
				/**
				 * woocommerce_single_product_summary hook
				 *
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 */
				do_action( 'woocommerce_single_product_summary' );
			?>
			
		    <?php if ( etheme_get_custom_field('size_guide_img') ) : ?>
		    	<?php $lightbox_rel = (get_option('woocommerce_enable_lightbox') == 'yes') ? 'prettyPhoto' : 'lightbox'; ?>
		        <div class="size_guide">
		    	 <a rel="<?php echo $lightbox_rel; ?>" href="<?php etheme_custom_field('size_guide_img'); ?>"><?php _e('SIZING GUIDE', ETHEME_DOMAIN); ?></a>
		        </div>
		    <?php endif; ?>	
			<?php $reserved = get_field('reserved'); ?>
			<?php if (!$reserved): ?>
			<?php woocommerce_template_single_add_to_cart(); ?>
			<?php endif; ?>

			<?php woocommerce_template_single_meta(); ?>
			<?php if($reserved): ?>
			<h4>This item is reserved</h4>
			<?php endif; ?>
            
            <?php if(etheme_get_option('share_icons')): ?>
							<span class="share-button-title">Share</span>
							<?php echo do_shortcode('[share text="'.get_the_title().'"]'); ?>
						<?php endif; ?>
            
			<?php woocommerce_template_single_sharing(); ?>
				
		</div>

		<?php if($single_product_sidebar && ($position == 'right' || ($responsive == 'bottom' && $position == 'left'))): ?>
			<div class="span3 sidebar sidebar-right single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>

	</div>
	
	<?php
		woocommerce_output_product_data_tabs();

		if(etheme_get_custom_field('additional_block') != '') {
			echo '<div class="sidebar-position-without">';
			et_show_block(etheme_get_custom_field('additional_block'));
			echo '</div>';
		} 

	  	if(etheme_get_option('upsell_location') == 'after_content') woocommerce_upsell_display();
	  	if(etheme_get_option('show_related'))
			woocommerce_output_related_products();
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>