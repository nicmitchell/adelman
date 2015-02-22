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
				
					$title = get_the_title();

					// UNO de 50
					if(strtolower($title) === "unode50"):
						$rings = array( 'image' => '', 'link' => '');
						$earrings = array( 'image' => 'http://d55.067.myftpupload.com/wp-content/uploads/2015/02/UNO_PEN0399VRDMTL0U_ISpyWithMyLittleEye.jpg', 'link' => '../product/i-spy-with-my-little-eye-2/');
						$necklaces = array( 'image' => '', 'link' => '');
						$bracelets = array( 'image' => 'http://e09.478.myftpupload.com/wp-content/uploads/2015/02/Journey-Bracelet-in-metal-mix-characteristic-of-Uno-de-50-coated-in-15micro-silver-150x150.jpg', 'link' => 'http://bing.com');
						$cuffs = array( 'image' => '', 'link' => '');
						$bangles = array( 'image' => '', 'link' => '');
					endif;

					// Melinda Maria
					if(strtolower($title) === "melinda maria"):
						$rings = array( 'image' => 'http://d55.067.myftpupload.com/wp-content/uploads/2015/02/MM_R5022GWT7_MonroeRing.jpg', 'link' => '');
						$earrings = array( 'image' => '', 'link' => '');
						$necklaces = array( 'image' => '', 'link' => '');
						$bracelets = array( 'image' => '', 'link' => '');
						$cuffs = array( 'image' => '', 'link' => '');
						$bangles = array( 'image' => '', 'link' => '');
					endif;


					$womens = array(
						'rings' => array(
							'title' => 'Rings',
							'image' => $rings['image'],
							'link' => $rings['link']
						),
						'earrings' => array(
							'title' => 'Earrings',
							'image' => $earrings['image'],
							'link' => $earrings['link']
						),
						'necklaces' => array(
							'title' => 'Necklaces',
							'image' => $necklaces['image'],
							'link' => $necklaces['link']
						),
						'bracelets' => array(
							'title' => 'Bracelets',
							'image' => $bracelets['image'],
							'link' => $bracelets['link']
						),
						'cuffs' => array(
							'title' => 'Cuffs',
							'image' => $cuffs['image'],
							'link' => $cuffs['link']
						),
						'bangles' => array(
							'title' => 'Bangles',
							'image' => $bangles['image'],
							'link' => $bangles['link']
						),
					);

					// $mens = array(
					// 	'bracelets' => array(
					// 		'title' => 'Bracelets',
					// 		'image' => '',
					// 		'link' => ''
					// 	),
					// 	'rings' => array(
					// 		'title' => 'Rings',
					// 		'image' => '',
					// 		'link' => ''
					// 	)
					// );

					?>
					<?php foreach ($womens as $type => $jewlery): ?>
						<?php if ($jewlery['image'] && $jewlery['link']): ?>
							<div class="page-content sidebar-position-without">
							  <div class="row">
							    <div class="content span12">
							      <div class="slider-container  posts-count-gt1">
							        <h2 class="title"><span>Women's Jewlery</span></h2>
							        <div class="items-slider products-slider grid-container slider-9853">
							          <div class="slider grid-wrapper">
							            <div class="slide-item product-slide grid-slide">
							              <div class="post-1234 product type-product status-publish has-post-thumbnail first sold-individually taxable shipping-taxable purchasable product-type-simple product-cat-paintings instock">
							                <div class="product-image-wrapper hover-effect-swap">
							                  <a href="<?php echo $jewlery['link']; ?>" class="product-content-image" data-images-list="<?php echo $jewlery['image']; ?>">
							                  <img src="<?php echo $jewlery['image']; ?>" class=" hide-image">
							                  </a>
							                </div>
							                <h3 class="product-name"><a href="<?php echo $jewlery['link']; ?>"><?php echo $jewlery['title']; ?></a></h3>
							                <div class="product-excerpt">
							                </div>
							                <div class="add-to-container">
							                </div>
							                <div class="clear"></div>
							              </div>
							            </div>
							            <!-- slide-item -->
							          </div>
							          <!-- slider -->
							        </div>
							        <!-- products-slider -->
							      </div>
							      <!-- slider-container -->
							      <div class="clear"></div>
							    </div>
							  </div>
							</div>
						<?php endif; ?>
					<? endforeach; ?>

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