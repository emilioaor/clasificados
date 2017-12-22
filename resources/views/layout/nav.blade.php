<ul>
    @if(Auth::check())
        @if(Auth::user()->level === App\User::LEVEL_ADMIN)

                <!-- Administrador -->
        <li>
            @if($selected === 'admin.index')
                <i class="glyphicon glyphicon-tasks"></i> Categorías
            @else
                <a href="{{ route('admin.index') }}">
                    <i class="glyphicon glyphicon-tasks"></i> Categorías
                </a>
            @endif
            <i>|</i>
        </li>

        @else

                <!-- Usuario -->
        <li>
            @if($selected === 'publication.index')
                <i class="glyphicon glyphicon-blackboard"></i> Publicaciones
            @else
                <a href="{{ route('publication.index') }}">
                    <i class="glyphicon glyphicon-blackboard"></i> Publicaciones
                </a>
            @endif
            <i>|</i>
        </li>

        <li>
            @if($selected === 'publication.create')
                <i class="glyphicon glyphicon-plus"></i> Nueva publicación
            @else
                <a href="{{ route('publication.create') }}">
                    <i class="glyphicon glyphicon-plus"></i> Nueva publicación
                </a>
            @endif
            <i>|</i>
        </li>
        @endif

        <li>
            @if($selected === 'user.config')
                <i class="glyphicon glyphicon-cog"></i> Configuración
            @else
                <a href="{{ route('publication.create') }}">
                    <i class="glyphicon glyphicon-cog"></i> Configuración
                </a>
            @endif
        </li>
    @endif
</ul>