@extends('layouts.app')
@section('page-title', 'Requisitions')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Requisitions</h2>
        <a href="{{ route('coach-requisitions.create') }}" class="btn btn-primary btn-sm">New Requisition</a>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <form method="GET" action="{{ route('coach-requisitions.index') }}" class="mb-3">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name, position, status..."
                       value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    @if(request('search'))
                        <a href="{{ route('coach-requisitions.index') }}" class="btn btn-light">&times;</a>
                    @endif
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive mb-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Requested By</th>
                        <th>Positions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requisitions as $req)
                    @php
                        $authUser        = auth()->user();
                        $authRole        = $authUser->role ? $authUser->role->name : null;
                        $isOwnReq        = $authUser->id === $req->requested_by;

                        $myPendingApproval = null;
                        if (!$isOwnReq && $req->canUserApprove($authUser)) {
                            $myPendingApproval = $req->currentPendingApproval();
                        }

                        // Status badge colour
                        $statusClass = match($req->status) {
                            'approved'     => 'success',
                            'not_approved' => 'danger',
                            'in_review'    => 'warning',
                            'pending'      => 'secondary',
                            default        => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                        <td>{{ $req->title }}</td>
                        <td>{{ optional($req->user)->name ?? '—' }}</td>
                        <td class="text-center">{{ $req->number_of_coaches }}</td>
                        <td>
                            <span class="badge bg-{{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            {{-- View --}}
                            <a href="{{ route('coach-requisitions.show', $req->id) }}"
                               class="btn btn-sm btn-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Edit: pending requisitions can be edited by owner or admin/manager.
                                 Admin can additionally edit regardless of status (matches the
                                 Admin bypass in CoachRequisitionController@edit/@update). --}}
                            @if(
                                ($req->status === 'pending' || $authRole === 'Admin') &&
                                ($isOwnReq || in_array($authRole, ['Admin', 'Manager']))
                            )
                                <a href="{{ route('coach-requisitions.edit', $req->id) }}"
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Approve/Decline:
                                 - Cannot be the requisition owner
                                 - Must be their turn (sequential: $myPendingApproval is set)
                                 - Requisition must be pending or in_review
                            --}}
                            @if(
                                !$isOwnReq &&
                                $myPendingApproval &&
                                in_array($req->status, ['pending', 'in_review'])
                            )
                                <a href="{{ route('coach-requisitions.approval', $req->id) }}"
                                   class="btn btn-sm btn-success" title="Approve / Decline">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center"><em>No requisitions found.</em></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {!! $requisitions->appends(request()->query())->links() !!}
        </div>

    </div>
</div>
@endsection