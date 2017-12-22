@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'publication.index'])
@endsection

@section('content')
    <h3>Publicaciones</h3>
    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Tipo</th>
                <th>Categoría</th>
                <th>Estatus</th>
                <th>Precio</th>
                <th width="5%"></th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            @if(count($publications))

                @foreach($publications as $publication)
                    <tr>
                        <td>{{ $publication->public_id }}</td>
                        <td>{{ $publication->title }}</td>
                        <td>
                            @if($publication->isPaid())
                                <span class="bg-success text-success">Pago</span>
                            @else
                                <span class="bg-warning text-warning">Gratuito</span>
                            @endif
                        </td>
                        <td>{{ $publication->category->name }}</td>
                        <td>
                            @if($publication->status === \App\Publication::STATUS_PUBLISHED)
                                <span class="bg-success text-success">Publicado</span>
                            @elseif($publication->status === \App\Publication::STATUS_HIDDEN)
                                <span class="bg-warning text-warning">Borrador</span>
                            @endif
                        </td>
                        <td>{{ $publication->getFormattedPrice() }} {{ \App\Publication::CURRENCY_SYMBOL }}</td>
                        <td>
                            <button class="btn-warning" title="Editar" onclick="location.href = '{{ route('publication.edit', ['publication' => $publication->public_id]) }}'">
                                <i class="glyphicon glyphicon-edit"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn-primary" title="Vista previa" onclick="window.open('{{ route('index.publication.show', ['publication' => $publication->public_id]) }}', '_Blank')">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">Sin publicaciones, agrega una <a href="{{ route('publication.create') }}">nueva</a></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="text-center">
        {{ $publications->render() }}
    </div>
@endsection