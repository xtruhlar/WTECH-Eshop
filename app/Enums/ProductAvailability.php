<?php

namespace App\Enums;

enum ProductAvailability: string
{
    case IN_STOCK = 'IN_STOCK';
    case IN_SHOP = 'IN_SHOP';
    case OUT_OF_STOCK = 'OUT_OF_STOCK';
}
