@extends('layout.base')

@section('css')
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
@endsection

@section('current-position')
    @include('layout.nav', ['selected' => 'publication.create'])
@endsection

@section('content')
    <h3>Crea una publicación</h3>

    <div class="sell-car w3l">
        <div class="container">
            <!--/sell-price-grids -->
            <div class="sell">

                <div class="row space-plans">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <h5>Selecciona tu plan</h5>
                        </div>

                        <div class="col-md-4 pricing-plans">
                            <div class="pricing-plan1" id="plan-basic">
                                <h4>Basico</h4>
                                <h5>Gratis</h5>
                                <ul>
                                    <li>Hasta 4 fotos</li>
                                    <li>Valido por 30 dias</li>
                                </ul>
                                <div class="value-button">
                                    <input type="button" value="Seleccionar" onclick="$('#publishForm').submit()">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pricing-plans">
                            <div class="pricing-plan1" id="plan-premium">
                                <h4>Premium</h4>
                                <h5><sup>$</sup> 10</h5>
                                <ul>
                                    <li>Hasta 6 fotos y 1 video</li>
                                    <li>Tiempo ilimitado</li>
                                </ul>
                                <div id="paypal-button"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('publication.store') }}" method="post" id="publishForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="transaction" id="transaction">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="title">Titulo</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Titulo de la publicación" maxlength="50" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="title">Categoría</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="category_id" id="category_id" class="form-control" onchange="changeCategory()" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="subCategoriesSpace"></div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="price">Precio</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="price" id="price" placeholder="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="price">Descripción</label>
                            </div>
                            <div class="col-sm-5">

                            <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    placeholder="Descripción de la publicación"
                                    rows="5"
                                    maxlength="600"
                                    required
                                    ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 value-button">
                                <input type="button" value="Siguiente" id="publishButton" onclick="verifyPlan()">
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        paypal.Button.render({
            env: '{{ env('APP_ENV') === 'production' ? 'production' : 'sandbox' }}', // Or 'sandbox',

            commit: true, // Show a 'Pay Now' button

            client: {
                sandbox:    'AUQ0NPbGLi30YZi9130zPk-EU7U4nH0C1BbC1ctBf46npA_dVO_-dvL2whlhfJ_DkjLKfwhmLgER2-T7',
                production: 'xxxxxxxxx'
            },

            style: {
                color: 'black',
                size: 'medium'
            },

            payment: function(data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: { total: '10.00', currency: 'USD' }
                            }
                        ]
                    }
                });
            },

            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function(payment) {
                    console.log(payment.state);
                    if (payment.state === 'approved') {
                        $('#transaction').val(payment.id);
                        $('#publishForm').submit();
                    }

                });
            },

            onCancel: function(data, actions) {
                console.log('Cancelado');
            },

            onError: function(err) {
                alert('Error de comunicación, intente de nuevo.');
            }
        }, '#paypal-button');
    </script>
    <script>
        var response = JSON.parse('{!! $categoriesArray !!}');
        var categories = response.categories;

        /**
         *  Verifica la Categoria actual para renderizar las
         *  sub catorias correspondientes
         */
        function changeCategory()
        {
            var category = $('#category_id').val();
            var currentCategory;
            $('#subCategoriesSpace').html('');

            for (var c in categories) {
                currentCategory = categories[c];

                if (currentCategory['id'] == category) {
                    renderSubCategories(currentCategory['subCategories']);
                }
            }
        }

        /**
         * Renderiza la subCategorias
         * @param subCategories
         */
        function renderSubCategories(subCategories)
        {
            var html = '';
            var current;
            var option;

            for (var sc in subCategories) {
                current = subCategories[sc];

                html += '<div class="form-group">';
                html +=     '<div class="row">';
                html +=         '<div class="col-sm-3">';
                html +=             '<label for="">' + current['name'] + '</label>';
                html +=         '</div>';
                html +=         '<div class="col-sm-5">';

                html +=             '<select name="options[]" class="form-control" required>';

                for (var o in current['options']) {
                    // Agrega las opciones
                    option = current['options'][o];
                    if (option['selected']) {
                        html +=             '<option value="' + option['id'] + '" selected>' + option['name'] + '</option>';
                    } else {
                        html +=             '<option value="' + option['id'] + '">' + option['name'] + '</option>';
                    }
                }

                html +=             '</select>';
                html +=         '</div>';
                html +=     '</div>';
                html += '</div>';
            }

            $('#subCategoriesSpace').html(html);
        }

        changeCategory();

        /**
         * Verifica el plan seleccionada para ejecutar la accion
         * correspondiente(Pago o registro)
         *
         * @returns {boolean}
         */
        function verifyPlan()
        {
            var title = $('#title').val();
            var price = $('#price').val();
            var description = $('#description').val();
            $('#title').css('border-color', '#555');
            $('#price').css('border-color', '#555');
            $('#description').css('border-color', '#555');

            if (title === '') $('#title').css('border-color', '#e16b5b');
            if (price === '') $('#price').css('border-color', '#e16b5b');
            if (description === '') $('#description').css('border-color', '#e16b5b');

            if (title !== '' && price !== '' && description !== '') {
                $('.space-plans').css('display', 'block');
                $('#publishForm').css('display', 'none');
            }
        }
    </script>
@endsection