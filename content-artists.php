<?php
/**
*	Template for Artists grid layout
*/

?>

<?php if (has_post_thumbnail()): ?>
	<?php $postClass = 'blog-post post-grid span4'; ?>
	<article <?php post_class($postClass); ?> id="post-<?php the_ID(); ?>" >
		<header class="artist-header">
			<h4 class="post-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h4>
		</header>
		<div class="post-images main-image">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
		</div>	
	</article>
<?php endif ?>
