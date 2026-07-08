
@if(isset ($errors) && count($errors) > 0)
    <div class="alert alert-danger alert-notification">
        <ul class="list-unstyled mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    @if (is_array($data))
        @foreach ($data as $msg)
            <div class="alert alert-success alert-notification">
                <i class="fa fa-check"></i>
                {{ $msg }}
            </div>
        @endforeach
    @else
        <div class="alert alert-success alert-notification">
            <i class="fa fa-check"></i>
            {{ $data }}
        </div>
    @endif
@endif

@if(Session::get('info', false))
    <div class="alert alert-info alert-notification">
        <i class="fa fa-info-circle"></i>
        {{ Session::get('info') }}
    </div>
@endif

@if(Session::get('warning', false))
    <div class="alert alert-warning alert-notification">
        <i class="fa fa-exclamation-triangle"></i>
        {{ Session::get('warning') }}
    </div>
@endif
