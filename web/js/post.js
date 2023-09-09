// when jquery is ready
$(function() {
    $('#my-ajax-form').on('beforeSubmit', function(){
        let form = $(this);
        let data = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: data,
            success: function(response){
                if(response.success === true){
                    $('#posts').prepend(response.part);
                    form.trigger('reset');
                }else{
                    alert(response.message);
                    // refresh captcha
                }
            },
            error: function(){
                alert('Error!');
            }
        });
        return false;
    });

    $('body').on('click', '.emoji-list .post-action', function(){
        let emoji_id = $(this).data('emoji_id');
        let post_id = $(this).parent().data('post_id');
        postAction(post_id, emoji_id);
    });

    function postAction(post_id, emoji_id)
    {
        let data = {
            emoji_id: emoji_id,
            post_id: post_id
        };
        $.ajax({
            url: '/post/emoji',
            type: 'POST',
            data: data,
            success: function(response){
                if(response.success === true){
                    updateEmojis(post_id, response.reactions);
                }else{
                    alert(response.message);
                }
            },
            error: function(){
                alert('Error!');
            }
        });
    }

    function updateEmojis(post_id, reactions)
    {
        let emoji_list = $('.emoji-list[data-post_id="' + post_id + '"]');
        $.each(reactions, function(emoji_id, count){
            let emoji = emoji_list.find('.post-action[data-emoji_id="' + emoji_id + '"]');
            emoji.text(count);
        });
    }

    $('#toggleCreatePost').click(function () {
        let create_post_block = $('.create-post-block');
        create_post_block.toggleClass('d-none');
        if(!create_post_block.hasClass('d-none')){
            location.href = '#my-ajax-form';
        }
    });
});