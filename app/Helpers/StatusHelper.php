<?php

if (!function_exists('statusIndonesia')) {

    function statusIndonesia($status)
    {
        return match ($status) {
            'Pending'    => 'Menunggu',
            'Accepted'   => 'Diterima',
            'Processing' => 'Diproses',
            'Ready'      => 'Siap Disajikan',
            'Completed'  => 'Selesai',
            'Cancelled'  => 'Dibatalkan',
            'Refund'     => 'Pengembalian Dana',
            'Refunded'   => 'Pengembalian Dana',
            'Void'       => 'Void',
            default      => $status,
        };
    }

    if (!function_exists('visitTypeIndonesia')) {
        function visitTypeIndonesia($type)
        {
            return match($type) {
                'Dine In' => 'Makan di Tempat',
                'Take Away' => 'Bawa Pulang',
                default => $type,
            };
        }
    }
}