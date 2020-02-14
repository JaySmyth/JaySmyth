<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

$factory->define(App\Shipment::class, function (Faker\Generator $faker) {
    return [
        'consignment_number' => $faker->numberBetween(10000000000, 20000000000),
        'carrier_consignment_number' => strtoupper(Str::random(12)),
        'carrier_tracking_number' => strtoupper(Str::random(20)),
        'user_id' => $faker->numberBetween(1, 20),
        'company_id' => $faker->numberBetween(1, 20),
        'status_id' => $faker->numberBetween(1, 4),
        'mode_id' => $faker->numberBetween(1, 4),
        'department_id' => $faker->numberBetween(1, 4),
        'carrier_id' => $faker->numberBetween(1, 5),
        'service_id' => $faker->numberBetween(1, 8),
        'route_id' => $faker->numberBetween(1, 2),
        'depot_id' => $faker->numberBetween(1, 3),
        'manifest_id' => null,
        'invoice_run_id' => null,
        'token' => Str::random(15),
        'sender_type' => $faker->randomElement(['r', 'c']),
        'sender_name' => $faker->name,
        'sender_company_name' => $faker->company,
        'sender_address1' => $faker->streetAddress,
        'sender_address2' => $faker->streetAddress,
        'sender_address3' => $faker->streetAddress,
        'sender_city' => $faker->city,
        'sender_state' => $faker->state,
        'sender_postcode' => $faker->postcode,
        'sender_country_code' => $faker->countryCode,
        'sender_telephone' => $faker->phoneNumber,
        'sender_email' => $faker->email,
        'shipment_reference' => $faker->randomElement([Str::random(5), Str::random(12), $faker->company, Str::random(9), $faker->word, $faker->name]),
        'recipient_type' => $faker->randomElement(['r', 'c']),
        'recipient_name' => $faker->name,
        'recipient_company_name' => $faker->company,
        'recipient_address1' => $faker->streetAddress,
        'recipient_address2' => $faker->streetAddress,
        'recipient_address3' => $faker->streetAddress,
        'recipient_city' => $faker->city,
        'recipient_state' => $faker->state,
        'recipient_postcode' => $faker->postcode,
        'recipient_country_code' => $faker->countryCode,
        'recipient_telephone' => $faker->phoneNumber,
        'recipient_email' => $faker->email,
        'shipping_charge' => rand(2, 40),
        'shipping_cost' => rand(2, 40),
        'special_instructions' => $faker->paragraph,
        'terms_of_sale' => $faker->word,
        'commercial_invoice_comments' => $faker->paragraph,
        'scs_job_number' => 'IFCEXJ000'.$faker->numberBetween(30000, 90000),
        'received' => $faker->boolean(),
        'documents_description' => $faker->randomElement(['BUSINESS DOCUMENTS ONLY', 'Legal Documents', 'Manuals']),
        'goods_description' => $faker->word,
        'pallet' => $faker->boolean(),
        'bill_shipping' => $faker->randomElement(['sender', 'recipient', 'other']),
        'bill_tax_duty' => $faker->randomElement(['sender', 'recipient', 'other']),
        'bill_shipping_account' => $faker->numberBetween(6000000, 9000000),
        'bill_tax_duty_account' => $faker->numberBetween(6000000, 9000000),
        'customs_value' => $faker->numberBetween(1, 100),
        'customs_value_currency_code' => 'GBP',
        'weight' => $faker->numberBetween(1, 10),
        'weight_uom' => 'kg',
        'dims_uom' => 'cm',
        'volumetric_weight' => $faker->numberBetween(1, 10),
        'volumetric_divisor' => 5000,
        'max_dimension' => $faker->numberBetween(1, 10),
        'hazardous' => $faker->randomElement(['N', 'E', 1, 2, 3, 4, 5, 6, 7, 8, 9]),
        'pieces' => $faker->numberBetween(1, 3),
        'alcohol_type' => $faker->randomElement(['', 'a', 'b', 'w']),
        'alcohol_packaging' => $faker->randomElement(['bt', 'bl', 'cn']),
        'alcohol_volume' => $faker->numberBetween(5, 15),
        'alcohol_quantity' => $faker->numberBetween(1, 25),
        'dry_ice_flag' => $faker->randomElement([0, 1, 2]),
        'dry_ice_weight_per_package' => $faker->numberBetween(1, 3),
        'dry_ice_total_weight' => $faker->numberBetween(4, 8),
        'delivered' => $faker->boolean(),
        'pod_signature' => $faker->name,
        'invoicing_status' => $faker->numberBetween(0, 1),
        'carrier_pickup_required' => $faker->boolean(),
        'ship_reason' => $faker->randomElement(['sold', 'documents', 'gift', 'sample', 'transfer']),
        'collection_date' => $faker->dateTimeThisMonth,
        'ship_date' => $faker->dateTimeThisMonth,
        'delivery_date' => $faker->dateTimeThisMonth,
    ];
});

