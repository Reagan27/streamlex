@extends('layouts.app')

@section('page-title', __('Issue Details User'))
@section('page-heading', __('Issue Details User'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('support.index') }}">@lang('My Issues')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Issue Details')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h4>@lang('Issue Details')</h4>

            

        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>@lang('Category:')</strong> {{ ucfirst($issue->category->name) }}<br>
                <strong>@lang('Priority:')</strong> {{ ucfirst($issue->priority) }}<br>
            </div>
            <div class="col-md-6">
                <strong>@lang('Subject:')</strong> {{ $issue->subject }}<br>
                <strong>@lang('Status:')</strong> {{ ucfirst($issue->status) }}<br>
            </div>
            <div class="col-md-12">
                <div class="content-section">
                    <strong>@lang('Content:')</strong>
                    <p>{{ $issue->content }}</p>
                    @if($issue->attachment)
                        <strong>@lang('Attachment:')</strong> 
                        <a href="{{ asset('storage/' . $issue->attachment) }}" target="_blank">@lang('Download Attachment')</a><br>
                    @endif
                </div>
            </div>
        </div>

        <hr>

        <h5>@lang('Comments')</h5>
        <div class="comments-section mb-4" id="comments-section" style="max-height: 300px; overflow-y: scroll;">
            @forelse ($issue->comments as $comment)
                <div class="d-flex {{ auth()->user()->id == $comment->user_id ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                    <div class="comment-box p-2 {{ auth()->user()->id == $comment->user_id ? 'bg-primary text-white' : 'bg-light' }}" style="border-radius: 10px; max-width: 75%; word-wrap: break-word;">
                        <strong>{{ $comment->user->name }}:</strong>
                        <p>{{ $comment->comment }}</p>
                        <small class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            @empty
                <p>@lang('No comments yet.')</p>
            @endforelse
        </div>

        <hr>

        <div class="d-flex justify-content-between">
            <div class="comment-form w-100">
                <h5>@lang('Add a Comment')</h5>
                <form action="{{ route('support.comment.store', $issue->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="comment" class="form-control w-100" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <a href="{{ route('support.index') }}" class="btn btn-secondary ml-2">@lang('Back to Issues')</a>
                </form>
            </div>
        </div>

    </div>
</div>

@stop

@section('styles')
<style>
    .content-section {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .comments-section {
        max-height: 300px;
        overflow-y: auto;
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 10px;
    }

    .comment-box {
        border-radius: 10px;
        padding: 10px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .bg-light {
        background-color: #f1f1f1 !important;
        color: #333;
    }

    .bg-primary {
        background-color: #353f49 !important;
        color: white !important;
    }
</style>
@stop

@section('scripts')
<script>
    window.onload = function() {
        var commentsSection = document.getElementById('comments-section');
        commentsSection.scrollTop = commentsSection.scrollHeight;
    };
</script>
@stop
