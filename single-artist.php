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

					/*
					*  Query Products for a pa_version-short => original-like attributes.
					*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
					*/
				
					global $product, $woocommerce_loop;
					$title = get_the_title();

					$args = apply_filters('woocommerce_related_products_args', array(
						// 'relation' => 'AND',
						'post_type'				=> 'product',
						'ignore_sticky_posts'	=> 1,
						'no_found_rows' 		=> 1,
						'posts_per_page' 	    => 30,
						'post__not_in'			=> array($product->id),
						'post_status'      => 'publish',
						'tax_query' => array(
							// 'relation' =>  'OR',
							 array(
						    'taxonomy' => 'pa_version-short',
						    'field' => 'slug',
						    'terms' => array('Original Artwork', 'Original Artwork on Paper')
							)
            ),
						'meta_query' => array(
							array(
								'key' => 'provider', // name of custom field
								'value' => '"' . get_the_ID() . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
								'compare' => 'LIKE'
							)
						)
					));

					$the_query = new WP_Query( $args );
					if(isset($the_query->posts) && !empty($the_query->posts)){
						echo '<span style="display: none;" id="limited">';
						foreach( $the_query->posts as $post) {
							echo $post->ID . ',';
						}
						echo '</span>';
					}

					wp_reset_postdata(); 

					$args = apply_filters('woocommerce_related_products_args', array(
						'relation' => 'AND',
						'post_type'				=> 'product',
						'ignore_sticky_posts'	=> 1,
						'no_found_rows' 		=> 1,
						'posts_per_page' 	    => 30,
						'post__not_in'			=> array($product->id),
						'tax_query' => array(
							// 'relation' =>  'OR',
							 array(
						    'taxonomy' => 'pa_version-short',
						    'field' => 'slug',
						    'terms' => array('Original Artwork', 'Original Artwork on Paper'),
						    'operator' => 'NOT IN',
							)
            ),
						'meta_query' => array(
							array(
								'key' => 'provider', // name of custom field
								'value' => '"' . get_the_ID() . '"',
								'compare' => 'LIKE'
							)
						)
					));

					$the_query = new WP_Query( $args );
					if(isset($the_query->posts) && !empty($the_query->posts)){
						echo '<span style="display: none;" id="original">';
						foreach( $the_query->posts as $post) {
							echo $post->ID . ',';
						}
						echo '</span>';
					}

					wp_reset_postdata(); ?>

					<?php

						$args = apply_filters('woocommerce_related_products_args', array(
							'relation' => 'AND',
							'post_type'				=> 'product',
							'ignore_sticky_posts'	=> 1,
							'no_found_rows' 		=> 1,
							'posts_per_page' 	    => 30,
							'post__not_in'			=> array($product->id),
							'meta_query' => array(
								array(
									'key' => 'provider', // name of custom field
									'value' => '"' . get_the_ID() . '"',
									'compare' => 'LIKE'
								)
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
						var select = '<select id="filter" style="margin: 0px 0 25px 15px;""><option>All</option><option value="original">Original</option><option value="limited">Limited Edition</option></select>';
						jQuery('.items-slider').before(select);

						jQuery('#filter').on('change', function () {
							jQuery("div[class*='post-']").show(); // show any hidden products
							var selected = jQuery(this).val();
							var hidden = jQuery('span#' + selected).text().split(',').slice(0,-1);
							for(var i = 0; i < hidden.length; i++){
								jQuery('.post-' + hidden[i]).hide();
							}
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