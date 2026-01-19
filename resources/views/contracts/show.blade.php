@extends('layouts.app')

@section('page-title', __('Contract'))
@section('page-heading', __('Contract'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Contract')
    </li>
@stop

@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-body">
        <h1>{{ $contract->title }}</h1>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Start Date:</strong> {{ $contract->start_date->format('Y-m-d') }}</p>
                <p><strong>Number of Days:</strong> {{ $contract->number_of_days }}</p>
                <p><strong>Status:</strong> {{ ucfirst($contract->status) }}</p>
                <p><strong>Role:</strong> {{ $contract->role->display_name }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Counties:</strong></p>
                <ul>
                    @forelse($contract->counties as $county)
                        <li>{{ $county->name }}</li>
                    @empty
                        <li>No counties assigned</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <p><strong>Description:</strong></p>
            <div class="card">
                <div class="card-body">
                    {!! $contract->description !!}
                </div>
            </div>
        </div>

        @if($contract->authority_signature)
            <div class="mt-4">
                <p><strong>Authority Signature:</strong></p>
                <img src="{{ $contract->authority_signature }}" alt="Authority Signature" style="max-width: 200px; max-height: 100px;">
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('contracts.destroy', $contract) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this contract?')">Delete</button>
            </form>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection