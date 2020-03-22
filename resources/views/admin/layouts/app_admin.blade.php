@php
    use App\http\Controllers\Common\Chap;
    if(!Session::has('nav_collapse')){
        Session::put('nav_collapse', 0);
    }
@endphp
<!DOCTYPE html>
<html lang="{!! app()->getLocale() !!}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base_url" content="{!! url('/') !!}">
    <title>@yield('title') - {!! Chap::get_option('site_name') !!} Admin</title>
    <link rel="stylesheet" type="text/css" href="{!! asset('contents/admin/css/minify.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('contents/admin/css/style.css') !!}">
    @stack('css')
    <script type="text/javascript" src="{!! asset('contents/admin/js/minify.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('contents/admin/js/admin.js') !!}"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="loading">
        <img src="{{ asset('contents/admin/images/loading.gif') }}" alt="">
    </div>
    <div class="header_top">
        <div class="header_top_left pull-left">
            <ul>
                <li id="admin_menu_toggle" class="visible-xs"><a href="javascript:void(0);"><i class="ab-icon"></i></a></li>
                <li><a href="{!! url('/') !!}" target="_blank"><i class="dashicons dashicons-admin-home site_home_icon"></i> <span class="hidden-xs">{!! Chap::get_option('site_name') !!}</span></a></li>
                <li class="li_drop_down">
                    <a href="javascript:void(0);"><i class="dashicons dashicons-plus"></i> <span class="hidden-xs">New</span></a>
                    <ul class="ul_drop_down">
                        <li><a href="{!! url('/admin/posts/post/add-new') !!}">Post</a></li>
                        <li><a href="{!! url('/admin/media') !!}">Media</a></li>
                        <li><a href="{!! url('/admin/posts/page/add-new') !!}">Page</a></li>
                        <li><a href="{!! url('/admin/users/action/add') !!}">User</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="header_top_right pull-right">
            <ul class="header_top_menu">
                <li class="header_top_dropdown">
                    <a href="javascript:void(0);">
                        <span class="hidden-xs">Hello, {!! Auth::user()->name !!}</span>
                        <div class="header_top_avatar">
                            <div class="img_wrapper">
                                <div class="img_show">
                                    <div class="img_thumbnail">
                                        <div class="img_centered">
                                            <img src="{!! Auth::user()->avatar !!}" alt="User Avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul>
                        <li>
                            <div class="header_profile">
                                <div class="header_profile_avatar hidden-xs">
                                    <div class="img_wrapper">
                                        <div class="img_show">
                                            <div class="img_thumbnail">
                                                <div class="img_centered">
                                                    <img src="{!! Auth::user()->avatar !!}" alt="User Avatar">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="header_profile_info">
                                    <a href="{!! url('/admin/users/action/profile') !!}">{!! Auth::user()->name !!}</a>
                                    <a href="{!! url('/admin/users/action/profile') !!}">Edit profile</a>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Đăng xuất</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="body_wrapper{!! Session::get('nav_collapse') == 1 ? ' collapsed' : '' !!}">
        <div class="admin_nav">
            <div class="admin_nav_area">
                <ul class="admin_nav_ul">
                    @if(count(config('admin_menus')) > 0)
                    @php
                        $admin_menus = config('admin_menus');
                        // $admin_menus = array_values($admin_menus, function ($value) {
                            // return $value['priority'];
                        // });
                    @endphp
                    @foreach($admin_menus as $menu)
                    <li class="admin_nav_li">
                        <a href="{!! $menu['url'] !!}">
                            <i class="{!! $menu['icon'] !!}"></i> <span>{!! $menu['title'] !!}</span>
                            @php
                                $unapproved_comment_count = Chap::comment_unapproved_count();
                            @endphp
                            @if($menu['name'] == 'comments' && $unapproved_comment_count > 0)
                            <strong class="admin_menu_badge blue" id="menu_badge_{!! $menu['name'] !!}">{!! $unapproved_comment_count !!}</strong>
                            @endif
                        </a>
                        @if(isset($menu['sub']) && count($menu['sub']) > 0)
                        @php
                            // $menu['sub'] = array_values(array_sort($menu['sub'], function ($value) {
                                // return $value['priority'];
                            // }));
                        @endphp
                        <ul class="sub_menu">
                            @foreach($menu['sub'] as $sub)
                            <li><a href="{!! $sub['url'] !!}">{!! $sub['title'] !!}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                    @endif
                    <li class="admin_nav_li hidden-xs"><div id="collapse_button"><i class="collapse-button-icon"></i> <span>Collapse menu</span></div></li>
                </ul>
            </div>
        </div>
        <div class="admin_page">
            <div class="admin_page_wrapper">
                <div class="container-fluid">
                    @yield('content')
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" id="_token" value="{!! csrf_token() !!}">
    <input type="hidden" id="current_url" value="{!! url()->current() !!}">
    @if(isset($active_url))
    <input type="hidden" id="active_url" value="{!! $active_url !!}">
    @else
    <input type="hidden" id="active_url" value="{!! url()->current() !!}">
    @endif
    <input type="hidden" id="nav_collapse_url" value="{!! url('/admin/nav-collapse') !!}">
    <input type="hidden" id="nav_mobile_collapse_url" value="{!! url('/admin/nav-mobile-collapse') !!}">
    <!-- Scripts -->
    <script type="text/javascript" src="{!! asset('contents/admin/js/app.js') !!}"></script>
    @stack('js')
</body>
</html>