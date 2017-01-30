<!DOCTYPE html>
<html lang="en">

    @section('htmlheader')
    @include('layouts.partials.htmlheader')
    @show

    @section('scripts')
    @include('layouts.partials.scripts')
    @show

    <body>
        <div class="wrapper">
            <!-- section class="content" comentado para quitar la margin del topo -->
            <!-- Your Page Content Here -->
            @yield('main-content')
            <!-- /section --><!-- /.content -->
        </div>
    </body>
</html>
