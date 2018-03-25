$jq(function() {
    var form = document.getElementById('upload-projects');
    var fileSelect = document.getElementById('csv');
    var form_changes = ['details', 'milestones'];

    form.onsubmit = function(event) {
       event.preventDefault();

       $jq('#status').html('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i> Uploading.......');

       // Get the files from the input
       var files = fileSelect.files;

       // Create a new FormData object.
       var formData = new FormData();

       //Grab just one file, since we are not allowing multiple file uploads
       var file = files[0];

       //Check the file type
       if (!file.type.match('csv.*')) {
           $jq('#status').addClass('bg-danger');
           $jq('#status').html('This file is not a CSV file. Sorry, it cannot be uploaded.');

           setTimeout(function() {
               $jq('#status').removeClass('bg-danger');
               $jq('#status').html('');
               $jq('#csv').val('');
           }, 3000);
           return;
       }

       if (file.size >= 2000000 ) {
           $jq('#status').addClass('bg-danger');
           $jq('#status').html('This file is larger than 2MB. Sorry, it cannot be uploaded.');

           setTimeout(function() {
               $jq('#status').removeClass('bg-danger');
               $jq('#status').html('');
               $jq('#csv').val('');
           }, 3000);
           return;
       }

        // Add the file to the request.
       formData.append('csv', file, file.name);
       formData.append('_token', $jq('#_token').val());

       // Set up the AJAX request.
       var xhr = new XMLHttpRequest();

       // Open the connection.
       xhr.open('POST', '/upload', true);

       // Set up a handler for when the request finishes.
       xhr.onload = function () {
         var response = $jq.parseJSON(xhr.response);

         if (xhr.status === 200 && response.success) {
           $jq('#status').html('<i class="fa fa-check" aria-hidden="true"></i> The projects successfully uploaded!');
           $jq('#status').addClass('bg-success');

           setTimeout(function() {
               $jq('#status').html('');
               $jq('#status').removeClass('bg-success');
               $jq('#uploadProject').modal('hide');

               $jq.ajax({
                 url: '/load/projects',
                 dataType: 'json',
                 beforeSend: function () {
                   var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                   $jq('#projects-container').html(loader);
                 },
                 success: function (response) {
                   if (response.success) {
                     $jq('#projects-container').html(response.html);
                     $jq('#status').removeClass('bg-success');
                   } else {
                     $jq('#status').removeClass('bg-danger');
                   }

                   $jq('#status').html('');
                   $jq('#csv').val('');
                 }
               });
           }, 3000);
         } else {
           if (response.message) {
             $jq('#status').html(response.message);
           } else {
             $jq('#status').html('An error occurred while uploading the file. Try again');
           }

           $jq('#status').addClass('bg-danger');

           setTimeout(function() {
               $jq('#status').html('');
               $jq('#status').removeClass('bg-danger');
               $jq('#csv').val('');
           }, 20000);
         }
       };

       // Send the Data.
       xhr.send(formData);
    }

    $jq(document).on('click', '.toggle-delete-project', function() {
        var id = $jq(this).data('id');
        if (confirm('Delete this project?')) {
            var form = new FormData();
            form.append('id', id);
            form.append('_token', $jq("meta[name='csrf-token']").attr("content"));

            $jq.ajax({
                url: '/delete/project',
                type: 'post',
                data: form,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $jq('#editProject').modal('hide');
                    if (response.success) {
                        $jq('#success-alert').removeClass('hidden');
                        $jq('#success-alert').find('#message').text(response.message);

                        $jq("#projects-container").html(response.html);

                        setTimeout(function() {
                            $jq('#success-alert').addClass('hidden');
                        }, 3000);
                    } else {
                        $jq('#error-alert').removeClass('hidden');
                        $jq('#error-alert').find('#message').text(response.message);

                        setTimeout(function() {
                            $jq('#error-alert').addClass('hidden');
                        }, 3000);
                    }
                }
            });
        }
    });

    $jq(document).on('click', '#toggle-edit-project', function() {
        var id = $jq(this).data('id');
        $jq.ajax({
            url: '/load/project/details/' + id,
            dataType: 'json',
            success: function(response) {
                $jq('#details').html(response.html);
                if ($jq('#details').find('#project-name').val().length) {
                    $jq('.chars').text(100 - $jq('#details').find('#project-name').val().length);
                }
            }
        });
    });

    $jq(document).on('click', '#toggle-edit-milestones', function() {
        var id = $jq(this).data('id');
        $jq.ajax({
            url: '/load/project/milestones/' + id,
            dataType: 'json',
            success: function(response) {
                $jq('#milestones').html(response.html);
            }
        });
    });

    $jq(document).on('change', '.form-changes', function() {
        var index    = $jq(this).data('index');
        var field    = $jq(this).data('field');
        var original = field+'##'+$jq(this).data('original');

        if ($jq(this).is(':checkbox')) {
            var value = $jq(this).is(':checked') ? 1 : 0;
        } else {
            var value = $jq(this).val();
        }

        var changes  = field+'##'+value+'##'+$jq(this).data('original');

        if (form_changes[index] instanceof Array) {
        } else {
            form_changes[index] = [];
        }

        if ($jq.inArray(changes, form_changes) == -1 && original != changes) {
            form_changes[index].push(changes);
        }

        form_changes[index] = $jq.unique(form_changes[index]);

        if (original == changes) {
            if (form_changes[index].length > 0) {
                $jq.each(form_changes[index], function(k, v) {
                    if (v != undefined) {
                        if (v.indexOf(field) != -1) {
                            delete form_changes[index][k];
                        }
                    }
                });
            }
        }

        if ($jq.isEmptyObject(form_changes[index])) {
            form_changes[index] = new Array();
        }
    });

    $jq(document).on('click', '#toggle-update-milestones', function() {
        if (form_changes['milestones'] != undefined) {
            if (form_changes['milestones'].length > 0) {
                form_changes_json = JSON.stringify(form_changes['milestones']);
                $jq('#update-milestones').append("<input type='hidden' name='changes' value='"+ form_changes_json +"'>");

                $jq('#editProject').modal('hide');
                $jq('#commentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $jq('.modal-body').find('#mid').val($jq(this).data('id'));
                $jq('.modal-body').find('#form').val('milestones');
                return false;
            }
        }

        bootbox.confirm({
            message: "No changes has been made with the project milestones.",
            title: "Project Details",
            buttons: {
                confirm: {
                    label: 'Close this Form',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'Make Changes',
                    className: 'btn-default'
                }
            },
            callback: function(result) {
                if (result) {
                    $jq('#editProject').modal('hide');
                }
            }
        });


        // $jq.ajax({
        //     url: '/project/milestones/update',
        //     type: 'post',
        //     data: $jq('#update-milestones').serialize(),
        //     dataType: 'json',
        //     success: function (response) {
        //         $jq('#editProject').modal({
        //             backdrop: 'static',
        //             keyboard: false
        //         });
        //         if (response.success) {
        //             $jq('.message-project-milestone-container').removeClass('hidden');
        //
        //             setTimeout(function() {
        //                 $jq('.message-project-milestone-container').addClass('hidden');
        //                 $jq('#editProject').modal('hide');
        //
        //                 if (form_changes['milestones'].length > 0) {
        //                     $jq('#commentModal').modal({
        //                         backdrop: 'static',
        //                         keyboard: false
        //                     });
        //                     $jq('.modal-body').find('#mid').val(response.data.mid);
        //                     $jq('.modal-body').find('#log-id').val(response.data.log_id);
        //
        //                     form_changes = new Array();
        //                     form_chagnes = ['details', 'changes'];
        //                 }
        //             }, 3000);
        //         }
        //     },
        //     error: function(data) {
        //         var errors = data.responseJSON;
        //         var error_message = "";
        //         $jq.each(errors.errors, function(key, value) {
        //             if (value !== undefined) {
        //                 error_message += "<li>"+ value +"</li>";
        //             }
        //         });
        //
        //         $jq('.error-wrapper').append(error_message);
        //         $jq('.error-container').removeClass('hidden');
        //         setTimeout(function () {
        //             $jq('.error-container').addClass('hidden');
        //         }, 5000);
        //     }
        // });
    });

    $jq(document).on('click', '#toggle-update-project', function() {
        if (form_changes['details'] != undefined) {
            if (form_changes['details'].length > 0) {
                form_changes_json = JSON.stringify(form_changes['details']);
                $jq('#update-projects').append("<input type='hidden' name='changes' value='"+ form_changes_json +"'>");

                $jq('#editProject').modal('hide');
                $jq('#commentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $jq('.modal-body').find('#pid').val($jq(this).data('id'));
                $jq('.modal-body').find('#form').val('details');
                return false;
            }
        }

        bootbox.confirm({
            message: "No changes has been made with the project details.",
            title: "Project Milestones",
            buttons: {
                confirm: {
                    label: 'Close this Form',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'Make Changes',
                    className: 'btn-default'
                }
            },
            callback: function(result) {
                if (result) {
                    $jq('#editProject').modal('hide');
                }
            }
        });

        // $jq.ajax({
        //     url: '/project/' + $jq('#pid').val(),
        //     type: 'put',
        //     data: $('#update-projects').serialize(),
        //     dataType: 'json',
        //     success: function (response) {
        //         if (response.success) {
        //             $jq('.message-update-container').removeClass('hidden');
        //             setTimeout(function () {
        //                 $jq('.message-update-container').addClass('hidden');
        //                 $jq('#editProject').modal('hide');
        //                 $jq('body').removeClass('modal-open');
        //                 $jq('.modal-backdrop').remove();
        //
        //                 // refresh project list container
        //                 $jq.ajax({
        //                   url: '/load/projects',
        //                   dataType: 'json',
        //                   beforeSend: function () {
        //                     var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
        //                     $jq('#projects-container').html(loader);
        //                   },
        //                   success: function (response) {
        //                     if (response.success) {
        //                       $jq('#projects-container').html(response.html);
        //                     }
        //                   }
        //                 });
        //
        //                 if (form_changes['details'].length > 0) {
        //                     form_changes = new Array();
        //                     form_changes = ['details', 'milestones'];
        //
        //                     $jq('#commentModal').modal({
        //                         backdrop: 'static',
        //                         keyboard: false
        //                     });
        //                     $jq('.modal-body').find('#pid').val(response.data.pid);
        //                     $jq('.modal-body').find('#log-id').val(response.data.log_id);
        //                 }
        //             }, 3000);
        //         }
        //     },
        //     error: function(data) {
        //         var errors = data.responseJSON;
        //         var error_message = "";
        //         $jq.each(errors.errors, function(key, value) {
        //             if (value !== undefined) {
        //                 error_message += "<li>"+ value +"</li>";
        //             }
        //         });
        //
        //         $jq('.error-wrapper').append(error_message);
        //         $jq('.error-container').removeClass('hidden');
        //         setTimeout(function () {
        //             $jq('.error-container').addClass('hidden');
        //         }, 5000);
        //     }
        // });
    });

    $jq(document).on('click', '#commentModal-close-btn', function() {
        // if (confirm("The changes to the project will not be saved without a comment. Are you sure you want to close this window?")) {
        //     $jq('#commentModal').modal('hide');
        // }
        $jq('#dataConfirmModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $jq(document).on('click', '#toggle-dont-save-changes', function() {
        $jq('#dataConfirmModal').modal('hide');
        $jq('#commentModal').modal('hide');
    });

    $jq(document).on('click', '.toggle-milestones-project', function() {
        var pid = $jq(this).data('pid');
        $jq.ajax({
            url: '/project/edit/' + pid,
            dataType: 'json',
            success: function (response) {
                $jq('#editProject').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $jq('#edit-project-details-container').html(response.html);
                $jq('a[href="#milestones"]').trigger('click');
                $jq('#details').removeClass('active');
                $jq('#milestones').addClass('active');
            }
        });
    });

    $jq(document).on('click', '#toggle-upload-csv', function() {
        if ($jq('#csv').val() == "") {
            bootbox.alert({
                message: "<h4>Please select a CSV file!</4>",
                size: 'small'
            });
        } else {
            $('#upload-projects').submit();
        }

    });

    $jq(document).on('click', '#toggle-add-comment', function() {
        if ($jq('#form').val() == 'details') {
            var _data = { comment: $jq('#comment-form').serialize(), project: $jq('#update-projects').serialize(), _token: $jq("meta[name='csrf-token']").attr("content") };
        } else {
            var _data = { comment: $jq('#comment-form').serialize(), milestones: $jq('#update-milestones').serialize(), _token: $jq("meta[name='csrf-token']").attr("content") };
        }

        $jq.ajax({
            url: '/comment/add',
            type: 'post',
            data: _data,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $jq('.message-comment-container').removeClass('hidden');
                    setTimeout(function() {
                        $jq('.message-comment-container').addClass('hidden');
                        $jq('#commentModal').modal('hide');

                        // refresh project list container
                        $jq.ajax({
                          url: '/load/projects',
                          dataType: 'json',
                          beforeSend: function () {
                            var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                            $jq('#projects-container').html(loader);
                          },
                          success: function (response) {
                            if (response.success) {
                              $jq('#projects-container').html(response.html);
                            }
                          }
                        });

                        if (form_changes['details'].length > 0) {
                            form_changes = new Array();
                            form_changes = ['details', 'milestones'];
                        }

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

    $jq(document).on('click', '.toggle-view-project', function() {
        var pid = $jq(this).data('pid');
        $jq.ajax({
            url: '/project/edit/' + pid,
            dataType: 'json',
            success: function (response) {
                $jq('#editProject').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $jq('#edit-project-details-container').html(response.html);
                $jq('a[href="#details"]').trigger('click');
                $jq('#details').addClass('active');
                $jq('#milestones').removeClass('active');
            }
        });
    });

    $jq(document).on('click', '#toggle-search', function() {
        var search_term = $jq('#search-term').val();
        $jq.ajax({
            url: '/search',
            type: 'post',
            data: $jq('#search-form').serialize(),
            dataType: 'json',
            beforeSend: function () {
              var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Searching...</td></tr>";
              $jq('#projects-container').html(loader);
            },
            success: function (response) {
              $jq('#projects-container').html(response.html);
              $jq('#pagination').addClass('hidden');
              if (search_term.length > 0) {
                  $jq('#pagination').addClass('hidden');
              } else {
                  $jq('#pagination').removeClass('hidden');
              }
            }
        });
    });

    $jq(document).on('keypress', '#search-term', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $jq('#toggle-search').trigger('click');
        }
    });

    $jq(document).on('click', '#toggle-add-team-member', function() {
        $jq.ajax({
            url: '/load/member/form',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('table').find('#team-member-container').append(response.html);
                    // $jq('#team-member-container').append(response.html);
                }
            }
        });
    });

    $jq(document).on('change', '.toggle-select-member', function() {
        var uid = $jq(this).val();
        $jq.ajax({
            url: '/select/team/member/' + uid,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $jq('table').find('#team-member-container').append(response.row);
                    $jq('table').find('#team-member-list-wrapper').remove();
                }
            }
        });
    });

    $jq(document).on('click', '.toggle-remove-member', function(e) {
        e.preventDefault();
        var uid = $jq(this).data('id');
        $('#row-' + uid).remove();
    });

    $jq(document).on('click', '#toggle-new-project-form', function() {
        $jq('#projectModal').modal('hide');
        $jq('body').removeClass('modal-open');
        $jq('.modal-backdrop').remove();

        $jq('#addProject').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $jq(document).on('click', '#toggle-upload-form', function() {
        $jq('#projectModal').modal('hide');
        $jq('body').removeClass('modal-open');
        $jq('.modal-backdrop').remove();

        $jq('#uploadProject').modal({
            backdrop: 'static',
            keyboard: false
        });

        if ($jq('#status').hasClass('bg-danger')) {
            $jq('#status').removeClass('bg-danger');
            $jq('#status').html('');
        }

        if ($jq('#status').hasClass('bg-success')) {
            $jq('#status').removeClass('bg-success');
            $jq('#status').html('');
        }
    });

    $jq(document).on('click', '#toggle-save-project', function(e) {
        $jq.ajax({
            type: 'post',
            url: '/project',
            data: $jq('#projects').serialize(),
            dataType: 'json',
            success: function(data) {
              if (data.success) {
                $jq('.message-container').removeClass('hidden');
                setTimeout(function () {
                    $jq('.message-container').addClass('hidden');
                    $jq('#addProject').modal('hide');
                    $jq('body').removeClass('modal-open');
                    $jq('.modal-backdrop').remove();

                    // refresh project list container
                    $jq.ajax({
                      url: '/load/projects',
                      dataType: 'json',
                      beforeSend: function () {
                        var loader = "<tr><td colspan='4' class='text-center padding-20'><i class='fa fa-cog fa-2x fa-spin' aria-hidden='true'></i><br>Loading...</td></tr>";
                        $jq('#projects-container').html(loader);
                      },
                      success: function (response) {
                        if (response.success) {
                          $jq('#projects-container').html(response.html);
                        }
                      }
                    });
                }, 4000);
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

    $jq(document).on('click', '#toggle-new-project', function() {
        $jq('#projectModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    var maxLength = 100;
    $jq(document).on('keyup', '.project-name', function() {
        var length = $jq(this).val().length;
        var length = maxLength-length;

        $jq('.chars').text(length);
    });

});
