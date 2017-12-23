@extends('layout.base')

@section('current-position')
    @include('layout.nav', ['selected' => 'user.whistList.index'])
@endsection

@section('content')
    <h3>Lista de deseos</h3>
    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Categoría</th>
                <th>Vendedor</th>
                <th>Telefono</th>
                <th>Precio</th>
                <th width="5%"></th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            @if(count($whistList))

                @foreach($whistList as $publication)
                    <tr>
                        <td>{{ $publication->public_id }}</td>
                        <td>{{ $publication->title }}</td>
                        <td>{{ $publication->category->name }}</td>
                        <td>{{ $publication->user->name }}</td>
                        <td>{{ $publication->user->phone }}</td>
                        <td>{{ \App\Publication::CURRENCY_SYMBOL }}{{ $publication->getFormattedPrice() }}</td>
                        <td>
                            <button class="btn-primary" title="Vista previa" onclick="window.open('{{ route('index.publication.show', ['publication' => $publication->public_id]) }}', '_Blank')">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </button>
                        </td>
                        <td>
                            <form action="{{ route('user.whistList.removePublication', ['publication' => $publication->public_id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="route" value="user.whistList.index">
                                <button class="btn-danger" title="Remover" onclick="return confirm('¿Seguro quiere eliminar esta publicación de su lista de deseos?')">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">Sin publicaciones en lista de deseos</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="text-center">
        {{ $whistList->render() }}
    </div>
@endsection