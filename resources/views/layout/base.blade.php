<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_NAME') }}</title>
    <!-- for-mobile-apps -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- //for-mobile-apps -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/zoomslider.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/component.css') }}" />
    <script type="text/javascript" src="{{ asset('js/modernizr-2.6.2.min.js') }}"></script>
    <!--/web-fonts-->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/myStyles.css') }}" />
    @yield('css')

    @if(env('APP_ENV') === 'production')
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,600italic,300,300italic,700,400italic' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Wallpoet' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Ubuntu:400,500,700,300' rel='stylesheet' type='text/css'>
    @endif
    <!--//web-fonts-->
</head>
<body>

@include('layout.notifications')

<!--/banner-section-->
<div id="demo-1" class="banner-inner">
    <div class="banner-inner-dott">
        <div class="header-top">

            @include('layout.header')
        </div>
        <div class="clearfix"></div>
        <!--banner-info-->
        <div class="banner-info">
            <h1><a href="{{ route('index.index') }}"><span class="logo-sub">{{ env('APP_NAME') }}</span> </a></h1>
            <h2>{{ env('INFO_SLOGAN') }}</h2>
            <p>{{ env('INFO_SLOGAN2') }}</p>

        </div>
        <!--//banner-info-->
    </div>
</div>
<!-- //sign-up-->
<div class="modal ab fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog sign" role="document">
        <div class="modal-content about">
            <div class="modal-header one">
                <button type="button" class="close sg" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="discount one">
                    <h3>Sign Up</h3>

                </div>
            </div>
            <div class="modal-body about">
                <div class="login-top sign-top one">
                    <form action="#" method="post">
                        <input type="text" name="name" class="name active" placeholder="Your Name" required="">
                        <input type="text" name="email" class="email" placeholder="Email" required="">
                        <input type="password" name="password" class="password" placeholder="Password" required="">
                        <input type="checkbox" id="brand1" value="">
                        <label for="brand1"><span></span> Remember me</label>
                        <div class="login-bottom one">
                            <ul>
                                <li>
                                    <a href="#">Forgot password?</a>
                                </li>
                                <li>

                                    <input type="submit" value="SIGN UP">

                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                    </form>
                </div>


            </div>
            <div class="social-icons">
                <ul>
                    <li><a href="#"><span class="icons"></span><span class="text">Facebook</span></a></li>
                    <li class="twt"><a href="#"><span class="icons"></span><span class="text">Twitter</span></a></li>
                    <li class="ggp"><a href="#"><span class="icons"></span><span class="text">Google+</span></a></li>
                </ul>
            </div>

        </div>
    </div>
</div>
<!-- //sign-up-->
<!--//banner-section-->
<!--/breadcrumb-->
<div class="service-breadcrumb w3-agile">
    <div class="container">
        <div class="wthree_service_breadcrumb_left">
            @yield('current-position')
        </div>
        <div class="wthree_service_breadcrumb_right">
            <h3>@yield('current-title', Auth::check() && Auth::user()->level === \App\User::LEVEL_ADMIN ? 'Administrador' : 'Usuario')</h3>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<!--//breadcrumb-->
<div class="single w3ls">
    <div class="container">
        <div class="col-md-12 single-left">
            @yield('content')

        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<!-- footer -->
<div class="footer">
    <div class="container">
        <div class="footer-grids">
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".5s">
                <h3>About Us</h3>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse.<span>Excepteur sint occaecat cupidatat
						non proident, sunt in culpa qui officia deserunt mollit.</span></p>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".6s">
                <h3>Contact Info</h3>
                <ul>
                    <li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>1234k Avenue, 4th block, <span>New York City.</span></li>
                    <li><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><a href="mailto:info@example.com">info@example.com</a></li>
                    <li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+1234 567 567</li>
                </ul>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".7s">
                <h3>Flickr Posts</h3>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/13.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/14.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/15.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/16.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/13.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/14.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/15.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/16.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="footer-grid-left">
                    <a href="#"><img src="images/13.jpg" alt=" " class="img-responsive" /></a>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".8s">
                <h3>Blog Posts</h3>
                <div class="footer-grid-sub-grids">
                    <div class="footer-grid-sub-grid-left">
                        <a href="#"><img src="images/11.jpg" alt=" " class="img-responsive" /></a>
                    </div>
                    <div class="footer-grid-sub-grid-right">
                        <h4><a href="#">culpa qui officia deserunt</a></h4>
                        <p>Posted On 25/3/2016</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="footer-grid-sub-grids">
                    <div class="footer-grid-sub-grid-left">
                        <a href="#"><img src="images/10.jpg" alt=" " class="img-responsive" /></a>
                    </div>
                    <div class="footer-grid-sub-grid-right">
                        <h4><a href="#">Quis autem vel eum iure</a></h4>
                        <p>Posted On 25/4/2016</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="footer-grid-sub-grids">
                    <div class="footer-grid-sub-grid-left">
                        <a href="#"><img src="images/15.jpg" alt=" " class="img-responsive" /></a>
                    </div>
                    <div class="footer-grid-sub-grid-right">
                        <h4><a href="#">Quis autem vel eum iure</a></h4>
                        <p>Posted On 25/5/2016</p>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="footer-logo animated wow slideInUp" data-wow-delay=".5s">
            <h2><a href="{{ route('index.index') }}">{{ env('APP_NAME') }} <span>{{ env('INFO_SLOGAN2') }}</span></a></h2>
        </div>
        <div class="copy-right animated wow slideInUp" data-wow-delay=".5s">
            <p>&copy {{ date('Y') }} {{ env('APP_NAME') }}. Todos los derechos reservados</p>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('js/jquery.light-carousel.js') }}"></script>
<script>
    $('.sample1').lightCarousel();
</script>
<link href="{{ asset('css/light-carousel.css') }}" rel="stylesheet" type="text/css">

<script src="{{ asset('js/bootstrap.js') }}"></script>
<script>
    /**
     * Consulta las publicaciones para el buscador
     *
     */
    function searchAutoComplete()
    {
        var words = $('#searchAutocomplete').val();

        if (words.length < 3) {
            loadAutoComplete([]);
            return;
        }

        $.ajax({
            url: '{{ route('index.publication.search') }}',
            data: {
                search: words
            },
            success: function(data) {
                loadAutoComplete(data);
            },
            error: function (err) {
                loadAutoComplete([]);
            }
        });
    }

    /**
     * Carga la data de autocompletado
     *
     * @param data
     */
    function loadAutoComplete(data)
    {
        var space = $('#spaceAutocomplete');
        space.css('display', 'none');

        if (data.length) {

            var html = '';
            var url = '{{ route('index.publication.searchWords') }}';
            for (var d in data) {
                html += '<p><a href="' + url + '?words=' + data[d].words + '">';
                html += data[d].label;
                html += '</a></p>';
            }

            space.css('display', 'block');
            space.html(html);
        }
    }
</script>
@yield('js')

</body>
</html>