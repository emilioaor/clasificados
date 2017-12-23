@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => ''])
@endsection

@section('content')
    <div class="tips w3l">
        <div class="container">
            <div class="col-md-9 tips-info">
                @foreach($publications as $publication)
                    <div class="news-grid">
                        <div class="news-img up">
                            <a href="{{ route('index.publication.show', ['publication' => $publication->public_id]) }}">
                                @if(count($publication->publicationImages))
                                    <img
                                            src="{{ asset('uploads/publication/small/' . $publication->id . '/' . $publication->publicationImages[0]->url) }}"
                                            alt="{{ $publication->title }}"
                                            class="img-responsive">
                                @else
                                    <img
                                            src="{{ asset('img/camera.png') }}"
                                            alt="{{ $publication->title }}"
                                            class="img-responsive">
                                @endif
                            </a>
                        </div>
                        <div class="news-text coming">
                            <h3>
                                <a href="{{ route('index.publication.show', ['publication' => $publication->public_id]) }}">
                                    {{ $publication->title }}
                                </a>
                            </h3>
                            <h5>{{ \App\Publication::CURRENCY_SYMBOL . $publication->getFormattedPrice() }}</h5>
                            <p class="news">{{ $publication->created_at->format('d-m-Y') }}</p>
                            <h6>Categoria:<a href=""> {{ $publication->category->name }}</a></h6>
                            <a href="{{ route('index.publication.show', ['publication' => $publication->public_id]) }}" class="read hvr-shutter-in-horizontal">
                                Visualizar
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                @endforeach

                {{ $publications->links() }}
            </div>
            <div class="col-md-3 advice-right w3-agile">

                <div class="blo-top1">
                    <div class="agileits_twitter_posts">
                        <h4>Publicidad</h4>
                    </div>
                </div>

                <div class="blo-top1">
                    <div class="agileits_twitter_posts">
                        <h4>Publicidad</h4>
                    </div>
                </div>

            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
@endsection