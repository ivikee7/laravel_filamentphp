<x-filament-panels::page>

    <x-filament::section heading="Student info">
        {{ $this->studentInfolist }}
    </x-filament::section>

    <x-filament::section heading="Store info">
        {{ $this->storeInfolist }}
    </x-filament::section>

    <div>
        {{ $this->table }}
    </div>








    {{--    --}}{{-- Listen for the event dispatched from PHP to refresh computed properties in JS --}}
    {{--    <div x-data @cart-updated.window="$wire.$refresh()">--}}

    {{--        <p>Student: {{ $this->targetStudent->name }}</p>--}}
    {{--        <p>Father's Name: {{ $this->targetStudent->father_name }}</p>--}}
    {{--        <p>Mother's Name: {{ $this->targetStudent->mother_name }}</p>--}}
    {{--        <p>Class: {{ $this->targetStudent->student->classAssignment->class->name ?? '' }}</p>--}}
    {{--        <p>Class: {{ $this->targetStudent->student->classAssignment->section->name ?? '' }}</p>--}}

    {{--        <div>--}}
    {{--            <div>--}}
    {{--                <p>--}}
    {{--                    Total: ₹ {{ number_format($this->total, 2) }}--}}
    {{--                </p>--}}

    {{--                <div>--}}
    {{--                    @if(!$this->cartItems->isEmpty())--}}
    {{--                        <x-filament::button--}}
    {{--                            wire:click="clearCart"--}}
    {{--                            color="danger"--}}
    {{--                            icon="heroicon-m-trash"--}}
    {{--                        >--}}
    {{--                            Clear Cart ({{ $this->cartItems->count() }})--}}
    {{--                        </x-filament::button>--}}

    {{--                        <x-filament::button--}}
    {{--                            wire:click="checkout"--}}
    {{--                            color="success"--}}
    {{--                            icon="heroicon-m-shopping-bag"--}}
    {{--                        >--}}
    {{--                            Proceed to Checkout--}}
    {{--                        </x-filament::button>--}}
    {{--                    @else--}}
    {{--                        <p>Cart is empty.</p>--}}
    {{--                    @endif--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div>--}}
    {{--            <div >--}}
    {{--                <h2>Available Products</h2>--}}
    {{--                <div>--}}
    {{--                    @foreach($this->products() as $storeProduct)--}}
    {{--                        <x-filament::card wire:key="product-{{ $storeProduct->id }}">--}}
    {{--                            <div>--}}
    {{--                                <p>{{ $storeProduct->name }}</p>--}}
    {{--                                <p>{{ $storeProduct->description }}</p>--}}
    {{--                                <p>Price: ₹ {{ number_format($storeProduct->price, 2) }}</p>--}}
    {{--                                <div>--}}
    {{--                                    <x-filament::button--}}
    {{--                                        wire:click="addToCart({{ $storeProduct->id }})"--}}
    {{--                                        size="sm"--}}
    {{--                                    >--}}
    {{--                                        Add to Cart--}}
    {{--                                    </x-filament::button>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </x-filament::card>--}}
    {{--                    @endforeach--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</x-filament-panels::page>
