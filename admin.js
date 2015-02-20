// To change menu order of products
// 0 for new, 1 for not new
jQuery(document).ready(function(){
  jQuery('select#product_new').on('change', function(){
    if (jQuery(this).val() === 'enable'){
      jQuery('input#menu_order').val(0);
    }
    if (jQuery(this).val() === 'disable' || jQuery(this).val() === 'disable'){
      jQuery('input#menu_order').val(1);
    }
  })
});