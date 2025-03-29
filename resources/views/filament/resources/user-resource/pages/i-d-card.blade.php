<x-filament-panels::page>

    <div>
        <div class="bg-white p-2 inline-block">
            {!! QrCode::size(150)->generate(Request::url()) !!}
        </div>
    </div>

</x-filament-panels::page>
