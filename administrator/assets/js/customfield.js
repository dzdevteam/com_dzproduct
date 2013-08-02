function customFieldSetup(container_name, controller_name, itemid) {
    jQuery(document).ready(function(){
        var $controller = jQuery('#jform_'+controller_name), fn_generateInput;
        
        fn_generateInput = function(fieldid, name, value) {
            var group = '<div class="control-group" />',
                label = '<label class="control-label" for="jform_' + container_name + '_' + fieldid + '">' + name + '</label>',
                control = '<div class="controls" />',
                input = '<input type="text" name="jform[' + container_name + '][' + fieldid + ']" id="jform_' + container_name + '_' + fieldid + '" value="' + ((value == null) ? "" : value) + '"/>';               
            
            return jQuery(group).append(jQuery(label)).append(jQuery(control).append(jQuery(input)));
        };
        $controller.on('change', function(){
            jQuery.get('index.php?option=com_dzproduct&task=getFieldsJSON&catid=' + jQuery(this).val() + '&itemid=' + itemid)
            .done(function(data) {
                var $container  = jQuery('#jform_' + container_name),
                    fields      = jQuery.parseJSON(data);
                $container.html('');
                for (var i = 0; i < fields.length; i++) {
                    $container.append(fn_generateInput(fields[i].id, fields[i].name, fields[i].value));
                }
            }).fail(function(){
                // Failed handling
            });
        });
        $controller.trigger('change');
    });
}