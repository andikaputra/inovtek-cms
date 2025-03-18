<?php

namespace App\Constants;

class SeoConst
{
    const SEO_TYPE_KEY = [
        '01' => 'post',
        '02' => 'page',
    ];

    const SEO_TYPE_DESC = [
        '01' => 'Postingan',
        '02' => 'Halaman',
    ];

    const SEO_TYPE_MAP = [
        self::SEO_TYPE_KEY['01'] => self::SEO_TYPE_DESC['01'],
        self::SEO_TYPE_KEY['02'] => self::SEO_TYPE_DESC['02'],
    ];

    const SEO_TYPE_ARR = [
        self::SEO_TYPE_KEY['01'],
        self::SEO_TYPE_KEY['02'],
    ];
}
