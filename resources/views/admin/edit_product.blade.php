@extends('layouts.app')

@section('title', config('urls.admin_edit_product.title'))

@section('content')

    <script>
        function onProductImageChange(event) {
            const file = event.target.files[0]
            document.getElementById("productImagePreview").src = URL.createObjectURL(file)
        }

        function removeImageFromGallery(element) {
            const imageId = element.id
            const elementToRemove = document.querySelector(`div[data-image-id="${imageId}"]`);
            console.log(elementToRemove)
            if (elementToRemove) {
                elementToRemove.parentNode.removeChild(elementToRemove);

                var input = document.getElementById('galleryImagesToRemove');
                var currentIds = input.value ? input.value.split(',') : [];
                if (!currentIds.includes(imageId)) {
                    currentIds.push(imageId);
                    input.value = currentIds.join(',');
                }
            }
        }

        function onGalleryImagesChange(event) {
            console.log(event);
            const files = event.target.files; // This is a FileList, not an array.

            // Get the galleryImages element
            const galleryImagesSection = document.getElementById("galleryImagesToAddPreview");

            // Clear previous images
            galleryImagesSection.innerHTML = "";

            // Convert FileList to Array to use forEach
            Array.from(files).forEach((file) => {
                if (file.type.startsWith('image/')) { // Check if the file is an image.
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.classList.add('img-fluid', 'col'); // Adding classes
                    img.onload = function() {
                        URL.revokeObjectURL(img.src); // Clean up memory after the image is loaded
                    };
                    galleryImagesSection.appendChild(img);
                }
            });
        }
    </script>
    <main class="container mt-4 pt-0">
        @if (session('success'))
            <div class="alert alert-success">
                <h4>{{ session('success') }}</h4>
            </div>
        @endif
        <div class="d-flex flex-column p-md-5 justify-content-between ">
            <!-- Back navigation -->
            <div class="d-flex flex-column">
                <a href="{{ config('urls.admin_view_products.url') }}" class="text-black text-decoration-underline fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Späť na zoznam produktov
                </a>
            </div>
            <form action="{{ route('product.update', ['productId' => $product->slug]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="d-flex flex-column col-12 col-md-5 mt-4 gap-0">
                    <h4 class="h4">Názov produktu</h4>
                    <input value="{{ $product->title }}" type="text" class="form-control rounded-0 text-xl"
                        id="productName" name="productName" placeholder="Nový názov produktu">
                </div>

                <!-- Product image upload -->
                <div class="d-flex flex-column col-md-5 col-12 mb-3 mt-5">
                    <h4 class="h4">Obrázok produktu</h4>
                    <div>
                        <img src="{{ $product->featuredImage }}" id="productImagePreview" alt="Product image"
                            class="img-fluid" style="width: 150px; height: 150px;">
                    </div>
                    <div class="d-flex">
                        <input onchange="onProductImageChange(event)" type="file" class="form-control mt-2"
                            name="productImage" id="productImage">
                    </div>
                </div>

                <!-- galeria -->
                <input type="hidden" name="galleryImagesToRemove" id="galleryImagesToRemove">
                <div class="d-flex flex-column col-md-5">
                    <h2 class="text-3xl">Galéria</h2>
                    <div class="row row-cols-md-3 row-cols-2 g-2" id="galleryImages">
                        @foreach ($product->galleryImages as $image)
                            <div class="col relative" data-image-id="{{ $image->id }}">
                                <img src="{{ $image->imageURL }}" alt="gallery image"
                                    class="w-100 object-cover h-full aspect-square" />
                                <button id="{{ $image->id }}" onclick="removeImageFromGallery(this)" type="button"
                                    class="absolute -top-2 -right-2 z-10 h-6 w-6 bg-red-600 rounded-full flex justify-center items-center">
                                    <i class="fas fa-times text-white"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-col gap-2 mt-4">
                        <h3>+ Pridať do galérie</h3>
                        <div id="galleryImagesToAddPreview" class="row row-cols-md-3 row-cols-2 g-2">
                        </div>
                        <input onchange="onGalleryImagesChange(event)" type="file" class="form-control"
                            name="galleryImages[]" id="galleryImagesToAdd" multiple>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between gap-2 pt-4">
                    <div class="d-flex flex-column col-12 col-md-5">
                        <!-- Short description -->
                        <div class="mb-3 d-flex flex-column">
                            <label for="shortDescription" class="form-label">Krátky opis</label>
                            <textarea class="form-control rounded-0" id="shortDescription" name="shortDescription" rows="4"
                                placeholder="Krátky opis produktu">{{ $product->shortDescription }}</textarea>
                        </div>

                        <!-- Detailed description -->
                        <div class="mb-3 d-flex flex-column">
                            <label for="detailedDescription" class="form-label">Dlhý opis</label>
                            <textarea class="form-control rounded-0" id="detailedDescription" name="detailedDescription" rows="7"
                                placeholder="Dlhý opis produktu">{{ $product->longDescription }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex flex-column col-12 col-md-5 gap-3">
                        <!-- Price, Category, Manufacturer, and Availability -->
                        <div class="col-md-6 d-flex flex-column">
                            <label for="price" class="form-label">Cena</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" class="form-control rounded-0" id="price"
                                    name="price" placeholder="Zadajte cenu v EUR" value="{{ $product->price }}">
                            </div>
                        </div>

                        <div class="col-md-6 d-flex flex-column">
                            <label for="category" class="form-label rounded-0">Kategória produktu</label>
                            <select class="form-select" id="category" name="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category->id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <label for="manufacturer" class="form-label rounded-0">Výrobca</label>
                            <select class="form-select" id="manufacturer" name="manufacturer">
                                @foreach ($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}"
                                        {{ $product->manufacturer->id == $manufacturer->id ? 'selected' : '' }}>
                                        {{ $manufacturer->name }}</option>
                                @endforeach
                                <!-- Add manufacturer options here -->
                            </select>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <label for="availability" class="form-label rounded-0">Dostupnosť</label>
                            <select class="form-select" id="availability" name="availability">
                                @foreach ($availabilities as $availability)
                                    <option value="{{ $availability }}"
                                        {{ $product->availability == $availability->value ? 'selected' : '' }}>
                                        {{ avaliabilityEnumValuesToString($availability->value) }}
                                    </option>
                                @endforeach
                                <!-- Add availability options here -->
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Publish button -->
                <div class="d-flex pt-3 gap-4 ">
                    <div class="mt-0 d-flex flex-column">
                        <button type="submit" class="btn btn-primary text-base">Uložiť zmeny</button>
                    </div>
                    <div class="mt-0 d-flex flex-column">
                        <a href="{{ config('urls.admin_delete_product.getPathBuilder')($product->id) }}"> <button
                                type="button" class="btn bg-red-500 text-white text-base">Zmazať produkt</button></a>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
