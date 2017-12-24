@extends('layout.base')

@section('content')
    <h3>Recuperar contraseña</h3>

    <div class="sell-car w3l">
        <div class="container">
            <!--/sell-price-grids -->
            <div class="sell">

                <form action="{{ route('index.emailPasswordReset') }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        id="email"
                                        placeholder="Email del usuario"
                                        maxlength="60"
                                        required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 value-button">
                                <input type="submit" value="Recuperar">
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection