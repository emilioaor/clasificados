<!-- footer -->
<div class="footer">
    <div class="container">
        <div class="footer-grids">
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".5s">
                <h3>Información</h3>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse.<span>Excepteur sint occaecat cupidatat
						non proident, sunt in culpa qui officia deserunt mollit.</span></p>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".6s">
                <h3>Contacto</h3>
                <ul>
                    <li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>1234k Avenue, 4th block, <span>New York City.</span></li>
                    <li><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><a href="mailto:info@example.com">info@example.com</a></li>
                    <li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+1234 567 567</li>
                </ul>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".7s">
                <h3>Recientes</h3>
                @inject('publicationRecent', 'App\Publication')
                @foreach($publicationRecent::getRecent(9) as $recent)
                    <div class="footer-grid-left">
                        <a href="{{ route('index.publication.show', ['publication' => $recent->public_id]) }}">
                            @if(count($recent->publicationImages))
                                <img
                                        src="{{ asset('uploads/publication/small/' . $recent->id . '/' . $recent->publicationImages[0]->url) }}"
                                        class="img-responsive"
                                        alt="{{ $recent->title }}"
                                        title="{{ $recent->title }}"
                                        />
                            @else
                                <img
                                        src="{{ asset('img/camera.png') }}"
                                        class="img-responsive"
                                        alt="{{ $recent->title }}"
                                        title="{{ $recent->title }}"
                                        />
                            @endif
                        </a>
                    </div>
                @endforeach
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 footer-grid animated wow slideInLeft" data-wow-delay=".8s">
                <h3>Comentarios</h3>
                @inject('commentRecent', 'App\Comment')
                @foreach($commentRecent::getRecent(3) as $recent)
                    <div class="footer-grid-sub-grids">
                        <div class="footer-grid-sub-grid-left">
                            <a href="{{ route('index.publication.show', ['publication' => $recent->public_id]) }}">
                                @if( ($commentPublication = \App\Publication::find($recent->id)) && count($commentPublication->publicationImages) )
                                    <img
                                            src="{{ asset('uploads/publication/small/' . $commentPublication->id . '/' . $commentPublication->publicationImages[0]->url) }}"
                                            alt="{{ $recent->title }}"
                                            title="{{ $recent->title }}"
                                            class="img-responsive"
                                            />
                                @else
                                    <img
                                            src="{{ asset('img/camera.png') }}"
                                            class="img-responsive"
                                            alt="{{ $recent->title }}"
                                            title="{{ $recent->title }}"
                                            />
                                @endif
                            </a>
                        </div>
                        <div class="footer-grid-sub-grid-right">
                            <h4>
                                <a href="{{ route('index.publication.show', ['publication' => $recent->public_id]) }}">
                                    {{ str_limit($recent->comment, 25) }}
                                </a>
                            </h4>
                            <p>{{ $recent->name }}</p>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                @endforeach
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