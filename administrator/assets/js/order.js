jQuery(document).ready(function(){
    // Add item button
    jQuery('#btn-add-item').on('click', function() {
        var id = jQuery('#jform_items').val();
        var index = 0;
        jQuery.ajax(
            'index.php?option=com_dzproduct&task=items.item&format=JSON&id=' + id,
            {
                success: function(data, textStatus, jqXHR) {
                    if (!jQuery.isEmptyObject(data)) {
                        // Generate new order item row
                        index = index + 1;
                        prefix = 'jform[ordered][new' + index + ']';
                        var html = '<tr class="new' + index + '">';
                        
                        // The title
                        html += '<td class="item-title">';
                        html += data.title;
                        html += '<input type="hidden" name="' + prefix + '[id]" value="0" />';
                        html += "<input type='hidden' name='" + prefix + "[title]' value='" + data.title + "' />";
                        html += '</td>';
                        
                        // The image
                        html += '<td class="item-image">';
                        html += '<img src="' + Joomla.rootUrl + data.image + '" />';
                        html += '<input type="hidden" name="' + prefix + '[image]" value="' + data.image + '" />';
                        html += '</td>';
                        
                        // The description
                        html += '<td class="item-description">';
                        html += data.description;
                        html += "<input type='hidden' name='" + prefix + "[description]' value='" + data.description + "' />";
                        html += '</td>';
                        
                        // The price
                        html += '<td class="item-price" contenteditable="true">';
                        html += data.price;
                        html += "<input type='hidden' name='" + prefix + "[price]' value='" + data.price + "' />";
                        html += '</td>';
                        
                        // The quantity
                        html += '<td class="item-quantity" contenteditable="true">';
                        var quantity = parseInt(jQuery('#input-item-quantity').val());
                        if (quantity < 1) 
                            quantity = 1;
                        html += quantity;
                        html += "<input type='hidden' name='" + prefix + "[quantity]' value='" + quantity + "' />";
                        html += '</td>';
                        
                        // End row
                        html += '</tr>';
                        
                        // Append row into the table
                        jQuery('table#ordered > tbody').append(html);
                        
                        // Recalculate total price
                        update_price();
                        
                        // Re-attach handler to newly created cells
                        jQuery('tr.new' + index + ' > td[contenteditable="true"]').each(function(){contenteditable_init(this)});
                    }
                }
            });
        
    });
    
    // Prevent line break in quantity and price fields
    var contenteditable_init = function(field) {
        jQuery(field).keypress(function(e){ 
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /[0-9]|\./;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        });
        jQuery(field).keyup(function(e){
            jQuery(this).trigger('change');
        });
        jQuery(field).on('change', function(){
            jQuery('input', this).val(jQuery(this).text());
            update_price();
        });
    }
    
    // Update total price
    var update_price = function() {
        var prices = jQuery(".item-price");
        var quantities = jQuery(".item-quantity");
        var total = 0;
        for (var i = 0; i < prices.length; i++) {
            total += parseInt(prices[i].innerText) * parseInt(quantities[i].innerText);
        }
        jQuery('#total-price').text(total);
    }
    
    // Start up
    jQuery('td[contenteditable="true"]').each(function(){contenteditable_init(this)});
    update_price();
});