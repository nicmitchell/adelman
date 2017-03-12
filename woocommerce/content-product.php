<?php
/**
 * The template for displaying product content within loops.
 *
 * Nic Note: This template override is currently necessary for changing the text "Out of stock" to "Sold". 
 * The alternative is to translate it using a PO file or a similar method.
 * 
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.1
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$hover = etheme_get_option('product_img_hover');

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;


// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

$lazy_images = false;
$style = 'default';

if (!empty($woocommerce_loop['lazy-load'])) {
	$lazy_images = true;
}

if (!empty($woocommerce_loop['style']) && $woocommerce_loop['style'] == 'advanced') {
	$style = 'advanced';
	$classes[] = 'content-product-advanced';
}
?>
<div <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<?php
			
			$image_size = '';

			if ( etheme_get_option( 'product_page_image_width' ) != '' && etheme_get_option( 'product_page_image_height' ) != '' ) {
				$image_size[] = etheme_get_option('product_page_image_width');
				$image_size[] = etheme_get_option('product_page_image_height');
			} else {
				$image_size = apply_filters( 'single_product_large_thumbnail_size', 'shop_catalog' );
			}

			$hoverUrl = '';

            if ( $hover == 'swap' ) {

            	$hoverUrl = etheme_get_custom_field( 'hover_img' );

            	if ( $hoverUrl != '' ) {
					$hoverImg = et_attach_id_from_src( $hoverUrl );
					$hoverImg = new_etheme_get_image( $hoverImg, $image_size );
					if ( $hoverImg == '' ) $hoverImg = '<img src=' . $hoverUrl . '>';
            	}

            }


		?>
		<?php if ($style == 'advanced'): ?>
			<div class="row-fluid">
				<div class="span6">
		<?php endif ?>
		<div class="product-image-wrapper hover-effect-<?php if ( has_post_thumbnail() || $hover == 'swap' ) echo $hover; ?>">
			<?php etheme_wc_product_labels(); ?>

	        <?php
				if ( !$product->is_in_stock() && etheme_get_option('out_of_label')):
	         ?>
	         	<span class="out-of-stock"><?php _e('Sold', ETHEME_DOMAIN) ?></span>
			<?php endif ?>

			<?php if (has_post_thumbnail()): ?>
				<?php
					$img_id = get_post_thumbnail_id($post->ID);
					$alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true);

					$effect = array( 'class' => '', 'extra' => '' );

					switch ( $hover ) {
						case 'slider':
							$effect['class'] = 'imageSlider';
							$effect['extra'] .= wp_get_attachment_image_url( $img_id, $image_size ) . ', ';
						
							$attachment_ids = $product->get_gallery_attachment_ids();

							foreach ( $attachment_ids as $ids ) {
								$effect['extra'] .= wp_get_attachment_image_url( $ids, $image_size ) . ', ';
							}

							$effect['extra'] = trim( $effect['extra'], ', ' );
							$effect['extra'] = 'data-images-list="' . $effect['extra'] . '"';
							break;

						case 'tooltip':
							$effect['class'] = 'imageTooltip';
							break;

						case 'swap':
							$effect['class'] = ( $hoverUrl != '' ) ? 'with-hover' : '';
							break;
					}

				 ?>

				<a href="<?php the_permalink(); ?>" class="product-content-image <?php echo $effect['class']; ?>" <?php echo $effect['extra']; ?>>
					<?php
						if ( $hoverUrl != '' ) echo $hoverImg;
						$hide_image = ( $hoverUrl != '' ) ? 'hide-image ' : '';
						echo wp_get_attachment_image( $img_id, $image_size, 0 , $attr = array( 'class' => $hide_image.'main-image', 'alt' => $alt_text, ) );
					?>

				</a>
			<?php else: ?>
				<a href="<?php the_permalink(); ?>" class="product-content-image">
					<?php if ( $hoverUrl != '' ) echo $hoverImg; ?>
					<?php echo wc_placeholder_img( $image_size ) ?>
				</a>
			<?php endif ?>


			<?php if ($hover == 'description'): ?>
				<div class="product-mask">
					<div class="mask-text">
						<h4><?php _e('Product description', ETHEME_DOMAIN) ?></h4>
						<?php echo trunc(get_the_excerpt(), etheme_get_option('descr_length')) ?>
						<p><a href="<?php the_permalink(); ?>" class="read-more-link button"><?php _e('Read More', ETHEME_DOMAIN); ?></a></p>
					</div>
				</div>
			<?php endif ?>

			<?php if (etheme_get_option('quick_view')): ?>
				<span class="show-quickly" data-prodid="<?php echo $post->ID;?>" style="font-size:11px; cursor: pointer;"><?php _e('Quick View', ETHEME_DOMAIN) ?></span>
			<?php endif ?>
		</div>

		<?php if ($style == 'advanced'): ?>
		        </div>
				<div class="span6">
		<?php endif ?>

		<?php if (etheme_get_option('product_page_productname')): ?>
			<h3 class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php endif ?>

		<?php if (etheme_get_option('product_page_cats')): ?>
			<div class="products-page-cats">
				<?php
					$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
					echo $product->get_categories( ', ' );
				?>
			</div>
		<?php endif ?>


		<?php woocommerce_template_loop_rating(); ?>

		<div class="product-excerpt">
			<?php echo do_shortcode(get_the_excerpt()); ?>
		</div>

		<div class="add-to-container">

			<?php
				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_price - 10
				 */
				if (etheme_get_option('product_page_price')) {
					do_action( 'woocommerce_after_shop_loop_item_title' );
				}
			?>

	        <?php
	        	if (etheme_get_option('product_page_addtocart')) {
	        		do_action( 'woocommerce_after_shop_loop_item' );
	        	}
	        ?>
        </div>



		<?php if ($style == 'advanced'): ?>
				</div>
			</div>
		<?php endif ?>
	<div class="clear"></div>
</div>
