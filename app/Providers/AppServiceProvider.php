<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
 config([
            'preload.styles' => [
                asset('bootstrap/css/bootstrap.min.css'),
                asset('plugins/font-awesome/font-awesome.min.css'),
                asset('plugins/ionicons/ionicons.min.css'),
                asset('dist/css/AdminLTE.min.css'),
                asset('dist/css/skins/_all-skins.min.css'),
                asset('plugins/iCheck/flat/blue.css'),
                asset('plugins/morris/morris.css'),
                asset('plugins/datatables/dataTables.bootstrap.css'),
                asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css'),
                asset('plugins/datepicker/datepicker3.css'),
                asset('plugins/daterangepicker/daterangepicker-bs3.css'),
                asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'),
            ],
            'preload.scripts' => [
                asset('plugins/jQuery/jQuery-2.1.3.min.js'),
                asset('bootstrap/js/bootstrap.min.js'),
                asset('plugins/input-mask/jquery.inputmask.js'),
                asset('plugins/input-mask/jquery.inputmask.date.extensions.js'),
                asset('plugins/input-mask/jquery.inputmask.extensions.js'),
                asset('plugins/moment/moment.min.js'),
                asset('plugins/daterangepicker/daterangepicker.js'),
                asset('plugins/colorpicker/bootstrap-colorpicker.min.js'),
                asset('plugins/timepicker/bootstrap-timepicker.min.js'),
                asset('plugins/datatables/jquery.dataTables.js'),
                asset('plugins/datatables/dataTables.bootstrap.js'),
                asset('plugins/slimScroll/jquery.slimscroll.min.js'),
                asset('plugins/iCheck/icheck.min.js'),
                asset('plugins/fastclick/fastclick.min.js'),
            ],
        ]);
    }
}
