@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        {{-- Dark Header Banner --}}
        <div class="px-4 py-4" style="background: linear-gradient(135deg, #0a893d, #045d45);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <div>
                        <h3 class="text-white fw-bold mb-1">
                            <i class="bi bi-clipboard-data me-2"></i>{{ $dataCollection->title }}
                        </h3>
                        @if($dataCollection->description)
                            <p class="text-white-50 mb-0 small">{{ $dataCollection->description }}</p>
                        @endif
                    </div>
                </div>

                @php
                    $today = \Carbon\Carbon::today();
                    $isActive = $today->between($dataCollection->start_date, $dataCollection->end_date);
                    $authUser = auth()->user();
                    $isAdmin = $authUser && (method_exists($authUser, 'isAdmin') ? $authUser->isAdmin() : $authUser->hasRole('Admin'));
                @endphp

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    {{-- Date Pills --}}
                    <div class="d-flex align-items-center gap-2 text-white-50 small">
                        <i class="bi bi-calendar-event"></i>
                        <span>{{ $dataCollection->start_date->format('d M Y') }}</span>
                        <span class="text-white-50">→</span>
                        <i class="bi bi-calendar-check"></i>
                        <span>{{ $dataCollection->end_date->format('d M Y') }}</span>
                    </div>

                    {{-- Status Badge --}}
                    @if($isActive)
                        <span class="badge rounded-pill px-3 py-2"
                              style="background-color: rgba(255,255,255,0.2); color: #fff; font-size: 0.85rem;">
                            <i class="bi bi-check-circle-fill me-1 text-success"></i> Active
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-2"
                              style="background-color: rgba(255,255,255,0.2); color: #fff; font-size: 0.85rem;">
                            <i class="bi bi-x-circle-fill me-1 text-danger"></i> Expired
                        </span>
                    @endif

                    {{-- Admin-only: Excel Download & Open Sheet --}}
                    @if($isAdmin)
                        @php
                            $sheetExportUrl = null;
                            if (!empty($dataCollection->sheet_url) && preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/', $dataCollection->sheet_url, $sm)) {
                                $sheetExportUrl = 'https://docs.google.com/spreadsheets/d/' . $sm[1] . '/export?format=xlsx';
                            }
                        @endphp
                        @if($sheetExportUrl)
                            <a href="{{ $sheetExportUrl }}" target="_blank"
                               class="btn btn-sm btn-success d-flex align-items-center gap-1" title="Download Responses as Excel">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                                <span>Download Responses</span>
                            </a>
                            <a href="{{ $dataCollection->sheet_url }}" target="_blank"
                               class="btn btn-sm btn-light d-flex align-items-center gap-1" title="Open Google Sheet">
                                <i class="bi bi-box-arrow-up-right"></i>
                                <span>Open Sheet</span>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Embedded Form --}}
        <div class="card-body p-0">
            @php
                $src = '';
                if (!empty($dataCollection->iframe_code) &&
                    preg_match('/src="([^"]+)"/', $dataCollection->iframe_code, $matches)) {
                    $src = $matches[1];
                }
            @endphp

            @if($src)
                <div id="iframe-loader" class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2 small">Loading form...</p>
                </div>
                <iframe
                    src="{{ $src }}"
                    width="100%"
                    frameborder="0"
                    scrolling="yes"
                    style="display:none; border: none; min-height: 100vh;"
                    onload="
                        document.getElementById('iframe-loader').style.display='none';
                        this.style.display='block';
                        try {
                            this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
                        } catch(e) {
                            this.style.height = '900px';
                        }
                    ">
                </iframe>
            @else
                <div class="alert alert-warning m-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No form is embedded for this data collection.
                </div>
            @endif
        </div>

    </div>

</div>
@endsection