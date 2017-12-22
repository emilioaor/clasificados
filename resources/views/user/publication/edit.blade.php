@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'publication.edit'])
@endsection

@section('content')
    <h3>Editar publicación</h3>

    <div class="sell-car w3l">
        <div class="container">
            <!--/sell-price-grids -->
            <div class="sell">

                <form action="{{ route('publication.update', ['publication' => $publication->public_id]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    @if($publication->status !== APP\Publication::STATUS_PUBLISHED)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-8">
                                    <p>
                                        Recuerda que tu publicación no sera accesible hasta que cambies su estatus a 'publicado'
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="status">Estatus</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="status" id="status" class="form-control">
                                    <option
                                            value="{{ \App\Publication::STATUS_PUBLISHED }}"
                                            {{ $publication->status === \App\Publication::STATUS_PUBLISHED ? 'selected' : null }}
                                            >
                                        Publicado
                                    </option>
                                    <option
                                            value="{{ \App\Publication::STATUS_HIDDEN }}"
                                            {{ $publication->status === \App\Publication::STATUS_HIDDEN ? 'selected' : null }}
                                            >
                                        Borrador
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="title">Titulo</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="text"
                                        class="form-control"
                                        name="title" id="title"
                                        placeholder="Titulo de la publicación"
                                        maxlength="50"
                                        required
                                        value="{{ $publication->title }}"
                                    >
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
                                        @if($category->id === $publication->category_id)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="subCategoriesSpace">

                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="price">Precio</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="number"
                                        class="form-control"
                                        name="price"
                                        id="price"
                                        placeholder="0"
                                        required
                                        value="{{ $publication->price }}"
                                        >
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
                                    style="resize: none;"
                                    maxlength="600"
                                    required
                                    >{{ $publication->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    @if($publication->isPaid())
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="video">Enlace hacia video</label>
                                </div>
                                <div class="col-sm-5">
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="video"
                                            id="video"
                                            placeholder="https://www.youtube.com"
                                            value="{{ $publication->video }}"
                                            >
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="tags">#Hashtags</label>
                            </div>
                            <div class="col-sm-5">
                                <input
                                        type="text"
                                        id="tags"
                                        name="tags"
                                        class="form-control"
                                        placeholder="#hashtags separados por coma(,)"
                                        onkeyup="addHashTag(event)"
                                        >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8">
                            <div class="single-left2-sub space-hashtags">

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

                <hr>
                <h3>Imagenes</h3>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <p>Dimensiones permitidas: ancho entre 1000px y 3000px, alto entre 500px y 1600px</p>
                        </div>
                    </div>
                </div>

                <div class="form-group select-image">
                    <div class="row">

                        <!-- Lista de imagenes -->
                        @foreach($publication->publicationImages as $image)
                            <div class="col-sm-3">
                                <img src="{{ asset('uploads/publication/small/' . $publication->id . '/' . $image->url) }}" alt="{{ $image->url }}">

                                <form action="{{ route('publication.deleteImage') }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <input type="hidden" name="publicationImageId" value="{{ $image->id }}">
                                    <button class="btn-danger">
                                        <i class="glyphicon glyphicon-remove-sign"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach

                        @if(! $publication->hasMaxImages())
                            <!-- Cargar una imagen -->
                            <div class="col-sm-3">
                                <img src="{{ asset('img/camera.png') }}" alt="Selecciona una imagen" class="new" onclick="selectImageFile()">
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Cargar una imagen -->
                <div class="select-image">
                    <form action="{{ route('publication.uploadImage', ['publication' => $publication->public_id]) }}" enctype="multipart/form-data" method="post" id="imageForm">
                        {{ csrf_field() }}
                        <input type="file" id="selectImage" name="newImage"  onchange="uploadImage()">
                        <input type="hidden" name="publication_id" value="{{ $publication->id }}">
                    </form>
                </div>

                <hr>
                <h3>Ubicación</h3>
                <div class="row">
                    <div class="col-xs-12">
                        <p>Da una pista de tu ubicación a los interesados</p>
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
                                    draggable: true
                                });
                            }
                        </script>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 value-button">
                            <input type="button" value="Guardar ubicación" onclick="savePosition();" id="savePositionButton">

                            <form action="{{ route('publication.updatePosition', ['publication' => $publication->public_id]) }}" method="post" id="positionForm">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="lng" id="lng">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        var response = JSON.parse('{!! $categoriesArray !!}');
        var categories = response.categories;
        var hashTags = {!! json_encode($publication->hash_tags) !!};

        /**
         *  Verifica la Categoria actual para renderizar las
         *  sub catorias correspondientes
         */
        function changeCategory()
        {
            var category = $('#category_id').val();
            var currentCategory;
            $('#subCategoriesSpace').html('')

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
         * Selecciona una imagen para subirla
         */
        function selectImageFile()
        {
            $('#selectImage').click();
        }

        /**
         * Carga la imagen
         */
        function uploadImage()
        {
            $('#imageForm').submit();
        }

        /**
         * Guarda la ubicacion del mapa
         */
        function savePosition()
        {
            if (map && marker) {
                $('#lat').val(marker.position.lat());
                $('#lng').val(marker.position.lng());
                $('#positionForm').submit();
            }
        }

        /**
         * Agrega un #hashtag
         */
        function addHashTag(event)
        {
            // Enter o coma (,) o espacio
            if (event.keyCode === 13 || event.keyCode === 188  || event.keyCode === 32) {
                var tag = $('#tags').val();
                if (tag != null && tag != '') {
                    tag = tag.substr(0, tag.length - 1);
                    if (tag[0] !== '#') {
                        tag = '#' + tag;
                    }
                    hashTags.push(tag);
                    renderHashTags();
                }
                $('#tags').val('');
            }
        }
        /**
         * remueve un #hashtag
         */
        function removeHashTag(i)
        {
            hashTags.splice(i, 1);
            renderHashTags();
        }

        /**
         * Renderiza los hashtags
         */
        function renderHashTags()
        {
            var html = '';
            html += '<ul>';
            html +=     '<li></li>';

            for (var h in hashTags) {
                html += '<li>';
                html +=     '<a href="JavaScript:removeHashTag(' + h + ')">' + hashTags[h] + '</a>';
                html +=     '<input type="hidden" name="hash_tags[]" value="' + hashTags[h] + '">';
                html += '</li>';
            }

            html += '</ul>';

            $('.space-hashtags').html(html);
        }

        // Renderiza los hashtags de backend
        renderHashTags();
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap" async defer></script>
@endsection