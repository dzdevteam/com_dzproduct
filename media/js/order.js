// Setup jQuery cookie
jQuery.cookie.defaults.path = '/';
jQuery.cookie.json = true;

jQuery(document).ready(function(){
    jQuery(".btn-order").each(function(){
        var cart = jQuery.cookie('cart');
        var button = this;
        // Display an input for quantity
        if (jQuery(this).data('is-item-view') === true) {
            var $input_wrapper = jQuery('<div class="input-append"><button class="btn btn-up"><i class="icon-chevron-up"></i></button><button class="btn btn-down"><i class="icon-chevron-down"></i></button></div>');
            var $input = jQuery('<input type="text" value="1" class="disabled input-mini" readonly="true"/>');
            $input_wrapper.prepend($input).insertBefore(this);
            jQuery('.btn-up', $input_wrapper).on('click', function() {
                var value = parseInt($input.val());
                if (value === NaN)
                    value = 0;
                value++
                $input.val(value);
            });
            jQuery('.btn-down', $input_wrapper).on('click', function() {
                var value = parseInt($input.val());
                if (value === NaN)
                    value = 1;
                else if (value > 1)
                    value--;
                $input.val(value);
            });
            
            // Attach the input reference into the button
            jQuery(button).data('input', $input);
        }
        if (typeof cart !== 'undefined') {
            var id = jQuery(this).data('info').id;
            if (cart.hasOwnProperty(id)) {
                jQuery(this).off('click').text('Ordered').addClass('disabled');
                if (typeof $input !== 'undefined')
                    $input.val(cart[id].quantity);
            }
        }
    }).on('click', function() {
        var info = jQuery(this).data('info');
        var cart = jQuery.cookie('cart');
        if (typeof cart === 'undefined') {
            cart = new Object();
        }
        if (!cart.hasOwnProperty(info.id)) {
            cart[info.id] = info;
            if (jQuery(this).data('is-item-view') === true) {
                cart[info.id].quantity = jQuery(this).data('input').val();
            }
            jQuery.cookie('cart', cart);
            jQuery(document).trigger('cart-updated'); // Notify others about the changes
        }
        // Do not add this to the cart again
        jQuery(this).off('click').text('Ordered').addClass('disabled');
    });
})