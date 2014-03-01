(function($) {
    $(document).ready(function() {

        //WP 3.5 add media uploader in logo settings
        $('.upload_image_button').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var object = $(this).closest('.option-inputs');
            wp.media.editor.send.attachment = function(props, attachment) {
                //                $('.custom_media_image').attr('src', attachment.url);
                object.find(':text').val(attachment.url);
                object.find('.cc_image_preview').attr('src', attachment.url);


                wp.media.editor.send.attachment = send_attachment_bkp;
            }

            wp.media.editor.open(button);

            return false;
        });

        $('.delete_image_button').click(function() {
            var object = $(this).closest('.option-inputs');
            object.find(':text').val('');
            object.find('.cc_image_preview').replaceWith('<img class="cc_image_preview" id="image_cap_favicon" src="" />');
        });

    });

})(jQuery)