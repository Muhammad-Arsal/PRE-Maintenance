@if (Session::has('flash_message'))
    @if (Session::get('flash_type') == 'errors')
        <div class="alert alert-danger" id="responseMsg">
            <span class="text-white ml-2">{!! Session::get('flash_message') !!}</span>
        </div>
    @endif
    @if (Session::get('flash_type') == 'success')
        <div class="alert alert-success" id="responseMsg">
            <span class="text-white ml-2">{!! Session::get('flash_message') !!}</span>
        </div>
    @endif
@endif
