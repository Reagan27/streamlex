@extends('layouts.app')

@section('page-title', __('Assets'))
@section('page-heading', __('Assets'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Assets')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="{{ route('assets.management') }}" method="GET" id="assets-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search for assets...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('assets.management') }}"
                                       class="btn btn-light d-flex align-items-center text-muted"
                                       role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-assets-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

                @if(auth()->user()->hasRole('Admin'))
                    <div class="col-md-6">
                        <a href="{{ route('assets.create') }}" class="btn btn-primary btn-rounded float-right">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Asset')
                        </a>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive" id="assets-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th class="min-width-80">@lang('Asset Name')</th>
                        <th class="min-width-150">@lang('Category')</th>
                        <th class="min-width-100">@lang('Total')</th>
                        <th class="min-width-100">@lang('Available')</th>
                        <th class="text-center min-width-150">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($assets))
                        @foreach ($assets as $asset)
                            <tr>
                                <td>{{ $asset->name }}</td>
                                <td>{{ ucfirst($asset->category) }}</td>
                                <td>{{ $asset->number_of_items }}</td>
                                <td>{{ $asset->remainder }}</td>
                                <td class="text-center">
                                    <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
        </div>
    </div>
</div>

{!! $assets->links() !!}

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#assets-form").submit();
        });
    </script>
@stop
