<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->
<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->

<script src="{{ url('/plugins/jquery-2.2.3.min.js') }}" type="text/javascript"></script>
<script src="{{ url('/plugins/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ url('/plugins/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript"> var $jq = jQuery.noConflict();</script>
<script src="{{ url('plugins/bootbox.min.js') }}" type="text/javascript"></script>
<script src="{{ url('js/class/main.js') }}" type="text/javascript"></script>
@yield('custom-script')
