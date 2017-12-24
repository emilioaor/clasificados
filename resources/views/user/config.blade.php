@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'user.config'])
@endsection

@section('content')
    <h3>Configuración</h3>

    <div class="sell-car w3l">
        <div class="container">
            <!--/sell-price-grids -->
            <div class="sell">

                <form action="{{ route('user.configUpdate') }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="phone">Telefono</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="text"
                                        class="form-control"
                                        name="phone"
                                        id="phone"
                                        placeholder="Telefono de contacto"
                                        maxlength="50"
                                        value="{{ Auth::user()->phone }}"
                                        required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="current_password">Contraseña actual</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="password"
                                        class="form-control"
                                        name="current_password"
                                        id="current_password"
                                        placeholder="Contraseña actual"
                                        maxlength="20"
                                        >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="password">Nueva contraseña</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        id="password"
                                        placeholder="Nueva contraseña"
                                        maxlength="20"
                                        >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="password_confirmation">Confirmación de contraseña</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        placeholder="Confirmación de contraseña"
                                        maxlength="20"
                                        >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 value-button">
                                <input type="submit" value="Actualizar">
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection