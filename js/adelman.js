// Close Pretty Photo modal window on click
jQuery(function () {
  if(jQuery("a[rel^='prettyPhoto']").length){
    jQuery("a[rel^='pp_pic_holder']").prettyPhoto();
    jQuery('.pp_pic_holder').live('click', function() {
      console.log('PP click called');
      jQuery.prettyPhoto.close();
      return false;
    });
  }
});

// Product title in lightbox
jQuery(document).ready(function(){
  jQuery('.main-image-slider a').on('click', function(){
    var title = jQuery('h1.product_title span').text();
    var artist = jQuery('span.artist_name_wrapper a').text();
    jQuery('p.pp_description').text(title + ' - ' + artist).css('display', 'block');
  });
});