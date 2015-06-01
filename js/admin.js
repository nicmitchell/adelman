jQuery(document).ready(function(){
  // To change menu order of products
  // 0 for new, 1 for not new
  jQuery('select#product_new').on('change', function(){
    if (jQuery(this).val() === 'enable'){
      jQuery('input#menu_order').val(0);
    }
    if (jQuery(this).val() === 'disable' || jQuery(this).val() === 'disable'){
      jQuery('input#menu_order').val(1);
    }
  });

  jQuery('#acf-sold_date').focusout(function(){
    window.setTimeout(function(){
      var date = jQuery('#acf-sold_date').find('input[type="hidden"]').val();
      console.log('date', date);
      // If a sold date is set, set dropdown to "Out of Stock"
      // If a sold date is not, set dropdown to "In Stock"
      if (date){
        jQuery('#inventory_product_data option[value="instock"]').prop('selected', false);
        jQuery('#inventory_product_data option[value="outofstock"]').prop('selected', 'selected');
      } else {
        jQuery('#inventory_product_data option[value="outofstock"]').prop('selected', false);
        jQuery('#inventory_product_data option[value="instock"]').prop('selected', 'selected');
      }
    }, 100);
  });
});