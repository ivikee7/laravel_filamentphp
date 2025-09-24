<div class="p-2">
    <div class="p-2 bg-white rounded">
        {!! QrCode::size(100)->generate($getState()) !!}
    </div>
</div>
