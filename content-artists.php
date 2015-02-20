<?php
/**
*	Template for Artists grid layout
*/

?>

<?php 
	$postId = get_the_ID();
	$lightbox = 1;//etheme_get_option('blog_lightbox');
	$postClass = 'blog-post post-grid span4';
	$width = 500;//etheme_get_option('blog_page_image_width');
	$height = 500;//etheme_get_option('blog_page_image_height');
	$crop = etheme_get_option('blog_page_image_cropping');
	$product = (get_field('linked_product')) ? array_shift(get_field('linked_product')) : false;
	$args = apply_filters('woocommerce_related_products_args', array(
		'relation' => 'AND',
		'post_type'				=> 'product',
		'ignore_sticky_posts'	=> 1,
		'no_found_rows' 		=> 1,
		'numberposts' 	    => 4,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_status'      => 'publish',
		'meta_query' => array(
			array(
				'key' => 'provider', // name of custom field
				'value' => '"' . $postId . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
				'compare' => 'LIKE'
			)
		)
	));
	$products = get_posts( $args );
?>


<article <?php post_class($postClass); ?> id="post-<?php the_ID(); ?>" >
	<header class="artist-header">
		<h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<span class="posted-on">Artwork shown: </span>
		<span class="product-link"><a href="<?php echo get_permalink($product->ID); ?>"><?php echo $product->post_title; ?></a></span>
	</header>

	<?php $image = etheme_get_image(false, $width,$height,$crop); ?>

	<?php if (has_post_thumbnail()): ?>
		<div class="post-images">
			<a href="<?php the_permalink(); ?>"><img src="<?php echo $image; ?>"></a>
			<div class="blog-mask">
				<div class="mask-content">
					<?php if($lightbox): ?><a href="<?php echo etheme_get_image(get_post_thumbnail_id($postId)); ?>" rel="lightbox"><i class="icon-resize-full"></i></a><?php endif; ?>
					<a href="<?php the_permalink(); ?>"><i class="icon-link"></i></a>
				</div>
			</div>
		</div>	
	<?php endif ?>	
	<div class="post-information <?php if (!has_post_thumbnail()): ?>border-top<?php endif ?>">
		
		<div class="post-info">
			<div class="artist-thumbnails post-images">
				<?php foreach ( $products as $product ) : ?>
					<figure class="artist-thumbnail">
						<a href="<?php echo get_permalink($product->ID); ?>">
							<?php 
								$thumb_atts = array(	
									'alt'	=> trim( strip_tags( $product->post_excerpt ) ),
									'title'	=> trim( strip_tags( $product->post_title ) )
								); 
								echo get_the_post_thumbnail( $product->ID, 'thumbnail', $thumb_atts); ?>
						</a>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="post-description"><?php //the_content('<span class="button center read-more">'.__('Read More', ETHEME_DOMAIN).'</span>'); ?></div>

		<div class="clear"></div>
		
	</div>

</article>