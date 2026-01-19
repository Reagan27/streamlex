<style>
    .message-popover {
        max-width: 400px;
        word-wrap: break-word;
    }
    .popover {
        max-width: 400px; 
        width: 400px;
    }
    /* Custom styles for popover title */
    .custom-popover .popover-header {
        background-color: #30353E; /* Dark gray background */
        color: white; /* White text */
        border-bottom: 1px solid #3a3a3a; /* Slightly darker border */
    }
    /* Optional: style the popover body if you want to */
    .custom-popover .popover-body {
        background-color: #f8f9fa; /* Light gray background */
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Recent Messages</h5>
            <a href="{{ auth()->user()->hasRole('Admin') ? route('messages.index') : route('messages.user') }}" class="float-right text-info">
                <i class="fas fa-list"></i> View All
            </a>
        </div>
        @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif
        @if($messages->isEmpty())
            <p>No messages found.</p>
        @else
            @foreach ($messages as $message)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            {{ Str::limit($message->message, 30) }}
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-secondary"
                                data-bs-toggle="popover"
                                data-bs-placement="left"
                                data-bs-custom-class="custom-popover"
                                data-bs-content="{{ $message->message }}"
                                data-bs-trigger="focus"
                                title="Full Message">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                        @if(auth()->user()->hasRole('Admin')) 
                            <span>{{$message->category}}</span>
                        @endif
                    </div>
                </div>
                @if(!$loop->last)
                    <hr class="border-muted">
                @endif
            @endforeach
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true,
                sanitize: false,
                container: 'body',
                content: function() {
                    return '<div class="message-popover">' + this.getAttribute('data-bs-content') + '</div>';
                }
            })
        })
    });
</script>