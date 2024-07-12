@extends('layouts.app')

@section('title', config('urls.cart.title'))

@section('content')
    <div class="container mt-8 lg:mt-16 flex flex-col gap-3">
        @if (session('success'))
            <div class="alert alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div class="gap-5 w-full max-w-[1200px] mx-auto">
            <div class="w-100 mb-4">
                <h1 class="fs-1">Košík</h1>
                <p class="text-sm">
                    Chcete pokračovať v nákupe?
                    <a href="{{ config('urls.homepage.url') }}#{{ config('urls.homepage.anchors.most_selling') }}"
                        class="text-black text-decoration-underline fw-bold">
                        Pozrite si najpredávanejšie produkty
                    </a>
                </p>
            </div>

            <div class="w-full gap-5 flex lg:flex-row flex-col justify-between">
                <div class="w-full lg:max-w-[600px] flex flex-col gap-10 p-0">
                    @if (sizeof($products) > 0)
                        @foreach ($products as $product)
                            @component('components.product_in_cart', [
                                'product' => $product,
                                'quantity' => $product->quantity,
                            ])
                            @endcomponent
                        @endforeach
                    @endif
                </div>

                <div class="w-full lg:max-w-[450px] flex flex-col gap-8 bg-gray-50 h-fit p-[16px] md:p-8">
                    <h2 class="text-2xl font-semibold">
                        Spolu
                    </h2>

                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <div>Suma</div>
                            <div>{{ $total }}€</div>
                        </div>
                    </div>

                    <button type="submit" class="p-[10px] bg-black text-white ">
                        <a href="{{ route('checkout') }}" class="text-decoration-none text-white ">Pokračovať k
                            objednávke</a>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection
