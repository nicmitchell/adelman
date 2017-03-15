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

       	<?php if ( have_posts() ) : ?>
					
				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						global $product;

						// Combined original and limited edition works
						$args = apply_filters('woocommerce_related_products_args', array(
							'relation' => 'AND',
							'post_type' => 'product',
							'post_status' => 'publish',
							'ignore_sticky_posts'	=> 1,
							'no_found_rows' => 1,
							'posts_per_page' => 100,
							'post__not_in' => array($product->id),
							'meta_query' => array(
								array(
									'relation' => 'AND',
									'sold_clause' => array(
								    'key' => '_stock_status'
									),
									'new_clause' => array(
								    'key' => 'product_new'
									),
									'provider_clause' => array(
										'key' => 'provider',
										'value' => '"' . get_the_ID() . '"',
										'compare' => 'LIKE',	
									)
								)
							),
							'orderby' => array(
								'sold_clause' => 'ASC',
								'new_clause' => 'DESC'
							)
						));

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
						var filters = 
						'<div class="artist-filters">' +
							'<select id="afa-edition-filter" style="margin: 0px 15px 25px 15px;"><option>All</option><option value="original">Original</option><option value="limited">Limited Edition</option></select>' + 
							'<input type="checkbox" id="afa-filter-out-of-stock"><label for="afa-filter-out-of-stock">Hide Sold Items</label>'+
						'</div>';
						jQuery('.items-slider').before(filters);

						jQuery('#afa-edition-filter').on('change', function () {
							jQuery("div[class*='post-']").show(); // show any hidden products
							var selected = jQuery(this).val();
							var hidden = jQuery('span#' + selected).text().split(',').slice(0,-1);
							for(var i = 0; i < hidden.length; i++){
								jQuery('.post-' + hidden[i]).hide();
							}
							jQuery('.slide-item .outofstock').toggle(!jQuery('#afa-filter-out-of-stock').is(':checked'));
						});
						jQuery('#afa-filter-out-of-stock').on('change', function(){
							jQuery('.slide-item .outofstock').toggle(!jQuery(this).is(':checked'));
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