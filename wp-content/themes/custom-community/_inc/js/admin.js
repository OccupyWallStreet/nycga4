(function($){
    $(document).ready(function(){
        $('.dismiss-activation-message,.cc-rate-it .go-to-wordpress-repo').click(function(){
            var message_block = $('.cc-rate-it');
            send_ajax_option_update(message_block, 'dismiss_activation_message');
        });
    
        $('.close').click(function(){
            var message_block = $('.slideshow_info');
            send_ajax_option_update(message_block, 'cc_dismiss_info_messages');
        });
        function send_ajax_option_update(message_block, action){
            $.ajax({
                url : admin_params.ajax_url,
                type: 'post',
                data : {
                    'action' : action, //'dismiss_activation_message',
                    'value' : 'yes'
                },
                success : function(data){
                    if(data){
                        message_block.hide() 
                    }
                } 
            })
        }
        //Hide Site Width option
        if(admin_params.responsive == "1"){
            $('#cap_website_width').parent().hide().prev().hide();
            $('#cap_leftsidebar_width').hide().prev().hide().prev().hide();
            $('#cap_rightsidebar_width').hide().prev().hide().prev().hide();
        }
    
        $('#cap_posts_lists_style_taxonomy, #cap_posts_lists_style_dates, #cap_posts_lists_style_author').change(function(){
            var have_block_view = false;
            $('#cap_posts_lists_style_taxonomy, #cap_posts_lists_style_dates, #cap_posts_lists_style_author').each(function(){
                if($(this).val() == admin_params.blog){
                    have_block_view = true;
                }
            });
            if(have_block_view){
                $('.blog-items').show().parent().next().show();
            } else {
                $('.blog-items').hide().parent().next().hide();
            }
        });
        $('#cap_posts_lists_style_home').change(function(){
            var have_block_view = false;
            if($(this).val() == admin_params.blog){
                have_block_view = true;
            }
            if(have_block_view){
                $('.blog-item-home, #cap_default_homepage_hide_avatar, #cap_default_homepage_style, #cap_default_homepage_hide_date').show().parent().parent('p').show()
            } else {
                $('.blog-item-home, #cap_default_homepage_hide_avatar, #cap_default_homepage_style, #cap_default_homepage_hide_date').hide().parent().parent('p').hide()
            }
        });
        $('#cap_posts_lists_style_taxonomy, #cap_posts_lists_style_dates, #cap_posts_lists_style_author, #cap_posts_lists_style_home').trigger('change');
        var texarea = document.getElementById("cap_overwrite_css");
        if(typeof CodeMirror != 'undefined' && texarea){
            var editor = CodeMirror.fromTextArea(texarea, {});
        }
    
        $('#cap_overwrite_css').focus(function(){
            $('#cap_overwrite_css').elastic();
        });
    
        $('#cc_page_slider_post_type').live('blur', function(){
            var value = $.trim($(this).val());
            if(value){
                var category_block = $('#categories-set');
                if($(this).val() != 'post'){
                    category_block.hide().find(':checkbox').attr('checked', false);
                } else {
                    category_block.show()
                }
            }
        });
    
        $('#cap_posts_lists_style_home').change(function(){
           hide_images_position(this, '#cap_magazine_style_home');
        });
        $('#cap_posts_lists_style_taxonomy').change(function(){
           hide_images_position(this, '#cap_magazine_style_taxonomy');
        });
        $('#cap_posts_lists_style_dates').change(function(){
            hide_images_position(this, '#cap_magazine_style_dates');
        });
        $('#cap_posts_lists_style_author').change(function(){
            hide_images_position(this, '#cap_magazine_style_author');
        });
        $('#cap_posts_lists_style_home, #cap_posts_lists_style_taxonomy, #cap_posts_lists_style_dates, #cap_posts_lists_style_author').trigger('change');
        
        function hide_images_position(object, selector){
           var value = $(object).val();
           if(value === 'blog'){
                $(selector).hide().prev().hide().prev('.option-title').hide();
           } else {
                $(selector).show().prev().show().prev('.option-title').show();
           }
        };
        
    });
    
    
})(jQuery)
