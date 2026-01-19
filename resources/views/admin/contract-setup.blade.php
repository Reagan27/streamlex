@extends('layouts.app')

@section('content')
    <h2>Admin Contract Setup</h2>
    <form action="{{ route('admin.store-contract') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="number_of_days">Number of Days</label>
            <input type="number" name="number_of_days" id="number_of_days" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="authority_signature">Authority Signature</label>
            <input type="file" name="authority_signature" id="authority_signature" class="form-control-file" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="draft">Draft</option>
                <option value="publish">Publish</option>
                <option value="drop">Drop</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Contract</button>
    </form>

    <hr>

    <h3>Existing Contracts</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>Number of Days</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $contract)
            <tr>
                <td>{{ $contract->title }}</td>
                <td>{{ $contract->start_date->format('Y-m-d') }}</td>
                <td>{{ $contract->number_of_days }}</td>
                <td>{{ $contract->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection