@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'publication.show'])
@endsection

@section('css')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
    <div class="single w3ls">
        <div class="container">
            <div class="col-md-8 single-left">
                <div class="sample1">
                    <div class="carousel" style="height: 341px;">
                        <ul>
                            @if(count($publication->publicationImages))
                                @foreach($publication->publicationImages as $key => $image)
                                    <li class="{{ $key === 0 ? 'current' : '' }}">
                                        <img src="{{ asset('uploads/publication/normal/' . $publication->id . '/' . $image->url) }}" alt="">
                                    </li>
                                @endforeach
                            @else
                                <li>
                                    <img src="{{ asset('img/camera.png') }}" alt="">
                                </li>
                            @endif
                        </ul>
                        <div class="controls">
                            <div class="prev"></div>
                            <div class="next"></div>
                        </div>
                    </div>
                    <div class="thumbnails">
                        <ul>
                            @foreach($publication->publicationImages as $key => $image)
                                <li class="{{ $key === 0 ? 'selected' : '' }}" style="width: {{ count($publication->publicationImages) > 3 ? (100 / count($publication->publicationImages)) : 25 }}%">
                                    <div><img src="{{ asset('uploads/publication/small/' . $publication->id . '/' . $image->url) }}" alt=" "></div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="single-left2">
                    <h3>{{ $publication->title }}</h3>
                    <ul class="com">
                        <li><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><a>{{ $publication->user->phone }}</a></li>
                        <li><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><a>{{ count($publication->comments) }} Comentarios</a></li>

                        <p class="price">{{ \App\Publication::CURRENCY_SYMBOL . $publication->getFormattedPrice() }}</p>
                    </ul>
                    @if($publication->hash_tags)
                        <div class="single-left2-sub">
                            <ul>
                                <li>Tags:</li>
                                @foreach($publication->hash_tags as $tag)
                                    <li><a>{{ $tag }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(count($publication->subCategoryOptions))
                        <div class="row">
                            @foreach($publication->subCategoryOptions as $option)
                                <div class="single-left2-sub2 col-sm-6">
                                    <h4>{{ $option->subCategory->name }}: <span>{{ $option->name }}</span></h4>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="single-left3">
                    @if(! empty($publication->video))
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="560" height="315" src="{{ $publication->video }}" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                        </div>
                    @endif

                    <p>{{ $publication->description }}</p>
                </div>
                <div class="single-left4">
                    <h4>Compartir</h4>
                    <ul class="social-icons social-icons1">
                        <li><a href="#" class="icon icon-border icon-border1 facebook"></a></li>
                        <li><a href="#" class="icon icon-border icon-border1 twitter"></a></li>
                        <li><a href="#" class="icon icon-border icon-border1 instagram"></a></li>
                        <li><a href="#" class="icon icon-border icon-border1 pinterest"></a></li>
                    </ul>
                </div>
                <div class="comments agile-info">
                    <h4>Comentarios</h4>
                    @foreach($comments as $comment)
                        <div class="comments-grid">
                            <div class="comments-grid-left">
                                <i class="glyphicon glyphicon-user"></i>
                            </div>
                            <div class="comments-grid-right">
                                <h3><a>{{ $comment->user->name }}</a></h3>
                                <h5><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> {{ $comment->created_at->format('d-m-Y h:i a') }}</h5>
                                <p>{{ $comment->comment }}</p>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    @endforeach

                    @if(Auth::check())
                        <form action="{{ route('publication.addComment', ['publication' => $publication->public_id]) }}" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <textarea
                                                name="comment"
                                                id="inputComment"
                                                cols="30"
                                                rows="5"
                                                class="form-control"
                                                maxlength="200"
                                                placeholder="Comentario ..."
                                                ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_CATCHA_PUBLIC') }}"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12 value-button">
                                        <input type="submit" value="Comentar">
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

            </div>
            <div class="col-md-4 single-right">
                <div class="blo-top">
                    <div class="tech-btm">
                        <h4>Ubicación</h4>
                        <div id="map"></div>
                        <script>
                            var map;
                            var marker;
                            function initMap() {
                                map = new google.maps.Map(document.getElementById('map'), {
                                    center: {!! $publication->location !!},
                                    zoom: 8
                                });
                                marker = new google.maps.Marker({
                                    position: {!! $publication->location !!},
                                    map: map,
                                    draggable: false
                                });
                            }
                        </script>
                    </div>
                </div>
                <div class="blo-top1">
                    <div class="agileits_twitter_posts tech-btm">
                        <h4>Publicidad</h4>

                    </div>
                </div>
                <div class="related-posts">
                    <h3>Relacionadas</h3>

                    @foreach($relatedPosts as $post)
                        <div class="related-post">
                            <div class="related-post-left">
                                <a href="{{ route('publication.show', ['publication' => $post->public_id]) }}">
                                    @if(count($post->publicationImages))
                                        <img
                                            src="{{ asset('uploads/publication/small/' . $post->id . '/' . $post->publicationImages[0]->url) }}"
                                            alt="{{ $post->title }}"
                                            class="img-responsive">
                                    @else
                                        <img
                                            src="{{ asset('img/camera.png') }}"
                                            alt="{{ $post->title }}"
                                            class="img-responsive">
                                    @endif
                                </a>
                            </div>
                            <div class="related-post-right">
                                <h4><a href="{{ route('publication.show', ['publication' => $post->public_id]) }}">{{ str_limit($post->title, 15, '..') }}</a></h4>
                                <p>{{ str_limit($post->description, 15, '..') }}</p>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    @endforeach
                </div>
                <div class="blo-top1">
                    <div class="agileits_twitter_posts tech-btm">
                        <h4>Publicidad</h4>

                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap" async defer></script>
@endsection