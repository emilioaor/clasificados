@extends('layout.base')

@section('content')
    <h3>Cambio de contraseña</h3>

    <div class="sell-car w3l">
        <div class="container">
            <!--/sell-price-grids -->
            <div class="sell">

                <form action="{{ route('index.changePassword', ['token' => $token]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

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
                                        required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="password_confirmation">Confirmar nueva contraseña</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        placeholder="Confirmar nueva contraseña"
                                        maxlength="20"
                                        required>
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