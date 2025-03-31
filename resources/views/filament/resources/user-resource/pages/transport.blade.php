<x-filament-panels::page>

    <div>
        {{-- <div class="inline-block">
            <div class="bg-white p-2 inline-block">
                {!! QrCode::size(150)->generate(route('filament.admin.resources.users.transport', $this->record->id)) !!}
            </div>
            <h2 class="text-center">{{ date('d-M-Y') }}</h2>
        </div> --}}

        <div>
            {{ $this->table }}
        </div>
    </div>

</x-filament-panels::page>
