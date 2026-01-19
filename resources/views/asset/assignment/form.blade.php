@extends('layouts.app')

@section('page-title', __('Manage Asset Assignments'))
@section('page-heading', __('Manage Asset Assignments for ' . $user->first_name . ' ' . $user->last_name))

@section('content')
<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="assign-tab" data-toggle="tab" href="#assign" role="tab">Assign Assets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="return-tab" data-toggle="tab" href="#return" role="tab">Return Assets</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Assign Assets Tab -->
                        <div class="tab-pane fade show active" id="assign" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Available</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assets as $asset)
                                        @php
                                            $hasActiveAssignment = $assignedAssets
                                                ->where('asset_id', $asset->id)
                                                ->where('assignment_status', \Vanguard\AssetAssignment::STATUS_ASSIGNED)
                                                ->isNotEmpty();
                                        @endphp
                                            <tr>
                                                <td>{{ $asset->name }}</td>
                                                <td>{{ $asset->category }}</td>
                                                <td>{{ $asset->quantity }}</td>
                                                <td>
                                                    @if($hasActiveAssignment)
                                                        <span class="badge badge-warning">Assigned</span>
                                                    @else
                                                        <span class="badge badge-success">Available</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" 
                                                            class="btn btn-primary btn-sm" 
                                                            data-toggle="modal" 
                                                            data-target="#assignModal_{{ $asset->id }}"
                                                            {{ $hasActiveAssignment ? 'disabled' : '' }}
                                                            title="{{ $hasActiveAssignment ? 'Asset already assigned to this user' : 'Assign asset' }}">
                                                        Assign
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Return Assets Tab -->
                        <div class="tab-pane fade" id="return" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Assigned Date</th>
                                            <th>Physical Condition</th>
                                            <th>IMEI</th>
                                            <th>Serial</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($assignedAssets->where('assignment_status', \Vanguard\AssetAssignment::STATUS_ASSIGNED)->count() > 0)
                                        @foreach($assignedAssets->where('assignment_status', \Vanguard\AssetAssignment::STATUS_ASSIGNED) as $assignment)
                                            <tr>
                                                <td>{{ $assignment->asset->name }}</td>
                                                <td>{{ $assignment->asset->category }}</td>
                                                <td>{{ $assignment->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $assignment->physical_condition === 'Good' ? 'success' : ($assignment->physical_condition === 'Poor' ? 'warning' : ($assignment->physical_condition === 'Damaged' ? 'danger' : 'info')) }}">
                                                        {{ $assignment->physical_condition }}
                                                    </span>
                                                </td>
                                                <td>{{ $assignment->imei_number }}</td>
                                                <td>{{ $assignment->serial_number }}</td>
                                                <td>
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm" 
                                                            data-toggle="modal" 
                                                            data-target="#returnModal_{{ $assignment->id }}">
                                                        Return
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No assets currently assigned to this user.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Assign Asset Modals -->
@foreach($assets as $asset)
@php
    $hasActiveAssignment = $assignedAssets
        ->where('asset_id', $asset->id)
        ->where('assignment_status', \Vanguard\AssetAssignment::STATUS_ASSIGNED)
        ->isNotEmpty();
@endphp
<div class="modal fade" id="assignModal_{{ $asset->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asset.assignment.assign', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($hasActiveAssignment)
                        <div class="alert alert-warning">
                            This asset is already assigned to this user. Please return the existing assignment before creating a new one.
                        </div>
                    @endif
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <input type="hidden" name="asset_category" value="{{ $asset->category }}">
                    
                    <div class="form-group">
                        <label>Asset:</label>
                        <input type="text" class="form-control" value="{{ $asset->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="imei_number">IMEI Number:</label>
                        <input type="text" 
                               name="imei_number" 
                               id="imei_number" 
                               class="form-control" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="serial_number">Serial Number:</label>
                        <input type="text" 
                               name="serial_number" 
                               id="serial_number" 
                               class="form-control" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="physical_condition">Physical Condition:</label>
                        <select name="physical_condition" id="physical_condition" class="form-control" required>
                            <option value="">Select Condition</option>
                            @foreach(\Vanguard\AssetAssignment::getPhysicalConditions() as $condition)
                                <option value="{{ $condition }}">{{ $condition }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="assign_comments">Comments:</label>
                        <textarea name="comments" id="assign_comments" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" {{ $hasActiveAssignment ? 'disabled' : '' }}>
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Return Asset Modals -->
@foreach($assignedAssets->where('assignment_status', \Vanguard\AssetAssignment::STATUS_ASSIGNED) as $assignment)
<div class="modal fade" id="returnModal_{{ $assignment->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Return Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asset.assignment.return', ['assignment' => $assignment->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Asset:</label>
                        <input type="text" class="form-control" value="{{ $assignment->asset->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>IMEI Number:</label>
                        <input type="text" class="form-control" value="{{ $assignment->imei_number }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Serial Number:</label>
                        <input type="text" class="form-control" value="{{ $assignment->serial_number }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Current Condition:</label>
                        <input type="text" class="form-control" value="{{ $assignment->physical_condition }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="return_physical_condition">Return Condition:</label>
                        <select name="physical_condition" id="return_physical_condition" class="form-control" required>
                            <option value="">Select Condition</option>
                            @foreach(\Vanguard\AssetAssignment::getPhysicalConditions() as $condition)
                                <option value="{{ $condition }}">{{ $condition }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="return_comments">Comments:</label>
                        <textarea name="comments" id="return_comments" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Return Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection