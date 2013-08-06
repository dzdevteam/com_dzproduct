jQuery(document).ready(function(){
    jQuery('.field_multilang').each(function(){
        var $that = jQuery(this), 
            $root = jQuery('.field_root', $that),
            // Parse root data
            data  = jQuery.parseJSON(jQuery('.field_root', $that).val());
        
        // Make data the container for our fields
        if (data == null)
            data = new Object();
        
        jQuery('.field_child', $that).each(function(){
            var $child = jQuery(this),
                // Get the lang of current field
                lang = $child.data('lang');
                
            // Put the stored value into this field
            if (data.hasOwnProperty(lang))
                $child.val(decodeURIComponent(data[lang]));
            // On field change
            $child.on('change', function() {
                // Update data in the container
                data[lang] = encodeURIComponent($child.val());
                
                // Put data into root field
                $root.val(JSON.stringify(data));
            });
            
            // Trigger first change
            $child.trigger('change');
        })
    });
});