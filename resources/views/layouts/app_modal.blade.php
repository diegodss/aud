<!DOCTYPE html>
<html lang="en">

    @section('htmlheader')
    @include('layouts.partials.htmlheader')
    @show

    @section('scripts')
    @include('layouts.partials.scripts')
    @show

    <body>
                <section class="content">
                    <!-- Your Page Content Here -->
                    @yield('main-content')
                </section><!-- /.content -->
    </body>
</html>
