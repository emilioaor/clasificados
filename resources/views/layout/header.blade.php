<div class="search-box">
    <div class="search-autocomplete">
        <input class="typeahead tt-query" id="searchAutocomplete" onkeyup="searchAutoComplete()" placeholder="Ingrese su busqueda..." spellcheck="false" autocomplete="off">
        <span class="glyphicon glyphicon-search"> </span>

        <div id="spaceAutocomplete" class="autocomplete">

        </div>
    </div>

    <ul>
        @if(Auth::check() && Auth::user()->level === \App\User::LEVEL_USER)
            <li>
                <a href="#" data-toggle="modal" data-target="#notificationModal" onclick="markAsRead()">
                    <span class="glyphicon glyphicon-cloud"></span>
                    Notificaciones
                </a>
            </li>
            <li>
                <a href="{{ route('user.whistList.index') }}">
                    <span class="glyphicon glyphicon-gift"></span>
                    Lista de deseos
                </a>
            </li>
        @endif
        <li>
            @if(Auth::check())
                @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                    <a href="{{ route('admin.index') }}"><span class="glyphicon glyphicon-home"></span> Panel</a>
                @else
                    <a href="{{ route('publication.index') }}"><span class="glyphicon glyphicon-blackboard"></span> Publicaciones</a>
                @endif
            @else
                <a href="#" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-list-alt"></span> Registro</a>
            @endif
        </li>
        <li>
            @if(Auth::check())
                <a href="{{ route('index.logout') }}" id="showRight" class="navig"><span class="glyphicon glyphicon-log-out"></span>Salir </a>
            @else
                <button id="showRight" class="navig"><span class="glyphicon glyphicon-log-in"></span>Login </button>
                <div class="cbp-spmenu-push">
                    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
                        <h3>Login</h3>
                        <div class="login-inner">
                            <div class="login-top">
                                <form action="{{ route('index.login') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="text" name="email" class="email" placeholder="Email" required=""/>
                                    <input type="password" name="password" class="password" placeholder="Contraseña" required=""/>

                                    <div class="login-bottom">
                                        <ul>
                                            <li>
                                                <a href="{{ route('index.passwordReset') }}">Olvide mi contraseña</a>
                                            </li>
                                            <li>
                                                <input type="submit" value="LOGIN"/>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                                <div class="clearfix"></div>

                            </div>
                        </div>
                    </nav>
                </div>
                <script src="{{ asset('js/classie2.js') }}"></script>
                <script>
                    var menuRight = document.getElementById( 'cbp-spmenu-s2' ),
                            showRight = document.getElementById( 'showRight' ),
                            body = document.body;

                    showRight.onclick = function() {
                        classie.toggle( this, 'active' );
                        classie.toggle( menuRight, 'cbp-spmenu-open' );
                        disableOther( 'showRight' );
                    };

                    function disableOther( button ) {
                        if( button !== 'showRight' ) {
                            classie.toggle( showRight, 'disabled' );
                        }
                    }
                </script>
                <!--Navigation from Right To Left-->
            @endif
        </li>
    </ul>

</div>