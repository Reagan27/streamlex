@extends('layouts.app')

@section('page-title', __('Templates'))
@section('page-heading', __('Templates'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Templates')
    </li>
@stop

@section('content')
  
@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="row mb-3 pb-3 border-bottom-light">
            <div class="col-lg-12">
                <div class="float-right">
                    <a href="{{ route('recommendation_certificates.create') }}" class="btn btn-primary btn-rounded">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('New Template')
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Threshold</th>
                        <th>Threshold Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td>{{ $template->name }}</td>
                        <td>{{ ucfirst($template->type) }}</td>
                        <td>{{ $template->threshold }}%</td>
                        <td>{{ $template->threshold_status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('recommendation_certificates.edit', $template) }}" class="btn btn-icon edit" title="@lang('Edit')"
                            data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('recommendation_certificates.destroy', $template) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                class="btn btn-icon"
                                title="@lang('Delete')"
                                data-toggle="tooltip"
                                data-placement="top"
                                data-confirm-title="@lang('Please Confirm')"
                                data-confirm-text="@lang('Are you sure?')"
                                data-confirm-delete="@lang('Yes!')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection