<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Pondok Indah | Software Restoran</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="MobileOptimized" content="320">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ url('/') }}/assets/metronic/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
   <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.css" />
   @yield('css_assets')
   <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="{{ url('/') }}/assets/metronic/css/style-metronic.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style-responsive.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ url('/') }}/assets/metronic/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->

    @yield('css_section')

    <link rel="shortcut icon" href="{{ url('/') }}/assets/logo.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="page-header-fixed">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="header-inner">
            <!-- BEGIN LOGO -->
            <a class="navbar-brand" href="index.html">
                <img src="{{ url('/') }}/assets/logotext.png" alt="logo" class="img-responsive" />
            </a>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <img src="{{ url('/') }}/assets/metronic/img/menu-toggler.png" alt="" />
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" src="{{ url('/') }}/assets/metronic/user-avatar.png" />
                        <span class="username">{{ Session::get('userlogin')->nama_lengkap }}</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ url('/oldapp/logout') }}" id="logoutLink"><i class="icon-key"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix"></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu">
                <li>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler hidden-phone"></div>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                </li>
                <li>
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM --
                    <form class="sidebar-search">
                        <div class="form-container">
                            <div class="input-box">
                                <a href="javascript:;" class="remove"></a>
                                <input type="text" placeholder="Search..." />
                                <input type="button" class="submit" value=" " />
                            </div>
                        </div>
                    </form>
                    <!-- END RESPONSIVE QUICK SEARCH FORM -->
                </li>
                <li class="start ">
                    <a href="javascript:;">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="{{ set_active('oldapp/report*') }}">
                    <a href="javascript:;">
                        <i class="icon-book"></i>
                        <span class="title">Laporan</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="{{ set_active('oldapp/report/pertanggal*') }}">
                            <a href="javascript:;">
                            Laporan Pertanggal
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="{{ set_active('oldapp/report/pertanggal') }}"><a href="{{ url('/oldapp/report/pertanggal') }}">Laporan Penjualan</a></li>
                                <li class="{{ set_active('oldapp/report/pertanggal/solditem') }}">
                                    <a href="{{ url('/oldapp/report/pertanggal/solditem') }}">
                                    Sold Item
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ set_active('oldapp/report/periode*') }}">
                            <a href="javascript:;">
                            Laporan Perperiode
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="{{ set_active('oldapp/report/periode') }}"><a href="{{ url('/oldapp/report/periode') }}">Laporan Penjualan</a></li>
                                <li class="{{ set_active('oldapp/report/periode/solditem') }}">
                                    <a href="{{ url('/oldapp/report/periode/solditem') }}">
                                        Sold Item
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div class="page-content">
            @yield('content')
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            2016 &copy; Pondok Indah. Resto Application by <a href="https://www.facebook.com/Ahmad.Rizal.Afani">Ahmad Rizal Afani</a>.
        </div>
        <div class="footer-tools">
            <span class="go-top">
         <i class="icon-angle-up"></i>
         </span>
        </div>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
   <script src="{{ url('/') }}/assets/metronic/plugins/respond.min.js"></script>
   <script src="{{ url('/') }}/assets/metronic/plugins/excanvas.min.js"></script>
   <![endif]-->
   <script type="text/javascript">
       var appToken = "{{ csrf_token() }}";
       var baseUrl = "{{ url('/') }}";
       @can('orderqueue.notification')
       var app = {
           notification : true
       };
       @endcan
   </script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.cookie.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.js"></script>
    @yield('js_assets')
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- END CORE PLUGINS -->
    <script src="{{ url('/') }}/assets/metronic/scripts/app.js"></script>
    <script src="{{ url('/') }}/assets/metronic/scripts/main.app.js"></script>
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();

            $(".btnSubmit").click(function(){
              $(this).addClass("disabled");
            });

            toastr.options.closeButton = true;
            toastr.options.positionClass = "toast-bottom-right";
            @if(Session::has('succcess'))
            toastr.success('{{ Session::get("succcess") }}');
            @endif
            @if($errors->has('failed'))
            toastr.error('{{ $errors->first("failed") }}');
            @endif
        });
    </script>
    @yield('js_section')
</body>
<!-- END BODY -->

</html>
