$jq(function() {

    $jq.ajax({
        url: '/check-first-login',
        dataType: 'json',
        success: function(response) {
            if (response.success && $jq.cookie('first_login') != 1) {
                $jq.cookie("first_login", 1), { expires: 1 };

                $jq('.toggle-user-profile').trigger('click');
            }
        }
    });

    // dashboard container will refresh every 15 minutes
    setInterval(function() {
        $jq.ajax({
            url: '/refresh/dashboard',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
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
                }
            }
        });
    }, 900000);

});
