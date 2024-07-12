@extends('layouts.app')

@section('title', config('urls.checkout.url'))

@section('content')
    <main class="container-fluid gap-3 pt-5 px-4 md:px-16">
        <div class="gap-5 w-full max-w-[1200px] mx-auto">
            <div class="flex flex-col gap-4">
                <!-- Pokladna uvodny text-->
                <div>
                    <h1 class="fs-1">Pokladňa</h1>
                    <p class="text-sm">Máte účet? <a href="{{ config('urls.log_in.url') }}"
                            class="text-black text-decoration-underline fw-bold">Prihláste sa</a>
                    </p>
                </div>
                <!-- Pokladna uvodny text -->
            </div>


            <!-- Formular -->
            <form action="{{ route('order.store') }}" method="POST" class="d-flex flex-column gap-2">
                @csrf

                <div class="w-full flex flex-col lg:flex-row gap-8 mt-4">
                    <div class="w-full gap-5 flex flex-col lg:flex-row justify-between">
                        <div class="w-full flex flex-col gap-8 bg-gray-50 h-fit p-6 md:p-8">
                            <div class="row flex cart-card gap-3 align-items-start bg-gray-50">
                                <div class="d-flex flex-col w-full h-full justify-between gap-2">
                                    <div class="d-flex flex-column gap-2">
                                        <h2 class="fs-2">Dodacie údaje</h2>
                                    </div>
                                    <div class="d-flex flex-column w-100 gap-2 align-content-between ">
                                        <div class="d-flex flex-col md:flex-row gap-2 ">
                                            <input required type="text" class="form-control rounded-0" id="name"
                                                name="name" aria-describedby="Meno" placeholder="Vaše meno" />
                                            <input required type="text" class="form-control rounded-0" id="surname"
                                                name="surname" aria-describedby="Priezvisko"
                                                placeholder="Vaše priezvisko" />
                                        </div>
                                        <div class="d-flex flex-col md:flex-row gap-2 "> <input required type="email"
                                                class="form-control rounded-0" id="email" name="email"
                                                aria-describedby="Email" placeholder="Váš email" />
                                            <input required type="tel" class="form-control rounded-0" id="phone"
                                                aria-describedby="Telefon" placeholder="Váš telefón" />
                                        </div>
                                        <div class="d-flex flex-col md:flex-row gap-2 "> <input required type="text"
                                                class="form-control d-flex  rounded-0" id="address" name="street"
                                                aria-describedby="Adresa" placeholder="Adresa" />
                                            <input required type="text" class="form-control d-flex  rounded-0"
                                                id="homenumber" aria-describedby="Cislo" name="num"
                                                placeholder="Číslo domu" />
                                        </div>
                                        <div class="d-flex flex-col md:flex-row gap-2 "> <input required type="text"
                                                class="form-control d-flex  rounded-0 " id="city" name="city"
                                                aria-describedby="Mesto" placeholder="Mesto" />
                                            <input required type="text" class="form-control d-flex  rounded-0"
                                                id="zip" aria-describedby="PSC" name="zip" placeholder="PSČ" />
                                        </div>
                                        <div class="d-flex">
                                            <textarea type="text" rows="8" class="form-control d-flex w-100 rounded-0" id="note" name="note"
                                                aria-describedby="Poznamka" placeholder="Poznámka k objednávke"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-8 bg-gray-50 h-fit p-6 md:p-8">
                        <h2 class="text-2xl font-semibold">
                            Objednávka
                        </h2>
                        <!-- Produkty -->
                        <div class="flex flex-col gap-3">

                            @foreach ($products as $product)
                                <div class="flex flex-col justify-between items-start">
                                    <h3>{{ $product->title }}</h3>
                                    <div>Počet kusov: {{ $product->quantity }}</div>
                                    <div>{{ $product->price * $product->quantity }}€</div>
                                </div>
                                <div class="h-[1px] bg-secondary"></div>
                            @endforeach
                        </div>

                        <div class="col-12 col-md-12 flex flex-col  bg-gray-50 ">
                            <h2 class="text-2xl font-semibold">
                                Výber dopravy
                            </h2>
                            <div class="d-flex flex-column column-gap-2 ">
                                <div class="form-check d-flex justify-content-between ">
                                    <div>
                                        <input class="form-check-input" type="radio" name="doprava" id="dopravaPostou"
                                            value="posta" data-price="4.5" onclick="recalculateTotal()">
                                        <label class="form-check-label" for="dopravaPostou">Doprava poštou</label>
                                    </div>
                                    <div id="kurierPrice">+ 4.50€</div>
                                </div>
                                <div class="form-check d-flex justify-content-between ">
                                    <div>
                                        <input class="form-check-input" type="radio" name="doprava" id="osobnyOdber"
                                            value="odber" data-price="0" onclick="recalculateTotal()">
                                        <label class="form-check-label" for="osobnyOdber">Osobný odber</label>
                                    </div>
                                    <div id="osobnyOdberPrice">+ 0.00€</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 flex flex-col  bg-gray-50 ">
                            <h2 class="text-2xl font-semibold">
                                Spôsob platby
                            </h2>
                            <div class="d-flex flex-column gap-2 ">
                                <div class="form-check d-flex justify-content-between ">
                                    <div>
                                        <input class="form-check-input" type="radio" name="payment" id="card"
                                            value="card" data-price="0" onclick="recalculateTotal()">
                                        <label class="form-check-label" for="card">
                                            Karta
                                        </label>
                                    </div>
                                    <div id="cashPrice">+ 0.00€</div>
                                </div>
                                <div class="form-check d-flex justify-content-between ">
                                    <div>
                                        <input class="form-check-input" type="radio" name="payment" id="cash"
                                            value="cash" data-price="1" onclick="recalculateTotal()">
                                        <label class="form-check-label" for="cash">
                                            Hotovosť
                                        </label>
                                    </div>
                                    <div id="cardPrice">+ 1.00€</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 flex flex-col  bg-gray-50 ">
                            <h2 class="text-2xl font-semibold">
                                Detail objednávky
                            </h2>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between items-center">
                                    <div>Medzisúčet</div>
                                    <div id="subtotal">{{ $total }}€</div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>Doprava</div>
                                    <div id="shipping">0€</div>
                                </div>
                                <div class="h-[1px] bg-black"></div>
                                <div class="flex justify-between items-center">
                                    <div>Spolu</div>
                                    <div id="total">{{ $total }}€</div>
                                </div>
                            </div>
                            <input type="hidden" name="total" value="{{ $total }}">
                            <button type="submit" class="p-[10px] bg-black text-white mt-4">
                                Pokračovať k platbe
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        function recalculateTotal() {
            var shippingMethod = document.querySelector('input[name="doprava"]:checked');
            var paymentMethod = document.querySelector('input[name="payment"]:checked');
            var shippingPrice = 0
            var paymentPrice = 0

            if (!document.querySelector('input[name="doprava"]:checked')) {
                shippingPrice = 0
            } else {
                shippingPrice = parseFloat(shippingMethod.getAttribute('data-price'));
            }

            if (!document.querySelector('input[name="payment"]:checked')) {
                paymentPrice = 0
            } else {
                paymentPrice = parseFloat(paymentMethod.getAttribute('data-price'));
            }

            var subtotal = parseFloat(document.getElementById('subtotal').textContent);
            shippingPrice = shippingPrice + paymentPrice;
            if (subtotal > 50) {
                shippingPrice = 0;
            } else {
                shippingPrice = shippingPrice;
            }
            var total = subtotal + shippingPrice;

            document.getElementById('shipping').textContent = shippingPrice.toFixed(2) + '€';
            document.getElementById('total').textContent = total.toFixed(2) + '€';
        }
    </script>
@endsection
