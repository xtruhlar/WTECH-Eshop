@extends('layouts.app')

@section('title', config('urls.cart.title'))

@section('content')
    <div class="container mt-8 lg:mt-16 flex flex-col gap-3">
        @if (session('success'))
            <div class="alert alert-success">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div>
            <h1>Konflikt košíkov</h1>
            <div class="max-w-3xl">
                Všimili sme si, že ste mali v košíku pred prihlásením nejaké produkty. Aby sme zabránili nedorozumeniu pri
                načítaní košíku uloženého vo Vašom účte, môžete sa rozhodnúť čo spraviť
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 mt-4">
            <div class="w-full">
                <h3>Aktuálny košík:</h3>
                <div class="w-full flex flex-col gap-2 bg-backgroundColor p-4">
                    @foreach ($sessionProducts as $id => $data)
                        <div class="flex flex-col justify-between items-start">
                            <h3>{{ $data['product']->title }}</h3>
                            <div>Počet kusov: {{ $data['quantity'] }}</div>
                            <div>{{ $data['product']->price * $data['quantity'] }}€</div>
                        </div>
                        <div class="h-[1px] bg-secondary"></div>
                    @endforeach
                </div>
                <div class="flex justify-center">
                    <form method="POST" action="{{ route('cart.accept.current') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-4">Chcem aktuálny košík</button>
                    </form>
                </div>
            </div>
            <div class="w-full">
                <h3>Košík uložený v účte</h3>
                <div class="w-full flex flex-col gap-2 bg-backgroundColor p-4">
                    @foreach ($dbProducts as $id => $data)
                        <div class="flex flex-col justify-between items-start">
                            <h3>{{ $data['product']->title }}</h3>
                            <div>Počet kusov: {{ $data['quantity'] }}</div>
                            <div>{{ $data['product']->price * $data['quantity'] }}€</div>
                        </div>
                        <div class="h-[1px] bg-secondary"></div>
                    @endforeach
                </div>
                <div class="flex justify-center">
                    <form method="POST" action="{{ route('cart.accept.account') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-4">Chcem košík uložený v účte</button>
                    </form>
                </div>

            </div>
        </div>
        <div class="flex justify-center mt-8">
            <form method="POST" action="{{ route('cart.merge') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Zlúčiť dohromady</button>
            </form>
        </div>

    </div>
@endsection
