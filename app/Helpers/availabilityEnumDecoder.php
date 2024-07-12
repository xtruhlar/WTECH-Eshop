<?php
function avaliabilityFilterValuesToEnum($value)
{
    $map = [
        'sklad' => 'IN_STOCK',
        'predajna' => 'IN_SHOP',
    ];

    // Check if the value exists in the map and return the corresponding enum value.
    // If the value is not found, you can return null or an appropriate default value.
    return $map[$value] ?? "";
}


function avaliabilityEnumValuesToString($value)
{
    $map = [
        'IN_STOCK' => 'Na sklade',
        'IN_SHOP' => 'V predajni',
        'OUT_OF_STOCK' => "Nieje na sklade"
    ];

    // Check if the value exists in the map and return the corresponding enum value.
    // If the value is not found, you can return null or an appropriate default value.
    return $map[$value] ?? "";
}

function avaliabilityEnumValueColor($value)
{
    $map = [
        'IN_STOCK' => '#1A8754',
        'IN_SHOP' => '#04CFC9',
        'OUT_OF_STOCK' => "#E06C75"
    ];

    // Check if the value exists in the map and return the corresponding enum value.
    // If the value is not found, you can return null or an appropriate default value.
    return $map[$value] ?? "";
}
