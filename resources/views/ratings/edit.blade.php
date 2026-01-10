@extends('layouts.app')

@section('page-title', __('Edit Rateable Item'))
@section('page-heading', __('Edit Rateable Item'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @include('partials.messages')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ratings.update', $item->id) }}" method="POST" id="rateableItemForm">
                        @csrf
                        @method('PUT')
                        
                        @include('ratings.partials.item-form')
                        @include('ratings.partials.attributes-form')
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="float-end">
                             
                                    <div class="d-flex justify-content-between">
                                    <a href="{{ route('ratings.show', $item->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Update Rateable Item
    </button>
</div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection