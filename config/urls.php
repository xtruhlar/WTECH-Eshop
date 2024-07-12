<?php
return [
    'homepage' => [
        'url' => '/',
        'title' => 'Domov',
        'anchors' => [
            'most_selling' => 'najpredavanejsie',
            'most_recent' => 'najnovsie',
        ]
    ],

    'shop' =>
    [
        'url' => '/obchod',
        'title' => 'Obchod'
    ],

    'category_archive' => [
        'getPathBuilder' => function ($categorySlug) {
            return "/obchod/?kategoria=$categorySlug";
        },
    ],

    'manufacturer_archive' => [
        'getPathBuilder' => function ($manufacturerSlug) {
            return "/obchod/?vyrobca=$manufacturerSlug";
        },
    ],

    'product_detail' => [
        'getPathBuilder' => function ($productId) {
            return "/produkt/$productId";
        },
        'url' => '/produkt/{productId}',
        'title' => 'REPLACE WITH PRODUCT NAME'
    ],

    'about' => [
        'url' => '/o-nas',
        'title' => 'O nás'
    ],

    'log_in' => [
        'url' => '/prihlasit-sa',
        'title' => 'Prihlásiť sa'
    ],

    'register' => [
        'url' => '/vytvorit-ucet',
        'title' => 'Vytvoriť účet'
    ],

    'search_results' => [
        'url' => '/hladat',
        'title' => 'Hľadať'
    ],

    'cart' => [
        'url' => '/kosik',
        'title' => 'Košík'
    ],

    'cart-conflict' => [
        'url' => '/konflikt-kosikov',
        'title' => 'Konflikt košíkov'
    ],

    'checkout' => [
        'url' => '/objednat',
        'title' => 'Objednať produkty'
    ],

    'about_us' => [
        'url' => '/o-nas',
        'title' => 'O nás'
    ],

    'admin_view_products' => [
        'url' => '/admin/produkty',
        'title' => 'Všetky produkty'
    ],

    'admin_new_product' => [
        'url' => '/admin/novy-produkt',
        'title' => 'Vytvoriť produkt'
    ],

    'admin_edit_product' => [
        'getPathBuilder' => function ($productId) {
            return "/admin/upravit-produkt/$productId";
        },
        'url' => '/admin/upravit-produkt/{productId}',
        'title' => 'Upraviť produkt'
    ],

    'admin_delete_product' => [
        'getPathBuilder' => function ($productId) {
            return "/admin/vymazat-produkt/$productId";
        },
        'url' => '/admin/vymazat-produkt/{productId}',
        'title' => 'Vymazať produkt'
    ],
];
