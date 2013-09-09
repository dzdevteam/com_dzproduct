jQuery(document).ready(function(){
    jQuery('.customfield').each(function(){
        var fieldname = jQuery(this).data('fieldname'),
            controller_name = jQuery(this).data('controller'),
            itemid = jQuery(this).data('itemid'),
            $controller = jQuery('#jform_'+controller_name), 
            $container = jQuery('.form-container', this),
            $loader = jQuery('.img-loading', this),
            $btn_reload = jQuery('.btn-reload', this),
            fn_generateInput, fn_generateGroupLabel;
        
        fn_generateInput = function(fieldid, name, value) {
            var group = '<div class="control-group" />',
                label = '<label class="control-label" for="jform_' + fieldname + '_' + fieldid + '">' + name + '</label>',
                control = '<div class="controls" />',
                input = '<input type="text" name="jform[' + fieldname + '][' + fieldid + ']" id="jform_' + fieldname + '_' + fieldid + '" value="' + ((value == null) ? "" : value) + '"/>';               
            
            return jQuery(group).append(jQuery(label)).append(jQuery(control).append(jQuery(input)));
        }
        fn_generateGroupLabel = function(id, name) {
            var group = '<div class="control-group" />',
                label = '<label class="control-label"><strong>' + name + '</strong></label>',
                control = '<div class="controls" />',
                button = '<a class="btn btn-primary btn-group-edit" target="_blank" href="index.php?option=com_dzproduct&task=group.edit&id=' + id + '">' + Joomla.JText._('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_BTN_EDIT_GROUP') + '</a>';
            return jQuery(group).append(label).append(jQuery(control).append(button));
        }
        $controller.on('change', function(){
            var loaded      = false;
            setTimeout(function() {
                if (loaded == false) {
                    $container.hide();
                    $loader.show();
                }
            }, 200);
            jQuery.get('index.php?option=com_dzproduct&task=getFieldsJSON&catid=' + jQuery(this).val() + '&itemid=' + itemid)
            .done(function(data) {
                $container.html('');
                
                if (data.group != null) {
                    $container.append(fn_generateGroupLabel(data.group.id, data.group.name));
                }
                if (data.fields.length == 0) {
                    $container.html(Joomla.JText._('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_ERROR_NO_FIELDS'));
                } else {
                    for (var i = 0; i < data.fields.length; i++) {
                        $container.append(fn_generateInput(data.fields[i].id, data.fields[i].name, data.fields[i].value));
                    }
                }
            }).fail(function(){
                $container.html(Joomla.JText._('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_ERROR_LOAD_FIELDS'));
            }).always(function(){
                loaded = true;
                $loader.hide();
                $container.show();
            });
        });
        $controller.trigger('change');
        
        $btn_reload.on('click', function() {
            $controller.trigger('change');
            return false;
        });
    });
});
