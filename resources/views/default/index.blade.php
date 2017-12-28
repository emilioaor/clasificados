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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/myStyles.css') }}" />
    <!--/web-fonts-->
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
<div id="demo-1" data-zs-src='["{{ asset('img/1.jpg') }}", "{{ asset('img/2.jpg') }}", "{{ asset('img/3.jpg') }}", "{{ asset('img/4.jpg') }}", "{{ asset('img/5.jpg') }}"]'>
    <div class="demo-inner-content">
        <div class="header-top" id="nav">
            @include('layout.header')

            <div class="clearfix"></div>
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
                    <h3>Registro</h3>

                </div>
            </div>
            <div class="modal-body about">
                <div class="login-top sign-top one">
                    <form action="{{ route('index.register') }}" method="post">
                        {{ csrf_field() }}
                        <input type="text" name="name" class="name active" placeholder="Tu nombre" required="">
                        <input type="text" name="email" class="email" placeholder="Email" required="">
                        <input type="password" name="password" class="password" placeholder="Contraseña" required="">
                        <input type="password" name="password_confirmation" class="password" placeholder="Confirmar contraseña" required="">
                        <input type="text" name="phone" class="phone" placeholder="Telefono" required="">
                        <div class="login-bottom one">
                            <ul>
                                <li>

                                </li>
                                <li>

                                    <input type="submit" value="Registro">

                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
@include('layout.modalNotification')

@foreach($categoryGroups as $g => $group)
    <div class="featured_section_w3l">
        <div class="container">
            @if($g === 0)
                <h3 class="tittle">CATEGORIAS</h3>
            @endif
            <div class="inner_tabs">
                <div class="bs-example bs-example-tabs" role="tabpanel" >
                    <!-- Tabs -->
                    <ul id="myTab" class="nav nav-tabs" role="tablist">
                        @foreach($group as $c => $category)
                            <li role="presentation" class="{{ $c === 0 ? 'active' : null }}">
                                <a
                                        href="#category{{ $category->id }}"
                                        id="expeditions-tab{{ $category->id }}"
                                        role="tab"
                                        data-toggle="tab"
                                        aria-controls="expeditions"
                                        @if($c === 0)
                                            aria-expanded="true"
                                        @endif
                                        onclick="hideAndShowTab('.tab-pane{{ $g }}', '#category{{ $category->id }}')"
                                        >
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- /Tabs -->

                    <!-- Tab content -->
                    @foreach($group as $c => $category)
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane tab-pane{{ $g }} fade{{ $c === 0 ? ' in active' : '' }}" id="category{{ $category->id }}" aria-labelledby="expeditions-tab">
                                <div class="section__content clearfix">
                                    @foreach($category->getPublicationsPreview() as $p => $publication)
                                        <!-- /card1 -->
                                        <div class="card effect__hover" style="{{ $p > 2 ? 'margin-top : 300px' : '' }}">
                                            <div class="card__front">
                                          <span class="card__text">
                                               <div class="img-grid">
                                                   @if(count($publication->publicationImages))

                                                       <img src="{{ asset('uploads/publication/small/' . $publication->id . '/' . $publication->publicationImages[0]->url) }}" alt="{{ $category->name }}">
                                                   @else
                                                       <img src="{{ asset('img/camera.png') }}" alt="{{ $category->name }}">
                                                   @endif
                                                   <div class="car_description">
                                                       <h4><a href="single.html">{{ str_limit($publication->title, 10, '') }}</a></h4>
                                                       <div class="price"><span class="fa fa-rupee"></span><span class="font25">{{ \App\Publication::CURRENCY_SYMBOL . ' ' . $publication->getFormattedPrice() }}</span></div>
                                                       <p>{{ $publication->created_at->format('d-m-Y h:i a') }}</p>
                                                   </div>

                                               </div>
                                          </span>
                                            </div>
                                            <div class="card__back">
                                              <span class="card__text">
                                                 <div class="login-inner2">
                                                     <h4>{{ $publication->title }}</h4>
                                                     <div class="login-top sign-top">
                                                         <div class="row">
                                                             <div class="col-xs-12">
                                                                 <div class="form-group">
                                                                     <p>
                                                                         <i class="glyphicon glyphicon-phone"></i> {{ $publication->user->phone }}
                                                                     </p>
                                                                 </div>

                                                                 <div class="form-group">
                                                                     <p>
                                                                         <i class="glyphicon glyphicon-list"></i> {{ str_limit($publication->description, 200) }}
                                                                     </p>
                                                                 </div>

                                                                 <div class="form-group">
                                                                     <input type="button" value="Visualizar" onclick="location.href='{{ route('index.publication.show', ['publication' => $publication->public_id]) }}'">
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>

                                                 </div>
                                              </span>
                                            </div>
                                        </div>
                                        <!-- //card1 -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- /Tab content -->

                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="clearfix"></div>
@include('layout.footer')

<script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.zoomslider.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script>
    /**
     * Cree este metodo para corregir un error al generar
     * las tab dinamicamente
     *
     * @param hideClass
     * @param showId
     */
    function hideAndShowTab(hide, show)
    {
        $(hide).removeClass('in').removeClass('active');
        $(show).addClass('in').addClass('active');
    }

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

    /**
     * Marca leidas todas las notificaciones
     */
    function markAsRead()
    {
        $.ajax({
            method: 'post',
            url: '{{ route('user.notification.markRead') }}',
            data: {
                _token: '{{ csrf_token() }}'
            }
        });
    }

    $(document).on('scroll', function() {
        if ($(document).scrollTop() > 200) {
            $('#nav').addClass('in');
        } else {
            $('#nav').removeClass('in');
        }
    });
</script>

</body>
</html>