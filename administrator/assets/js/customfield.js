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
            var $container  = jQuery('#jform_' + container_name),
                $loader     = jQuery('#jform_' + container_name + '-loader'),
                loaded      = false;
            setTimeout(function() {
                if (loaded == false) {
                    $container.hide();
                    $loader.show();
                }
            }, 200);
            jQuery.get('index.php?option=com_dzproduct&task=getFieldsJSON&catid=' + jQuery(this).val() + '&itemid=' + itemid)
            .done(function(data) {
                var fields = jQuery.parseJSON(data);
                if (fields.length == 0) {
                    $container.html("<div class=\"alert alert-info alert-block\"><h4>No fields here!</h4>Either this category hasn't been associated to any fields group yet, or the associated fields group doesn't have any custom fields!</div>");
                } else {
                    $container.html('');
                    for (var i = 0; i < fields.length; i++) {
                        $container.append(fn_generateInput(fields[i].id, fields[i].name, fields[i].value));
                    }
                }
            }).fail(function(){
                $container.html('<div class="alert alert-error alert-block"><h4>Error!</h4>Can not get custom fields associated to this category!</div>');
            }).always(function(){
                loaded = true;
                $loader.hide();
                $container.show();
            });
        });
        $controller.trigger('change');
    });
}