$factory->define(App\Address::class, function (Faker\Generator $faker) {
    return [
        'company_id' => $faker->numberBetween(1, 10),
        'name' => $faker->name,
        'company_name' => $faker->company,
        'address1' => $faker->streetAddress,
        'address2' => $faker->streetAddress,
        'address3' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postcode' => $faker->postcode,
        'country_code' => $faker->countryCode,
        'telephone' => $faker->phoneNumber,
        'email' => $faker->email,
        'type' => $faker->randomElement(['r', 'c']),
        'definition' => $faker->randomElement(['sender', 'recipient']),
    ];
});

$factory->define(App\ShipReason::class, function (Faker\Generator $faker) {
    return [
        'reason' => $faker->word,
        'enabled' => 1,
    ];
});

$factory->define(App\Commodity::class, function (Faker\Generator $faker) {
    return [
        'company_id' => $faker->numberBetween(1, 10),
        'description' => $faker->word,
        'product_code' => Str::random(10),
        'country_of_manufacture' => $faker->countryCode,
        'manufacturer' => $faker->company,
        'unit_value' => $faker->numberBetween(1, 30),
        'currency_code' => $faker->randomElement(['GBP', 'USD']),
        'unit_weight' => $faker->numberBetween(1, 5),
        'weight_uom' => 'kg',
        'uom' => 'ea',
        'commodity_code' => Str::random(10),
        'harmonized_code' => Str::random(10),
        'shipping_cost' => $faker->numberBetween(1, 30),
    ];
});

$factory->define(App\Package::class, function (Faker\Generator $faker) {
    return [
        'index' => $faker->numberBetween(1, 3),
        'weight' => $faker->numberBetween(10, 90),
        'length' => $faker->numberBetween(10, 90),
        'width' => $faker->numberBetween(10, 90),
        'height' => $faker->numberBetween(10, 90),
        'dry_ice_weight' => $faker->numberBetween(0, 3),
        'packaging_code' => Str::random(3),
        'carrier_packaging_code' => Str::random(3),
        'carrier_tracking_number' => Str::random(12),
        'barcode' => $faker->ean13(),
        'received' => $faker->boolean(),
        'date_received' => $faker->dateTimeThisMonth,
        'location' => $faker->randomElement(['sort', 'hold']),
        'shipment_id' => $faker->numberBetween(1, 50),
    ];
});

$factory->define(App\ShipmentContent::class, function (Faker\Generator $faker) {
    return [
        'package_index' => $faker->numberBetween(1, 3),
        'description' => $faker->word,
        'manufacturer' => $faker->company,
        'product_code' => Str::random(10),
        'commodity_code' => Str::random(10),
        'harmonized_code' => Str::random(10),
        'country_of_manufacture' => $faker->countryCode,
        'quantity' => $faker->numberBetween(1, 50),
        'uom' => $faker->randomElement(['ea', 'ml', 'mg', 'l']),
        'unit_value' => $faker->numberBetween(1, 30),
        'currency_code' => $faker->randomElement(['GBP', 'USD', 'EUR']),
        'unit_weight' => $faker->numberBetween(1, 5),
        'weight_uom' => 'kg',
        'shipment_id' => $faker->numberBetween(1, 50),
    ];
});

