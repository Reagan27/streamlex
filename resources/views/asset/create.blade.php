@extends('layouts.app')

@section('page-title', __('Create Asset'))
@section('page-heading', __('Create Asset'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Create Asset')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="asset-form" action="{{ route('asset.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sku_code">SKU Code<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="sku_code" name="sku_code" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required min="0">
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Consumable">Consumable</option>
                            <option value="Durable">Durable</option>
                        </select>
                    </div>
                </div>
            </div>
       
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" id="submit-asset">Create Asset</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
