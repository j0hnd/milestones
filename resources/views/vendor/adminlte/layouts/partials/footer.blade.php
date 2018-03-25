<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        <?php
         $content = file_get_contents(app_path('../release.txt'));
         echo $content;
         ?>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2017</strong>
    <!-- {{ trans('adminlte_lang::message.createdby') }} <a href="http://acacha.org/sergitur">Sergi Tur Badenas</a>. {{ trans('adminlte_lang::message.seecode') }} <a href="https://github.com/acacha/adminlte-laravel">Github</a> -->
</footer>
