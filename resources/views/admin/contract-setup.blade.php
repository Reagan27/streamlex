@extends('layouts.app')

@section('content')
    <h2>Admin Contract Setup</h2>
    <form action="{{ route('admin.store-contract') }}" method="POST" enctype="multipart/form-data">

        @csrf
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>Contract Type</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="contract_type" id="contract_type_individual" value="individual">
                        <label class="form-check-label" for="contract_type_individual">Individual</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="contract_type" id="contract_type_group" value="group">
                        <label class="form-check-label" for="contract_type_group">Group</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user">User</label>
                    <input type="text" name="user" id="user" class="form-control">
                </div>
                <div class="form-group">
                    <label for="engagement_type">Engagement Type</label>
                    <select name="engagement_type" id="engagement_type" class="form-control">
                        <option value="">Select Engagement</option>
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="consultant">Consultant</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration_type">Duration Type</label>
                    <select name="duration_type" id="duration_type" class="form-control">
                        <option value="">Select Duration Type</option>
                        <option value="fixed">Fixed</option>
                        <option value="open">Open</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration_amount">Duration Time</label>
                    <input type="number" name="duration_amount" id="duration_amount" class="form-control">
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="number_of_working_days">Number of Working Days (auto, excl. Sundays)</label>
                    <input type="number" name="number_of_working_days" id="number_of_working_days" class="form-control" readonly>
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="project">Project</label>
                    <input type="text" name="project" id="project" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                        <option value="drop">Drop</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="active_for_onboarding">Active for Onboarding</label><br>
                    <input type="checkbox" name="active_for_onboarding" id="active_for_onboarding" data-toggle="toggle" data-on="Yes" data-off="No">
                </div>
            </div>
        </div>
        <!-- All other fields remain below -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="authority_signature">Authority Signature</label>
            <input type="file" name="authority_signature" id="authority_signature" class="form-control-file" required>
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