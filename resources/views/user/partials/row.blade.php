<tr>
    <td class="align-middle">{{ $user->first_name . ' ' . $user->last_name }}</td>
    <td class="align-middle">{{ $user->email }}</td>
    <td class="align-middle">
        @if($user->role && $user->role->name === 'Regional_Coordinator')
            @php
                $countyNames = $user->counties->pluck('name')->join(', ');
            @endphp
            {{ $countyNames ?: 'N/A' }}
        @else
            {{ $user->county->name ?? 'N/A' }}
        @endif
    </td>
    <td class="align-middle">
        @if($user->role)
            <span class="">{{ $user->role->display_name }}</span>
        @else
            <span class="">Unassigned</span>
        @endif
    </td>
    <td class="align-middle">
        <span class="badge badge-lg badge-{{ $user->present()->labelClass }}">
            {{ trans("app.status.{$user->status->value}") }}
        </span>
    </td>
    <td class="text-center align-middle">
        <div class="btn-group">
            <a href="{{ route('users.edit', $user->id) }}" 
               class="btn btn-icon edit" 
               title="@lang('Edit User')" 
               data-toggle="tooltip" 
               data-placement="top">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('users.destroy', $user->id) }}" 
                  method="POST" 
                  style="display: inline-block;"
                  class="delete-user-form">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-icon delete-user"
                        title="@lang('Delete User')"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                    <i class="fas fa-trash"></i>
                </button>
            </form>

            <div class="dropdown d-inline-block">
                <button class="btn btn-icon"
                        type="button" 
                        id="dropdownMenuButton-{{ $user->id }}"
                        data-toggle="dropdown"
                        aria-haspopup="true" 
                        aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-{{ $user->id }}">
                    @if (config('session.driver') == 'database')
                        <a href="{{ route('user.sessions', $user->id) }}" class="dropdown-item">
                            <i class="fas fa-list mr-2"></i>
                            @lang('User Sessions')
                        </a>
                    @endif

                    @canBeImpersonated($user)
                        <a href="{{ route('impersonate', $user->id) }}" class="dropdown-item impersonate">
                            <i class="fas fa-user-secret mr-2"></i>
                            @lang('Impersonate')
                        </a>
                    @endCanBeImpersonated
                </div>
            </div>
        </div>
    </td>
</tr>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('.delete-user-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const userName = form.find('.delete-user').data('user-name');
        
        Swal.fire({
            title: '@lang("Please Confirm")',
            html: `@lang('Are you sure you want to delete') <strong>${userName}</strong>?<br>@lang('This action cannot be undone.')`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '@lang("Yes, delete!")',
            cancelButtonText: '@lang("Cancel")'
        }).then((result) => {
            if (result.isConfirmed) {
                form.off('submit').submit();
            }
        });
    });
    $('.dropdown-toggle').dropdown();
});
</script>