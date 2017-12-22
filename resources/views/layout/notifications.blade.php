@if($errors->any())
    <div class="alert alert-danger" id="alert-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ ucfirst($error) }}</li>
            @endforeach
        </ul>
        <div class="text-center">
            <button onclick="$('#alert-error').css('display', 'none')">
                <i class="glyphicon glyphicon-remove"></i>
            </button>
        </div>
    </div>
@endif

@if(Session::has('alert-message') && Session::has('alert-type'))
    <div class="alert {{ Session::get('alert-type') }}" id="alert-notification">
        <ul>
            <li>{{ ucfirst(Session::get('alert-message')) }}</li>
        </ul>
        <div class="text-center">
            <button onclick="$('#alert-notification').css('display', 'none')">
                <i class="glyphicon glyphicon-remove"></i>
            </button>
        </div>
    </div>
@endif