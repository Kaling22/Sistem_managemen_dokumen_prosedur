@php
    $label = ucfirst($status);
    $class = 'bg-secondary';

    switch ($status) {
        case 'open':
            $class = 'bg-danger';
            $label = 'Open';
            break;

        case 'progress':
            $class = 'bg-warning text-dark';
            $label = 'Progress';
            break;

        case 'closed':
            $class = 'bg-success';
            $label = 'Closed';
            break;

        case 'rejected':
            $class = 'bg-pink';
            $label = 'Rejected';
            break;
    }
@endphp

<span class="badge {{ $class }}">
    {{ $label }}
</span>
