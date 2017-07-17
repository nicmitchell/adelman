<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php 
	global $product;
	extract(etheme_get_single_product_sidebar());
?>

<?php
	/**
	Why we need this custom template:
	- Reserved items rely on ACF fields
	- Showing dimensions
	- Note: Can probalby use hooks to eliminate need for custom templates

	 * Single Product Content
	 *
	 * @author 		WooThemes
	 * @package 	WooCommerce/Templates
	 * @version     3.0.0
	 */
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<?php $tabs_position = ( etheme_get_option( 'tabs_position' ) == 'tabs-under' ) ? 'under' : 'inside'; ?>

<div id="product-<?php the_ID(); ?>" <?php post_class('single-product-page'); ?>>
	
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
		<div class="content-area <?php echo ( $position == 'left' ) ? 'pull-right' : '' ; ?>">
			<div class="span<?php echo $images_span; ?>">
				<?php woocommerce_show_product_images(); ?>
			</div>
			<div class="span<?php echo $meta_span; ?> product_meta">
				<?php if (etheme_get_option('show_name_on_single')): ?>
					<h2 class="product-name"><?php the_title(); ?></h2>
				<?php else : ?>
					<div style="display:none;" itemprop="name" class="product-name-hiden"><?php the_title(); ?></div>
				<?php endif; ?>
				<h4><?php _e('Product Information', ETHEME_DOMAIN) ?></h4>
				
				<?php woocommerce_template_single_rating(); ?>

				<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && $product->get_sku() ) : ?>
					<span itemprop="productID" class="sku_wrapper"><?php _e( 'Product code', ETHEME_DOMAIN ); ?>: <span class="sku"><?php echo $product->get_sku(); ?></span></span>
				<?php endif; ?>

				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
				
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
						<?php $lightbox_rel = 'lightbox'; ?>
							<div class="size_guide">
						 <a rel="<?php echo $lightbox_rel; ?>" href="<?php etheme_custom_field('size_guide_img'); ?>"><?php _e('SIZING GUIDE', ETHEME_DOMAIN); ?></a>
							</div>
					<?php endif; ?>	
					
					<?php /* may be able to hook reserved stuff into woocommerce_single_product_summary */ ?>
						<?php $reserved = get_field('reserved'); ?>
						<?php if (!$reserved): ?>
							<?php woocommerce_template_single_add_to_cart(); ?>
						<?php endif; ?>
					 
						<?php woocommerce_template_single_meta(); ?>
						<?php if($reserved): ?>
							<h4>This item is reserved</h4>
						<?php endif; ?>
							
						<?php if(etheme_get_option('share_icons')) echo do_shortcode('[share text="'.get_the_title().'"]'); ?>
							
				<?php woocommerce_template_single_sharing(); ?>
					
			</div>
			
			<?php if ( $tabs_position == 'inside' ) : ?>
				<div class="tabs-under-product">
					<?php woocommerce_output_product_data_tabs(); ?>
				</div>
			<?php endif; ?>
		</div>
			<div class="span3 sidebar sidebar-<?php echo $position; ?> pull-<?php echo $position; ?> mobile-sidebar-<?php echo $responsive; ?> single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
	</div>
	
	<?php
	
		if ( $tabs_position != 'inside' ) {
			woocommerce_output_product_data_tabs();
		}

		if(etheme_get_custom_field('additional_block') != '') {
			echo '<div class="sidebar-position-without">';
			et_show_block(etheme_get_custom_field('additional_block'));
			echo '</div>';
		} 

			if(etheme_get_option('upsell_location') == 'after_content') woocommerce_upsell_display();
			if(etheme_get_option('show_related'))
			woocommerce_output_related_products();
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>