(function($){
    $(document).ready(function(){
        $('.single-poll-item').each(function () {
            var pasp_poll_single_item = $(this);
            $(this).find('#pasp_poll_vote_button').click(function() {
                var poll_button = $(this);
                $(pasp_poll_single_item).parent().find('.single-poll-item').each(function () {
                    var that = $(this);
                    that.find('#pasp_poll_vote_button').val('...');
                    that.find('#pasp_poll_vote_button').attr('disabled','yes');
                });
                var option_id = pasp_poll_single_item.find('#pasp_poll-option-id').val();
                var poll_id = pasp_poll_single_item.find('#pasp_poll-id').val();
                $.ajax({
                    type: 'POST',
                    url: pasp_poll_ajax_obj.ajax_url,
                    data: {
                        action: 'pasp_poll_action',
                        option_id: option_id,
                        poll_id: poll_id
                    },
                    success: function(response){
                        var pasp_poll_json = jQuery.parseJSON(response);
                        pasp_poll_single_item.parent().find('.single-poll-item').each(function(){
                            $(this).find('#pasp_poll_vote_button').addClass('pasp_poll_button_hide');
                        });
                        jQuery(pasp_poll_json).find('.vote_percentage').text(pasp_poll_json.total_vote_percentage+'%');
                        
                        jQuery(pasp_poll_json).find('.vote_process').text(pasp_poll_json.total_opt_vote_count+' / '+pasp_poll_json.total_vote_count);
                        setTimeout( () => {
                            $(poll_button).addClass('pasp_poll_button_show');
                            $(poll_button).val('Voted');
                        }, 300 );
                    }
                });
            })
            
        })
    });
})(jQuery);