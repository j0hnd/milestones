$jq(function () {
    $jq(document).on('click', '#toggle-delete-projecttype', function() {
        var id = $jq(this).data('id');
        if (confirm('Delete this project type?')) {
            $jq.ajax({
                url: '/project/type/'+ id +'/delete',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $jq('#project-types-container').html(response.list);
                    }
                }
            });
        }
    });

    $jq(document).on('click', '#toggle-update-projecttype', function() {
        var id = $jq('#id').val();
        $jq.ajax({
            url: '/project/type/'+ id +'/edit',
            type: 'put',
            data: $jq('#edit-project-type-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#editProjectTypeModal').modal('hide');
                    $jq('#project-types-container').html(response.list);
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

    $jq(document).on('click', '#toggle-edit-projecttype', function() {
        var id = $jq(this).data('id');
        $jq.ajax({
            url: '/project/type/'+ id +'/edit',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#editProjectTypeModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $jq('#project-type-info-container').html(response.form);
                }
            }
        });
    });

    $jq(document).on('click', '#toggle-save-projecttype', function() {
        $jq.ajax({
            url: '/save/project/type',
            type: 'post',
            data: $jq('#project-type-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#projectTypeModal').modal('hide');
                    $jq('#project-types-container').html(response.list);
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

    $jq(document).on('click', '#toggle-add-project-type', function() {
        $jq('#projectTypeModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $jq(document).on('click', '#toggle-delete-userrole', function() {
        var id = $jq(this).data('id');
        if (confirm('Delete this role?')) {
            $jq.ajax({
                url: '/user/role/'+ id +'/delete',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $jq('#user-roles-container').html(response.list);
                    }
                }
            });
        }
    });

    $jq(document).on('click', '#toggle-update-userrole', function() {
        var id = $jq('#id').val();
        $jq.ajax({
            url: '/user/role/'+ id +'/edit',
            type: 'put',
            data: $jq('#edit-user-role-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#editUserRoleModal').modal('hide');
                    $jq('#user-roles-container').html(response.list);
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

    $jq(document).on('click', '#toggle-edit-userrole', function() {
        var id = $jq(this).data('id');
        $jq.ajax({
            url: '/user/role/'+ id +'/edit',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#editUserRoleModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $jq('#user-role-info-container').html(response.form);
                }
            }
        });
    });

    $jq(document).on('click', '#toggle-save-userrole', function() {
        $jq.ajax({
            url: '/save/user/role',
            type: 'post',
            data: $jq('#user-role-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('#userRoleModal').modal('hide');
                    $jq('#user-roles-container').html(response.list);
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

    $jq(document).on('click', '#toggle-add-user-role', function() {
        $jq('#userRoleModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $jq(document).on('click', '#toggle-dashboard-search', function() {
        var search_term = $jq('#search-term').val();
        $jq.ajax({
            url: '/search/summary',
            type: 'post',
            data: $jq('#search-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
              var loader = "<tr><td colspan='15' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Searching...</td></tr>";
              $jq('#dashboard-container').html(loader);
            },
            success: function (response) {
                $jq('#dashboard-container').html(response.html);
                if (search_term.length > 0) {
                    $jq('#pagination').addClass('hidden');
                } else {
                    $jq('#pagination').removeClass('hidden');
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

    $jq(document).on('keypress', '#search-term', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $jq('#toggle-dashboard-search').trigger('click');
        }
    });
});