$factory->define(App\Service::class, function (Faker\Generator $faker) {
    return [
        'code' => strtoupper(Str::random(2)),
        'name' => strtoupper($faker->word),
    ];
});

$factory->define(App\Tracking::class, function (Faker\Generator $faker) {
    return [
        'message' => $faker->sentence,
        'status' => $faker->word,
        'datetime' => $faker->dateTimeThisMonth,
        'carrier' => $faker->randomElement(['FedEx', 'UPS', 'DHL', 'TNT']),
        'city' => $faker->city,
        'state' => $faker->state,
        'country_code' => $faker->countryCode,
        'postcode' => $faker->postcode,
        'source' => $faker->randomElement(['easypost', 'ifs']),
        'tracker_id' => strtoupper(Str::random(12)),
        'user_id' => 0,
        'shipment_id' => $faker->numberBetween(1, 50),
    ];
});

$factory->define(App\Label::class, function (Faker\Generator $faker) {
    return [
        'base64' => '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Y3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgICAgICAgICAgeG1sbnM6cGRmPSJodHRwOi8vbnMuYWRvYmUuY29tL3BkZi8xLjMvIgogICAgICAgICAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIj4KICAgICAgICAgPHhtcDpDcmVhdGVEYXRlPjIwMTYtMDctMjdUMTk6MTE6MjU8L3htcDpDcmVhdGVEYXRlPgogICAgICAgICA8eG1wOk1vZGlmeURhdGU+MjAxNi0wNy0yOFQxNDo0ODowMiswMTowMDwveG1wOk1vZGlmeURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTYtMDctMjhUMTQ6NDg6MDIrMDE6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDxwZGY6UHJvZHVjZXI+RlBERiAxLjc8L3BkZjpQcm9kdWNlcj4KICAgICAgICAgPHhtcE1NOkRvY3VtZW50SUQ+dXVpZDo0NGViZGE1MS1kMjEyLTRkZjMtYjYzZi01ZGFjMGY3NmZlNmI8L3htcE1NOkRvY3VtZW50SUQ+CiAgICAgICAgIDx4bXBNTTpJbnN0YW5jZUlEPnV1aWQ6OGRkNGFhNmYtOGNlYS00ZjM3LWFmZWItZTJjZTM3ZDMyZGFkPC94bXBNTTpJbnN0YW5jZUlEPgogICAgICAgICA8ZGM6Zm9ybWF0PmFwcGxpY2F0aW9uL3BkZjwvZGM6Zm9ybWF0PgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+DQplbmRzdHJlYW0NZW5kb2JqDTcgMCBvYmoNPDwvQ3JlYXRpb25EYXRlKEQ6MjAxNjA3MjcxOTExMjVaKS9Nb2REYXRlKEQ6MjAxNjA3MjgxNDQ4MDIrMDEnMDAnKS9Qcm9kdWNlcihGUERGIDEuNyk+Pg1lbmRvYmoNeHJlZg0KMCA4DQowMDAwMDAwMDAwIDY1NTM1IGYNCjAwMDAwMDIzMDAgMDAwMDAgbg0KMDAwMDAwMjQzOSAwMDAwMCBuDQowMDAwMDAzMjE1IDAwMDAwIG4NCjAwMDAwMDMzNTQgMDAwMDAgbg0KMDAwMDAwNDEzMSAwMDAwMCBuDQowMDAwMDA0MTk1IDAwMDAwIG4NCjAwMDAwMDczNTQgMDAwMDAgbg0KdHJhaWxlcg0KPDwvU2l6ZSA4L0lEWzw0MTZCQUM1OENBRjEwNjREODU5NkE2NzI1OUY4REM0OT48NDE2QkFDNThDQUYxMDY0RDg1OTZBNjcyNTlGOERDNDk+XT4+DQpzdGFydHhyZWYNCjExNg0KJSVFT0YNCg==',
        'shipment_id' => $faker->numberBetween(1, 50),
    ];
});
