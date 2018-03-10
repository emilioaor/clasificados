@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'admin.index'])
@endsection

@section('content')
    <h3>Configuración de categorías</h3>
    <div class="insurance-agile-its">
        <div class="container">
            <div class="insurance-info">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title asd">
                                <a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="{{ $collapse == '1' ? 'true' : 'false' }}" aria-controls="collapseOne">Categorias<span class="glyphicon glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse {{ $collapse == '1' ? 'in' : '' }}" role="tabpanel" aria-labelledby="headingOne" {{ $collapse == '1' ? '' : 'style="height: 0px;"' }}>
                            <div class="panel-body panel_text">
                                <h4>Agrega todas las categorias que estarán disponibles para las publicaciones</h4>

                                <!-- Lista de categorias -->
                                <div class="row">
                                    @foreach($categories as $category)
                                        @if($category->status === \App\Category::STATUS_ACTIVE)
                                            <div class="col-sm-6">

                                                <form action="{{ route('admin.updateCategory', ['category' => $category->id]) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PUT') }}
                                                    <input type="hidden" name="collapse" value="1">

                                                    <div class="row">
                                                        <div class="col-xs-5">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">

                                                        </div>
                                                        <div class="col-xs-2">
                                                            <div class="form-group text-center">
                                                                <button class="btn btn-default">
                                                                    <i class="glyphicon glyphicon-ok"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <div class="form-group text-center">
                                                                <a href="" class="btn btn-danger" onclick="return confirm('¿Desea eliminar esta categoria?')">
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="contact-form w3-agile">

                                    <form action="{{ route('admin.addCategory') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="collapse" value="1">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="name">Nueva categoria</label>
                                                <input type="text" Name="name" id="name" class="input-100" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="value-button">
                                                    <input type="submit" value="Guardar">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="clearfix"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title asd">
                                <a class="pa_italic collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="{{ $collapse == '2' ? 'true' : 'false' }}" aria-controls="collapseTwo">Sub-categorias<span class="glyphicon glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse {{ $collapse == '2' ? 'in' : '' }}" role="tabpanel" aria-labelledby="headingTwo" {{ $collapse == '2' ? '' : 'style="height: 0px;"' }}>
                            <div class="panel-body panel_text">
                                <h4>
                                    Las sub-categorías son especificaciones que debera agregar el usuario sobre la categoría seleccionada. Por ejemplo: categoría vehiculo, sub-categoria marca
                                </h4>

                                <!-- Lista de sub-categorias -->
                                <div class="row">
                                    @foreach($categories as $c)

                                        @if(count($c->subCategories))
                                            <div class="col-xs-12">
                                                <hr>
                                                <h5>{{ $c->name }}</h5>
                                                <hr>
                                            </div>
                                        @endif

                                        @foreach($c->subCategories as $subCategory)
                                            <div class="col-sm-6">

                                                <form action="{{ route('admin.updateSubCategory', ['category' => $subCategory->id]) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PUT') }}
                                                    <input type="hidden" name="collapse" value="2">

                                                    <div class="row">
                                                        <div class="col-xs-5">
                                                            <div class="form-group">
                                                                <select name="category_id" class="form-control">
                                                                    @foreach($categories as $category)
                                                                        @if($subCategory->category_id == $category->id)
                                                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                                                        @else
                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="name" value="{{ $subCategory->name }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-2">
                                                            <div class="form-group text-center">
                                                                <button class="btn btn-default">
                                                                    <i class="glyphicon glyphicon-ok"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="contact-form w3-agile">

                                    <form action="{{ route('admin.addSubCategory') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="collapse" value="2">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label for="name">Categoria</label>
                                                <select name="category_id" class="input-100">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for="name">Sub-categoria</label>
                                                <input type="text" Name="name" id="name" class="input-100" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="value-button">
                                                    <input type="submit" value="Guardar">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title asd">
                                <a class="pa_italic collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="{{ $collapse == '3' ? 'true' : 'false' }}" aria-controls="collapseThree">Opciones <span class="glyphicon glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse {{ $collapse == '3' ? 'in' : '' }}" role="tabpanel" aria-labelledby="headingThree" {{ $collapse == '1' ? '' : 'style="height: 0px;"' }}>
                            <div class="panel-body panel_text">
                                <h4>
                                    Estas son las opciones permitidas para una sub-categoria. Por ejemplo: categoria vehiculo, sub-categoria marca, opciones Nissan, Ford, etc
                                </h4>

                                <!-- Lista de opciones -->
                                <div class="row">
                                    @foreach($categories as $c)
                                        @foreach($c->subCategories as $sc)

                                            @if(count($sc->subCategoryOptions))
                                                <div class="col-xs-12">
                                                    <hr>
                                                    <h5>{{ $c->name . ' - ' .$sc->name }}</h5>
                                                    <hr>
                                                </div>
                                            @endif

                                            @foreach($sc->subCategoryOptions as $option)
                                                <div class="col-sm-6">

                                                    <form action="{{ route('admin.updateOption', ['option' => $option->id]) }}" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}
                                                        <input type="hidden" name="collapse" value="3">

                                                        <div class="row">
                                                            <div class="col-xs-5">
                                                                <div class="form-group">
                                                                    <select name="sub_category_id" class="form-control">
                                                                        @foreach($categories as $category)
                                                                            @foreach($category->subCategories as $sub)
                                                                                @if($option->sub_category_id == $sub->id)
                                                                                    <option value="{{ $sub->id }}" selected>{{ $sub->name }}</option>
                                                                                @else
                                                                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="name" value="{{ $option->name }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-2">
                                                                <div class="form-group text-center">
                                                                    <button class="btn btn-default">
                                                                        <i class="glyphicon glyphicon-ok"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="contact-form w3-agile">

                                    <form action="{{ route('admin.addOption') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="collapse" value="3">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label for="name">Sub-categoria</label>
                                                <select name="sub_category_id" class="input-100">
                                                    @foreach($categories as $c)
                                                        @foreach($c->subCategories as $category)
                                                            <option value="{{ $category->id }}">{{ $c->name . ' - ' . $category->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for="name">Opción</label>
                                                <input type="text" Name="name" id="name" class="input-100" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="value-button">
                                                    <input type="submit" value="Guardar">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection