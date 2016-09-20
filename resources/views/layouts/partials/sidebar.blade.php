<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())

        <!--
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>{{ Auth::user()->name }}</p>

                            <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('message.online') }}</a>
                        </div>
                    </div>
        -->

        <div class="user-panel">
            <div   style="text-align:center">
                <img src="http://web.minsal.cl/wp-content/uploads/2015/07/logo180-180.png"   alt="User Image" />
            </div>

        </div>


        @endif

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('message.search') }}..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('message.header') }} 1 </li>
            <!-- Optionally, you can add icons to the links -->
            <!-- li ><a href="{{ url('home') }}"><i class='fa fa-link'></i> <span>Home</span></a></li -->
            <?php $linkParentActiveId = "0"; ?>
            @if (!Auth::guest())
            @foreach ( Auth::user()->getMenuAcceso() as $menuItem)

            <li class="treeview" id="menu_parent_{{ $menuItem->id_menu  }}">
                <a href="#">
                    <i class='fa fa-link'></i>
                    <span>{{ $menuItem->nombre_menu }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @foreach ( Auth::user()->getSubMenuAcceso( $menuItem->id_menu ) as $submenuItem)
                    <?php
                    $uri = Request::path();
                    $uri = explode("/", $uri);
                    $uri = $uri[0];
                    $linkActive = "";
                    if ($submenuItem->slug == $uri) {
                        $linkActive = "active";
                        $linkParentActiveId = $menuItem->id_menu;
                    }
                    ?>
                    <li class='{{ $linkActive }}'><a href="{{ url($submenuItem->slug) }}"> {{ $submenuItem->nombre_menu }}</a>  </li>
                    @endforeach
                </ul>
            </li>
            @endforeach
            @endif

            <script>
                $(document).ready(function () {
                $("#menu_parent_" + {{ $linkParentActiveId }}).attr('class', 'active');
                });
            </script>

         <!-- li><a href="{{ url('itemCRUD2') }}"><i class='fa fa-link'></i><span>Item</span></a></li -->


        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
