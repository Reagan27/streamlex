<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>@lang('No')</th>
            <th>@lang('Template')</th>
            <th>@lang('Created At')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($templates as $template)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ Str::limit($template->message, 100) }}</td>
                <td>{{ $template->formatted_created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
