<?php

$wa = env('AGRINOVA_WHATSAPP');

return [
    // format nomor: 628123456789 (tanpa +, spasi, atau tanda hubung)
    'whatsapp' => $wa,
    'whatsapp_url' => $wa
        ? 'https://wa.me/' . $wa . '?text=' . rawurlencode('Halo Agrinova, saya ingin bertanya.')
        : '#',
    'address' => env('AGRINOVA_ADDRESS'),
];