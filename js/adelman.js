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
var showLightboxTitle = function(){ 
  jQuery('.main-image-slider a').on('click', function(){
    var title = jQuery('h1.product_title span').text();
    var artist = jQuery('span.artist_name_wrapper a').text();
    jQuery('p.pp_description').text(title + ' - ' + artist).css('display', 'block');
  });
};

// Toggle custom engraving input boxes 
var toggleCustomEngraving = function(){
  jQuery('#pa_custom-engraving').on('click', function(){
    jQuery(this).on('change', function(evt){
      var val = evt.currentTarget.value.split('-')[2];
      var engravings = jQuery('.product-addon-custom-engraving .form-row');
      engravings.hide().find('input').val('');
      engravings.find('input[maxlength=' + val + ']').closest('.form-row').show();
      jQuery('.product-addon-custom-engraving .addon-description').show();
    });
  });
};

// Disables right click on product pages
var disableProductRightClick = function() {
  jQuery('body').on('contextmenu', function(e){
    e.stopPropagation();
    var disallowed = ['mfp-img'];
    var current = e.target.classList.value;
    return !disallowed.includes(current);
  });

  jQuery('.main-image-slider').on('contextmenu', function(e){
    e.stopPropagation();
    return false;
  });
}

jQuery(document).ready(function(){
  showLightboxTitle();
  toggleCustomEngraving();
  disableProductRightClick();
});