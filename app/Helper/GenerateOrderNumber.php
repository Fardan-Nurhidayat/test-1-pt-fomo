<?php

namespace App\Helper;

class GenerateOrderNumber
{
    public static function generate(): string
    {
        $timestamp = now()->format('YmdHis');
        $randomString = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        return 'ORD-' . $timestamp . '-' . $randomString;
    }
}
