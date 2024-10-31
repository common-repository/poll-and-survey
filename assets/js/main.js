(function ($) {
    $(document).ready(function() {
        $('.pasp_add_option_btn').click(function() {
            var date = new Date();
            var components = [
                date.getYear(),
                date.getMonth(),
                date.getDate(),
                date.getHours(),
                date.getMinutes(),
                date.getSeconds(),
                date.getMilliseconds(),
            ];
            var paspUniqueId = components.join("");

            $('#pasp_poll_append_option_field_table').append('<tr class="pasp_poll_append_option_field_table_tr"><td><table class="pasp_form_table"><tr><td>Option Name:</td><td><input type="text" name="pasp_poll_option[]" id="pasp_poll_option" class="widefat" /></td></tr><tr><td>Option Image:</td><td><input type="url" id="pasp_poll_option_image" name="pasp_poll_option_image[]" class="widefat" /><input type="hidden" name="pasp_poll_option_id[]" id="pasp_poll_option_id[]" value="'+paspUniqueId+'" /></td><td><input type="button" class="button" name="pasp_poll_option_button" id="pasp_poll_option_button" value="Upload" /></td></tr><tr><td colspan="2"><input type="button" class="button" id="pasp_poll_option_rm_btn" name="pasp_poll_option_rm_btn" value="Remove Option" /></td></tr></table></td></tr><hr>');

            $('#pasp_poll_append_option_field_table .pasp_poll_append_option_field_table_tr').each(function() {
                var pasp_tr_container = $(this);
                pasp_tr_container.find('#pasp_poll_option_rm_btn').click(function() {
                    $(pasp_tr_container).remove();
                });
            });

            $('#pasp_poll_append_option_field_table .pasp_poll_append_option_field_table_tr').each(function() {
                $(this).find('#pasp_poll_option_button').click(function(e) {
                    var img_val = $(this).parent().parent().find('#pasp_poll_option_image');
                    var image = wp.media({
                        title: 'upload Image',
                        multiple: false,
                    }).open()
                    .on('select',function(e) {
                        var upload_image = image.state().get('selection').first();
                        var image_url = upload_image.toJSON().url;
                        img_val.val(image_url);
                    });
                });
            });
        });

        $('#pasp_poll_append_option_field_table .pasp_poll_append_option_field_table_tr').each(function() {
            $(this).find('#pasp_poll_option_button').click(function(e) {
                
                var img_val = $(this).parent().parent().find('#pasp_poll_option_image');
                var image = wp.media({
                    title: 'upload Image',
                    multiple: false,
                }).open()
                .on('select',function(e) {
                    var upload_image = image.state().get('selection').first();
                    var image_url = upload_image.toJSON().url;
                    img_val.val(image_url);
                });
            });
        });


    });
})(jQuery)