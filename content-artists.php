<?php
/**
*	Template for Artists grid layout
*/

?>

<?php 
	$postId = get_the_ID();
	$lightbox = 1;//etheme_get_option('blog_lightbox');
	$postClass = 'blog-post post-grid span4';
	$product = (get_post_meta($postId, 'linked_product')) ? array_shift(get_post_meta($postId, 'linked_product'))[0] : false;
?>

<article <?php post_class($postClass); ?> id="post-<?php the_ID(); ?>" >
	<header class="artist-header">
		<h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<span class="product-link" style="display:none">
			<a href="<?php echo get_permalink($product); ?>"><?php echo get_the_title($product); ?></a>
		</span>
	</header>

	<?php if (has_post_thumbnail()): ?>
		<?php $image = get_the_post_thumbnail_url($postId); ?>
		<div class="post-images main-image">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
			<div class="blog-mask">
				<div class="mask-content">
					<?php if($lightbox): ?><a href="<?php the_post_thumbnail_url(); ?>" rel="lightbox"><i class="icon-resize-full"></i></a><?php endif; ?>
					<a href="<?php the_permalink(); ?>"><i class="icon-link"></i></a>
				</div>
			</div>
		</div>	
	<?php endif ?>

</article>