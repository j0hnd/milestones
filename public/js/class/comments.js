$jq(function() {
    $jq(document).on('click', '.toggle-comment-form', function() {
        var logId = $(this).data('id');

        // open comment form
        if ($jq('#comment-form-' + logId).hasClass('hidden')) {
            $jq('#comment-form-' + logId).removeClass('hidden');
        } else {
            $jq('#comment-form-' + logId).addClass('hidden');
        }
    });

    $jq(document).on('click', '.toggle-comments', function() {
        var pid = $(this).data('id');

        // open specific comment wrapper
        if ($jq('#comment-' + pid).hasClass('hidden')) {
            $jq('#comment-' + pid).removeClass('hidden');
        } else {
            $jq('#comment-' + pid).addClass('hidden');
        }

        $jq.ajax({
            url: '/comments/' + pid,
            dataType: 'json',
            beforeSend: function () {
              var loader = "<div class='padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading comments...</div>";
              $jq('#comment-' + pid).find('.comment-wrapper').addClass('text-center');
              $jq('#comment-' + pid).find('.comment-wrapper').html(loader);
            },
            success: function (response) {
                $jq('#comment-' + pid).find('.comment-wrapper').removeClass('text-center');
                $jq('#comment-' + pid).find('.comment-wrapper').html(response.html);
            }
        });
    });

    $jq(document).on('click', '#toggle-save-comment', function() {
        var logId = $jq(this).data('id');
        $jq.ajax({
            url: '/reply/comment',
            type: 'post',
            data: $('#comment-form' + logId).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $jq('.alert').removeClass('hidden');
                    $jq('.alert').find('p').text('Comment has been posted.');
                    $jq('#comment-form-' + logId).addClass('hidden');
                    setTimeout(function() {
                        $jq('.alert').addClass('hidden');
                        $jq.ajax({
                            url: '/load/dashboard',
                            dataType: 'json',
                            beforeSend: function () {
                              var loader = "<tr><td colspan='16' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Updating...</td></tr>";
                              $jq('#dashboard-container').html(loader);
                            },
                            success: function(response) {
                                $jq('#dashboard-container').html(response.html);
                            }
                        });
                    }, 3000);
                }
            }
        });
    });
});
