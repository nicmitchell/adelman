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
				
					$all_jewelry = array(
						'womens' => ['image_count' => 0],
						'mens' => ['image_count' => 0]
					);

					// Get/set attribute IDs for women / men jewelry
					$gender_terms = get_terms("pa_gender");
					$womens_id = 0;
					$mens_id = 0;

					foreach ( $gender_terms as $term ):
						$gender = $term->slug;
						if (preg_match('/women/', $gender)):
							$womens_id = $term->term_id;
						elseif (preg_match('/men/', $gender)):
							$mens_id = $term->term_id;
						endif;
					endforeach;

					// Get attribute IDs for jewelry-type attribute
					$jewelry_type_terms = get_terms("pa_jewelry-type");

					// Make sure we are only showing the right Jeweler
					$brand = basename(get_permalink());

					// Get the ACF fields for the category images
					$fields = get_fields();
					foreach ($fields as $field_name => $field):
						$category = explode('_', $field_name);
						$gender = $category[1];
						$gender_id = ($gender === 'womens') ? $womens_id : $mens_id;
						$category_slug = $category[0];
						$term_id;
						
						// Get the category attibute id based on the slug
						foreach ( $jewelry_type_terms as $term ):
							if ($term->slug === $category_slug):
								$term_id = $term->term_id;
								break;
							endif;
						endforeach; 

						$jewelry = array(
							'id' => (isset($term_id)) ? $term_id : NULL,
							'gender_id' => $gender_id,
							'category' => $category_slug,
							'image' => $field['sizes']['medium'],
							'link' => get_site_url(). '/brand/'. $brand
						);

						if ($jewelry['gender_id']) $jewelry['link'] .= '?filtering=1&filter_gender='. $jewelry['gender_id'];
						if ($jewelry['id']) $jewelry['link'] .= '?filtering=1&filter_jewelry-type='. $jewelry['id'];

						// Split the jewelry_attrs array based on gender
						// Only add it if there is an image present
						if($gender === 'womens' && $jewelry['image']):
							$all_jewelry['womens'][$category_slug] = $jewelry;
							$all_jewelry['womens']['image_count']++;
						elseif($gender === 'mens' && $jewelry['image']):
							$all_jewelry['mens'][$category_slug] = $jewelry;
							$all_jewelry['mens']['image_count']++;
						endif;

					// End of looping through ACF fields
					endforeach;
					
					?>

					<div class="page-content sidebar-position-without">
					  <div class="row">
					    <div class="content span12">

					    	<?php foreach($all_jewelry as $gender => $values): ?>
					    		<?php if($values['image_count'] > 0): ?>

							      <div class="slider-container  posts-count-gt1">
							        <h2 class="title"><span><?php echo ucwords($gender); ?>'s Jewlery</span></h2>
							        <div class="items-slider products-slider grid-container slider-9853">
							          <div class="slider grid-wrapper">

													<?php foreach($values as $type => $jewelry): ?>
														<?php if ($jewelry['image']): ?>

									            <div class="slide-item product-slide grid-slide">
									              <div class="post-1234 product type-product status-publish has-post-thumbnail first sold-individually taxable shipping-taxable purchasable product-type-simple product-cat-paintings instock">
								                	<a href="<?php echo $jewelry['link']; ?>">
										                <div class="product-image-wrapper hover-effect-swap">
									                		<img src="<?php echo $jewelry['image']; ?>" style="width: 200px;">
										                </div>
										                </a>
										                <h3 class="product-name">
											                <a href="<?php echo $jewelry['link']; ?>"><?php echo ucwords($type); ?></a>
									                	</h3>
									                <div class="clear"></div>
									              </div>
									            </div>

									          <?php endif; ?>
								          <?php endforeach; ?>
							            <!-- slide-item -->
							          </div>
							          <!-- slider -->
							        </div>
							        <!-- products-slider -->
							      </div>
							    <?php endif; ?>
						      <!-- slider-container -->
					      <?php endforeach; ?>
					      <div class="clear"></div>
					    </div>
					  </div>
					</div>

		      <div class="portfolio-single-item">
		      	<h3 class="title"><span>About the Jeweler</span></h3>
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