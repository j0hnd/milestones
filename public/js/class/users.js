$jq(function () {
    $jq(document).on('click', '#toggle-update-member', function() {
        var uid = $jq('#uid').val();
        $jq.ajax({
            url: '/user/' + uid,
            type: 'put',
            data: $('#member-form').serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $jq('#editModal').modal('hide');
                    $jq.ajax({
                        url: '/load/members',
                        beforeSend: function () {
                            var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Updating...</td></tr>";
                            $jq('#team-members-container').html(loader);
                        },
                        success: function (response) {
                            if (response.success) {
                                $jq('#team-members-container').html(response.html);
                            }
                        }
                    });
                }
            }
        });
    });

    $jq(document).on('click', '#toggle-edit-member', function() {
        var uid = $jq(this).data('id');
        $jq.ajax({
            url: '/user/' + uid + '/edit',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $jq('#editModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $jq('#editModal').find('#form-container').html(response.html);
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

    $jq(document).on('click', '#toggle-delete-member', function() {
        var uid = $jq(this).data('id');
        if (confirm('Delete selected member?')) {
            $jq.ajax({
                url: '/user/delete/' + uid,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $jq.ajax({
                            url: '/load/members',
                            beforeSend: function () {
                                var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Updating...</td></tr>";
                                $jq('#team-members-container').html(loader);
                            },
                            success: function (response) {
                                if (response.success) {
                                    $jq('#team-members-container').html(response.html);
                                }
                            }
                        });
                    }
                }
            });
        }
    });

    $jq(document).on('click', '#toggle-add-team-member', function() {
        $jq.ajax({
            url: '/user/create',
            dataType: 'json',
            success: function (response) {
                $jq('#memberModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $jq('#form-container').html(response.html);
            }
        });
    });

    $jq(document).on('click', '#toggle-save-member', function() {
        $jq.ajax({
            url: '/user',
            type: 'post',
            data: $('#member-form').serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $jq('.message-add-member-container').removeClass('hidden');

                    setTimeout(function() {
                        $jq('.message-add-member-container').addClass('hidden');
                        $jq('#memberModal').modal('hide');
                        $jq.ajax({
                            url: '/load/members',
                            dataType: 'json',
                            beforeSend: function () {
                                var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                                $jq('#team-members-container').html(loader);
                            },
                            success: function (response) {
                                $jq('#team-members-container').html(response.html);
                            }
                        });
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
});
