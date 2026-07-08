@extends('layouts.app')

@section('page-title', __('Email Details'))
@section('page-heading', __('Email Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('emails.index') }}">@lang('Emails')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Email Details')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>@lang('Email Details')</h3>
            <div>
                <a href="{{ route('emails.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> @lang('Back')
                </a>
                <button onclick="printEmail()" class="btn btn-info mr-2">
                    <i class="fas fa-print"></i> @lang('Print')
                </button>
                <a href="{{ route('emails.download_email', $email->id) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> @lang('Download')
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">@lang('Recipient')</label>
                    <p class="form-control-plaintext">{{ $email->recipient }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">@lang('Subject')</label>
                    <p class="form-control-plaintext">{{ $email->subject }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">@lang('Status')</label>
                    <p class="form-control-plaintext">
                        <span class="badge badge-info">{{ $email->status_description ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">@lang('Sent At')</label>
                    <p class="form-control-plaintext">{{ $email->formatted_created_at ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="email-body-wrapper">
            <label class="font-weight-bold d-block mb-3">@lang('Email Message')</label>
            <div class="email-body" id="emailBody">
                {!! $email->message !!}
            </div>
        </div>

        @if($email->attachment)
        <hr class="my-4">
        <div>
            <label class="font-weight-bold">@lang('Attachments')</label>
            @php
                $paths = is_array($email->attachment) ? $email->attachment : (json_decode($email->attachment, true) ?: []);
            @endphp
            @if(count($paths))
                <ul class="list-group mt-3">
                @foreach($paths as $p)
                    <li class="list-group-item">
                        <a href="{{ Storage::url($p) }}" target="_blank" class="text-decoration-none">
                            <i class="fas fa-file"></i> {{ basename($p) }}
                        </a>
                    </li>
                @endforeach
                </ul>
            @else
                <p class="text-muted mt-3">@lang('No attachments')</p>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
    .email-body-wrapper {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1.5rem;
    }

    .email-body {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 2rem;
        font-size: 15px;
        line-height: 1.8;
        color: #333333;
        word-break: break-word;
    }

    .email-body p {
        margin-bottom: 1.2rem;
    }

    .email-body h1,
    .email-body h2,
    .email-body h3,
    .email-body h4,
    .email-body h5,
    .email-body h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .email-body ul,
    .email-body ol {
        margin-bottom: 1.2rem;
        padding-left: 2rem;
    }

    .email-body li {
        margin-bottom: 0.5rem;
    }

    .email-body a {
        color: #007bff;
        text-decoration: none;
    }

    .email-body a:hover {
        text-decoration: underline;
    }

    .email-body img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem 0;
        border-radius: 0.25rem;
    }

    .email-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.2rem 0;
    }

    .email-body table th,
    .email-body table td {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        text-align: left;
    }

    .email-body table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .email-body blockquote {
        border-left: 4px solid #007bff;
        padding: 0.5rem 0 0.5rem 1rem;
        margin: 1.2rem 0;
        background-color: #f8f9fa;
        font-style: italic;
        color: #666666;
    }

    .email-body code {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.2rem 0.4rem;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }

    .email-body pre {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        overflow-x: auto;
        margin: 1.2rem 0;
    }

    .email-body pre code {
        background-color: transparent;
        border: none;
        padding: 0;
    }

    @media print {
        .card-body > div:first-child {
            display: none;
        }
        .email-body-wrapper {
            background-color: transparent;
            border: none;
            padding: 0;
        }
        .email-body {
            background-color: transparent;
            border: none;
            padding: 0;
        }
    }
</style>

<script>
    function printEmail() {
        window.print();
    }
</script>
@endsection
