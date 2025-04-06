{{-- @php
    $url = $getState(); // 👈 Resolve the actual string URL
@endphp --}}

<div class="p-2">
    {!! QrCode::size(100)->generate($getState()) !!}
</div>
