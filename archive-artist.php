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
        <h1 class="title"><span>Artists</span></h1>
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
      </div>

      <?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
        <div class="<?php echo $sidebar_span; ?> sidebar sidebar-right">
          <?php etheme_get_sidebar($sidebarname); ?>
        </div>
      <?php endif; ?>
    </div>


  </div>
</div>

<?php
  get_footer();
?>