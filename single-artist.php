<?php
/**
 * The Template for displaying a single artist.
 *
 */

	get_header();
?>
<div class="page-heading bc-type-<?php etheme_option('breadcrumb_type'); ?>">
	<div class="container">
		<div class="row-fluid">
			<div class="span12 a-center">
				<h1 class="title"><span><?php the_title(); ?></span></h1>
				<?php etheme_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="page-content sidebar-position-without">
		<div class="row">
			<div class="content span12">
				<div class="artist-filters">
					<input type="checkbox" id="afa-filter-out-of-stock"><label for="afa-filter-out-of-stock">Hide Sold Items</label>
				</div>

       	<?php if ( have_posts() ) : ?>
					
				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						// get the query vars from functions.php
						$provider = get_the_ID();
						$args = apply_filters('woocommerce_related_products_args', adelman_get_slider_args_for_provider($provider));
						
						$slider_args = array(
							'title' => 'Artist Work',
							'shop_link' => false,
							'slider_type' => 'grid',
							'items' => '[[0, 1], [479,2], [619,2], [768,4], [1200, 6], [1600, 6]]',
							'style' => 'default',
							'block_id' => false
						);

						etheme_create_slider($args, $slider_args);

						wp_reset_postdata();

					?>
					
					<script>
						jQuery('document').ready(function(){

							// Toggle "Sold" visibility
							jQuery('#afa-filter-out-of-stock').on('change', function(){
								jQuery('.product.outofstock').toggle(!jQuery(this).is(':checked'));
							});
						});
					</script>
		      <div class="portfolio-single-item">
			      <h3 class="title"><span>About the Artist</span></h3>
						<?php the_content(); ?>
		      </div> 
				
				<?php endwhile; // End the loop. Whew. ?>
					
				<?php else: ?>

					<h3><?php _e('No pages were found!', ETHEME_DOMAIN) ?></h3>

				<?php endif; ?>
				<div class="clear"></div>

				<!-- Display Comments -->

			</div>
		</div>

	</div>
</div>
	
<?php
	get_footer();
?>