// jQuery Cookie setup
jQuery.cookie.defaults.path = '/';
jQuery.cookie.json = true;

jQuery(document).ready(function(){
    // Attach handler to control the quantity for a row
    var quantity_control = function(row, id) {
        jQuery('.btn-decrease', row).on('click', function() {
            var cart = jQuery.cookie('cart');
            if (cart[id].quantity > 1) {
                cart[id].quantity--;
                jQuery('.item-quantity > span', row).text(cart[id].quantity);
                jQuery('.item-quantity > input', row).val(cart[id].quantity);
                jQuery.cookie('cart', cart);
                jQuery(document).trigger('cart-updated');
            }
        });
        jQuery('.btn-increase', row).on('click', function() {
            var cart = jQuery.cookie('cart');
            cart[id].quantity++;
            jQuery('.item-quantity > span', row).text(cart[id].quantity);
            jQuery('.item-quantity > input', row).val(cart[id].quantity);
            jQuery.cookie('cart', cart);
            jQuery(document).trigger('cart-updated');
        });
        jQuery('.btn-remove', row).on('click', function() {
            var cart = jQuery.cookie('cart');
            delete cart[id];
            jQuery(row).fadeOut(300, function() {jQuery(row).remove()});
            jQuery.cookie('cart', cart);
            jQuery(document).trigger('cart-updated');
        });
    }
    // Update total price
    var update_price = function() {
        var cart = jQuery.cookie('cart');
        var total, id;
        if (typeof cart !== 'undefined') {
            total = 0;
            for (var id in cart) {
                total += cart[id].price * cart[id].quantity;
            }
            jQuery('#total-price').text(total);
        }
    }
    jQuery(document).on('cart-updated', update_price);
    
    // Render products from the cart into table
    var cart = jQuery.cookie('cart');
    var row, id;
    if (typeof cart !== 'undefined') {
        for (id in cart) {
            row = '<tr>';
            
            row += '<td class="item-title">';
            row += cart[id].title;
            row += '<input name="jform[products][' + id + '][title]" value=\'' + cart[id].title + '\' type="hidden" />'
            row += '</td>';
            
            row += '<td class="item-image">';
            row += '<img src="' + Joomla.rootUrl + cart[id].image + '" />';
            row += '<input name="jform[products][' + id + '][image]" value=\'' + cart[id].image + '\' type="hidden" />'
            row += '</td>';
            
            row += '<td class="item-description">';
            row += cart[id].description;
            row += '<input name="jform[products][' + id + '][description]" value=\'' + cart[id].description + '\' type="hidden" />'
            row += '</td>';
            
            row += '<td class="item-price">';
            row += '<span>' + cart[id].price + '</span>';
            row += '<input name="jform[products][' + id + '][price]" value=\'' + cart[id].price + '\' type="hidden" />'
            row += '</td>';
            
            row += '<td class="item-quantity">';
            row += '<i class="icon-chevron-left btn-decrease"></i><span>' + cart[id].quantity + '</span><i class="icon-chevron-right btn-increase"></i>';
            row += '<button type="button" class="btn btn-link btn-remove">Remove</button>';
            row += '<input name="jform[products][' + id + '][quantity]" value=\'' + cart[id].quantity + '\' type="hidden" />'
            row += '</td>';
            
            row += '</tr>';
            row = jQuery(row); // Make this a DOM object
            
            quantity_control(row, id);
            
            jQuery('table#cart > tbody').append(row);
        }
    }
    
    // Send form through ajax
    var alert_tpl = '<div class="alert fade in"><a class="close" data-dismiss="alert" href="#">&times;</a></div>';
    var displayAlert = function(message, classname) {
        jQuery("#alert-area").html(jQuery(alert_tpl).addClass(classname).append(message));
    };
    var displayLoading = function() {
        jQuery("#alert-area").html('<img src="' + Joomla.loadingGIF + '" />');
        jQuery("#alert-area")[0].scrollIntoView();
    };
    var disable_handler = function() { return false; };
    
    // Validation for order form
    jQuery('#order-form').validate({
        submitHandler: function(form) {
            displayLoading();
            jQuery.ajax({
                type: form.method,
                url: form.action,
                data: jQuery(form).serialize(),
                        success: function(data) {
                            displayAlert(data.message, 'alert-success');
                            
                            // Prevent form from submitting again
                            jQuery(form).off('submit');
                            jQuery(form).on('submit', disable_handler);
                            jQuery('button', form).addClass('disabled');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            displayAlert(errorThrown, 'alert-danger');
                            Recaptcha.reload();
                        }
            });
        },
        rules: {
            "jform[email]": "email",
        },
        errorClass: "alert"
    });
    
    // Start up event
    jQuery(document).trigger('cart-updated');
});