@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Contract Information for') {{ $user->first_name }} {{ $user->last_name }}</h5>
                  
                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="mb-3">@lang('Personal Details')</h6>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3"><strong>@lang('Name'):</strong></div>
                                    <div class="col-md-9">{{ $user->first_name }} {{ $user->last_name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3"><strong>@lang('Phone'):</strong></div>
                                    <div class="col-md-9">{{ $user->phone }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3"><strong>@lang('Email Address'):</strong></div>
                                    <div class="col-md-9">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3"><strong>@lang('Address'):</strong></div>
                                    <div class="col-md-9">{{ $user->address }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3"><strong>@lang('User Role'):</strong></div>
                                    <div class="col-md-9">{{ $user->role->display_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bankDetails)
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3">@lang('Bank Details')</h6>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3"><strong>@lang('Bank'):</strong></div>
                                        <div class="col-md-9">{{ $bankDetails->bank->name ?? $bankDetails->bank_id }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3"><strong>@lang('Branch'):</strong></div>
                                        <div class="col-md-9">{{ $bankDetails->bank_branch }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3"><strong>@lang('Bank Code'):</strong></div>
                                        <div class="col-md-9">{{ $bankDetails->bank->bank_code ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3"><strong>@lang('Account Name'):</strong></div>
                                        <div class="col-md-9">{{ $bankDetails->account_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3"><strong>@lang('Account Number'):</strong></div>
                                        <div class="col-md-9">{{ $bankDetails->account_number }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($userDocuments)
    <div class="mb-4 pb-4 border-bottom">
        <h6 class="mb-3">@lang('Documents')</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-3"><strong>@lang('ID Number'):</strong></div>
                    <div class="col-9">{{ $userDocuments->id_number }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-3"><strong>@lang('ID Photo'):</strong></div>
                    <div class="col-9"><a href="{{ asset('storage/' . $userDocuments->id_photo_path) }}" target="_blank">View ID</a></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-3"><strong>@lang('KRA PIN'):</strong></div>
                    <div class="col-9">{{ $userDocuments->kra_pin }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-3"><strong>@lang('Certificate'):</strong></div>
                    <div class="col-9"><a href="{{ asset('storage/' . $userDocuments->kra_certificate_path) }}" target="_blank">View Certificate</a></div>
                </div>
            </div>
        </div>
    </div>
@else
    <p>No user documents available. (User ID: {{ $user->id }})</p>
@endif

                    @if($contractSignature)
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3">@lang('Contract Signature')</h6>
                            <div class="row">
                                <div class="col-md-3"><strong>@lang('Signed At'):</strong></div>
                                <div class="col-md-9">{{ $contractSignature->agreed_at }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><strong>@lang('Status'):</strong></div>
                                <div class="col-md-9">{{ ucfirst($contractSignature->status) }}</div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <img src="{{ $contractSignature->signature }}" alt="Signature" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">@lang('Approval Action')</h5>
                <form action="{{ route('approval.process', $user) }}" method="POST" id="approvalForm">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <button type="submit" name="status" value="approved" class="btn btn-success btn-block">@lang('Approve')</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger btn-block" id="decline-btn">@lang('Decline')</button>
                        </div>
                    </div>
                    <div class="form-group" id="reason-group" style="display: none;">
                        <label for="reason">@lang('Reason for Declining')</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
                        <button type="submit" name="status" value="declined" class="btn btn-danger btn-block mt-2">@lang('Confirm Decline')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop