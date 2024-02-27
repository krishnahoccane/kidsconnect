@include('./layouts/web.header')

{{-- @php
    $lastSegment = collect(explode('/', request()->url()))->last();
@endphp --}}

<h3>{{ 'adminuser' }}</h3>


@include('./layouts/web.footer')
