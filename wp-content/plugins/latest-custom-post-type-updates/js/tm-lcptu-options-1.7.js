jQuery(document).ready(function($) {

    // Get the taxonomies and update them
    $(document).on('change', 'input.tm_lcptu_post_type', function(event) {
            var myTypes = [];
            var parentId = '#' + $(this).parents('div.widget').attr('id');
            $(parentId + ' input.tm_lcptu_post_type').filter(':checked').each(function(i) { myTypes[i] = $(this).val(); });
            var data = {
                action: 'tm_lcptu_tax',
                posttype: myTypes,
                fieldName: $(parentId + ' .taxonomies-field-name').val()
            };
            $.post(ajaxurl, data,
                function(response) {
                    $(parentId + ' span.taxonomy-list').html(response);
                    $(parentId + ' div.' + 'taxonomy-terms-container').slideUp(100);
                }
            );
        }
    );

    // Get the tags/categories and update them
    $(document).on('change', 'input.tm_lcptu_taxonomy_checkbox', function(event) {
            var myTypes = [];
            var parentId = '#' + $(this).parents('div.widget').attr('id');
            var taxonomy = $(this).val();
            var myContainer = $(parentId + ' .tm_lcptu_terms-' + taxonomy);
            if(myContainer.length == 0) {
                // Get container!
                var data = {
                    action: 'tm_lcptu_terms',
                    taxonomy: taxonomy,
                    fieldName: $(parentId + ' .terms-field-name').val()
                };
                $.post(ajaxurl, data,
                    function(response) {
                        $(parentId + ' div.taxonomy-terms-container').append(response);
                    }
                );
            }
            else {
                myContainer.slideUp(100).remove();
            }
        }
    );

    // Allow our sections to be shown/hidden as desired
    $(document).on('click', 'a.tm_lcptu_show-hide', function(e) {
        e.preventDefault();
        my_id = $(this).attr('id');
        my_div = $('div.' + my_id);
        my_div.slideToggle(100);
    });

    // Allow our advanced option details to be shown/hidden
    $(document).on('change', 'input.tm_lcptu_toggle_options', function(e) {
        my_id = $(this).attr('id');
        my_div = $('div.' + my_id);
        my_div.slideToggle(100);
    });

    // Allow us to have custom stuff!
    $(document).on('change', 'select.tm_lcptu_dropdown_custom', function(e) {
        my_id = $(this).attr('id');
        my_div = $('div.' + my_id);
        my_value = $(this).val();
        if(my_value == 'CUSTOM' || my_value == 'meta_value' || my_value == 'meta_value_num')
            my_div.slideDown(100);
        else
            my_div.slideUp(100);
    });
});
