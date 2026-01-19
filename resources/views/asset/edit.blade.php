@extends('layouts.app')

@section('page-title', __('Edit Asset'))
@section('page-heading', __('Edit Asset'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Edit Asset')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="asset-form" action="{{ route('asset.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sku_code">SKU Code<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="sku_code" name="sku_code" value="{{ $asset->sku_code }}" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $asset->quantity }}" required min="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $asset->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Consumable" {{ $asset->category == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                            <option value="Durable" {{ $asset->category == 'Durable' ? 'selected' : '' }}>Durable</option>
                        </select>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" id="submit-asset">Update Asset</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
