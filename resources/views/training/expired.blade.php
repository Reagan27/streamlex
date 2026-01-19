@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="text-danger mb-4">Form Expired</h3>
                    <p class="mb-4">
                        This attendance form is no longer accepting submissions. 
                        Please contact the event organizer if you need assistance.
                    </p>
                    <a href="/" class="btn btn-primary">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection