@props(['user'])

@if($user->role->name == 'Regional Coordinator')
    {{ $user->counties->pluck('name')->implode(', ') }}
@else
    {{ $user->county->name ?? 'N/A' }}
@endif