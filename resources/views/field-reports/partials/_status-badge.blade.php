
@php
    $statusClasses = [
        'draft' => 'bg-secondary',
        'submitted' => 'bg-primary',
        'approved' => 'bg-success'
    ];

    $statusIcons = [
        'draft' => 'fas fa-pencil-alt',
        'submitted' => 'fas fa-clock',
        'approved' => 'fas fa-check-circle'
    ];
@endphp

<span class="badge {{ $statusClasses[$status] ?? 'bg-secondary' }}">
    <i class="{{ $statusIcons[$status] ?? 'fas fa-circle' }} me-1"></i>
    {{ ucfirst($status) }}
</span>