@if(Auth::check() && Auth::user()->level === \App\User::LEVEL_USER)
    <!-- //notificaciones-->
    <div class="modal ab fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog sign" role="document">
            <div class="modal-content about">
                <div class="modal-header one">
                    <button type="button" class="close sg" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="discount one">
                        <h3>Notificaciones</h3>

                    </div>
                </div>
                <div class="modal-body about">
                    @if(count(Auth::user()->notifications))
                        <!-- Notificaciones -->
                        @foreach(Auth::user()->lastNotifications(5) as $publication)
                            <div class="form-group {{ $publication->pivot->status === \App\User::STATUS_NOTIFICATION_UNREAD ? 'bg-success' : '' }}">
                                <div class="row">
                                    <div class="col-xs-4">
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

                                    <div class="col-xs-8">
                                        <p>
                                            El usuario
                                            <strong>{{ $publication->user->email }}</strong>
                                            ha publicado contenido que te puede interesar:
                                            <strong><a href="{{ route('index.publication.show', ['publication' => $publication->public_id]) }}">{{ $publication->title }}</a></strong>,
                                            el : <strong>{{ $publication->created_at->format('d-m-Y') }}</strong>
                                            a las : <strong>{{ $publication->created_at->format('h:i a') }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        <p>No hay notificaciones</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif