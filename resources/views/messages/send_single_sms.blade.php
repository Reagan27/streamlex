<form method="POST" action="{{ route('messages.send_single') }}">
    @csrf
    <div class="form-group">
        <label for="recipient">@lang('Recipient')</label>
        <input type="text" name="recipient" class="form-control" placeholder="Enter phone number" required>
    </div>
    <div class="form-group">
        <label for="message">@lang('Message')</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">@lang('Send Single SMS')</button>
</form>
