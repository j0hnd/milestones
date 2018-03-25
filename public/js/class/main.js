$jq(function() {
    $jq(document).on('click', '#toggle-send-reset-link', function() {
        $jq.ajax({
            url: '/forgot-password',
            type: 'post',
            data: $jq('#forgot-password-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('.message-forgot-password-container').removeClass('hidden');
                    setTimeout(function () {
                        $jq('.message-forgot-password-container').addClass('hidden');
                        $jq('#forgotPasswordModal').modal('hide');
                    }, 3000);
                } else {
                    $jq('.message-forgot-password-error-container').removeClass('hidden');
                    setTimeout(function () {
                        $jq('.message-forgot-password-container').addClass('hidden');
                        $jq('#forgotPasswordModal').modal('hide');
                    }, 3000);
                }
            },
            error: function(data) {
                var errors = data.responseJSON;
                var error_message = "";
                $jq.each(errors.errors, function(key, value) {
                    if (value !== undefined) {
                        error_message += "<li>"+ value +"</li>";
                    }
                });

                $jq('.error-wrapper').append(error_message);
                $jq('.error-container').removeClass('hidden');
                setTimeout(function () {
                    $jq('.error-container').addClass('hidden');
                }, 5000);
            }
        });
    });

    $jq(document).on('click', '#toggle-forgot-password', function() {
        $jq('#forgotPasswordModal').modal({
            backdrop: 'static',
            keyboard: false
        });

        if ($jq('.message-forgot-password-error-container').hasClass('hidden') == false) {
            $jq('.message-forgot-password-error-container').addClass('hidden');
        }

        if ($jq('.message-forgot-password-container').hasClass('hidden') == false) {
            $jq('.message-forgot-password-container').addClass('hidden');
        }
    });

    $jq(document).on('click', '#toggle-save-userprofile', function() {
        $jq.ajax({
            url: '/user/profile',
            type: 'put',
            data: $jq('#user-profile-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('.message-user-profile-container').removeClass('hidden');
                    setTimeout(function () {
                        $jq('#profileModal').modal('hide');
                    }, 3000);
                }
            },
            error: function(data) {
                var errors = data.responseJSON;
                var error_message = "";
                $jq.each(errors.errors, function(key, value) {
                    if (value !== undefined) {
                        error_message += "<li>"+ value +"</li>";
                    }
                });

                $jq('.error-wrapper').append(error_message);
                $jq('.error-container').removeClass('hidden');
                setTimeout(function () {
                    $jq('.error-container').addClass('hidden');
                }, 5000);
            }
        });
    });

    $jq(document).on('click', '.toggle-user-profile', function() {
        $jq.ajax({
            url: '/user/profile',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#profileModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $jq('#user-profile-container').html(response.form);
                }
            }
        });
    });
});
