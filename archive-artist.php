<?php 
/* Template for Artists archive */


  get_header();
?>


<?php 
  extract(etheme_get_blog_sidebar());
  $postspage_id = get_option('page_for_posts');
?>

<div class="page-heading bc-type-<?php etheme_option('breadcrumb_type'); ?>">
  <div class="container">
    <div class="row-fluid">
      <div class="span12 a-center">
        <h1 class="title"><span><?php echo 'Artists'; ?></span></h1>
        <?php //etheme_breadcrumbs(); ?>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="page-content sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">
    <div class="row">
      <?php if($position == 'left' || ($responsive == 'top' && $position == 'right')): ?>
        <div class="<?php echo $sidebar_span; ?> sidebar sidebar-left">
          <?php etheme_get_sidebar($sidebarname); ?>
        </div>
      <?php endif; ?>

      <div class="content <?php echo $content_span; ?>">
        <div class="blog-masonry row">

          <?php if(have_posts()): while(have_posts()) : the_post(); ?>

              <?php get_template_part('content', 'artists'); ?>

          <?php endwhile; ?>

        </div>


        <?php else: ?>

          <h1><?php _e('No posts were found!', ETHEME_DOMAIN) ?></h1>

        <?php endif; ?>

        <div class="articles-nav">
          <div class="left"><?php next_posts_link(__('&larr; Older Posts', ETHEME_DOMAIN)); ?></div>
          <div class="right"><?php previous_posts_link(__('Newer Posts &rarr;', ETHEME_DOMAIN)); ?></div>
          <div class="clear"></div>
        </div>

      </div>

      <?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
        <div class="<?php echo $sidebar_span; ?> sidebar sidebar-right">
          <?php etheme_get_sidebar($sidebarname); ?>
        </div>
      <?php endif; ?>
    </div>


  </div>
</div>
<script>
jQuery('document').ready(function(){
  jQuery('.icon-resize-full').on('click', function(){
    var node = jQuery(this).closest('header.artist-header').next('.post-information');
    var product = jQuery(this).closest('.post-images').prev('header.artist-header').find('.product-link').html();
    var artist = ' by ' + jQuery(this).closest('.post-images').prev('header.artist-header').find('.post-title').html();
    var caption = product + artist;
    setTimeout(function(){ jQuery('.mfp-title').html(caption).fadeIn(); }, 500);
  });
});
</script>

  
<?php
  get_footer();
?>