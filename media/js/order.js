// Setup jQuery cookie
jQuery.cookie.defaults.path = '/';
jQuery.cookie.json = true;

jQuery(document).ready(function(){
    jQuery(".btn-order").on('click', function() {
        var info = jQuery(this).data('info');
        var cart = jQuery.cookie('cart');
        if (typeof cart === 'undefined') {
            cart = new Object();
            cart[info.id] = info;
            jQuery.cookie('cart', cart);
        } else {
            if (!cart.hasOwnProperty(info.id)) {
                cart[info.id] = info;
                jQuery.cookie('cart', cart);
                jQuery(document).trigger('cart-updated'); // Notify others about the changes
            }
        }
        // Do not add this to the cart again
        jQuery(this).off('click').text('Ordered').addClass('disabled');
    }).each(function(){
        var cart = jQuery.cookie('cart');
        if (typeof cart !== 'undefined') {
            if (cart.hasOwnProperty(jQuery(this).data('info').id)) {
                jQuery(this).off('click').text('Ordered').addClass('disabled');
            }
        }
    });
})