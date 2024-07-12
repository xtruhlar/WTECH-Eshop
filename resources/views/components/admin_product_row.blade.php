<div class="product-row p-2">
    <div class="flex gap-2 w-full gap-3">
        <img src="{{ $product->featuredImage }}" alt="" class="w-[100px] h-full rounded-md aspect-square" />
        <div class="w-full flex flex-col justify-center">
            <span class="font-semibold text-sm" style="color:{{ avaliabilityEnumValueColor($product->availability) }}">
                {{ avaliabilityEnumValuesToString($product->availability) }}
            </span>
            <h2 class="font-semibold py-1 text-lg">
                {{ $product->title }}
            </h2>

        </div>
    </div>
    <div class="py-1">{{ $product->price }}€</div>
    <div class="py-1">{{ $product->category->name }}</div>
    <div class="py-1">{{ $product->manufacturer->name }}</div>
    <div class="py-1 flex flex-row gap-2">
        <div class="action-button-wraper h-fit hover:text-red-500">
            <a href="{{ config('urls.admin_delete_product.getPathBuilder')($product->id) }}"
                class="w-[32px] h-[32px] bg-gray-100 flex justify-center items-center rounded-2 text-base">
                <i class="fas fa-solid fa-trash"></i>
            </a>
            <div class="custom-tooltip">Zmazať</div>
        </div>
        <div class="action-button-wraper h-fit hover:text-blue-500">
            <a href="{{ config('urls.admin_edit_product.getPathBuilder')($product->slug) }}"
                class="w-[32px] h-[32px] bg-gray-100 flex justify-center items-center rounded-2 text-base">
                <i class="fas fa-solid fa-marker"></i>
            </a>
            <div class="custom-tooltip">Upraviť</div>
        </div>
        <div class="action-button-wraper h-fit hover:text-green-500">
            <a href="{{ config('urls.product_detail.getPathBuilder')($product->slug) }} "
                class="w-[32px] h-[32px] bg-gray-100 flex justify-center items-center rounded-2 text-base">
                <i class="fas fa-solid fa-link"></i>
            </a>
            <div class="custom-tooltip">Zobraziť</div>
        </div>
    </div>
</div>
