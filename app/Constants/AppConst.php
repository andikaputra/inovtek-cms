<?php

namespace App\Constants;

class AppConst
{
    const MAP_REFRESH_LOAD_MINUTE = 5;

    const MAP_REFRESH_LOAD_SECOND = 300; // 5 menit

    const MINIMUM_GALLERY_IMAGE = 10;

    const POINT_TYPE = [
        '01' => 'titik-awal',
        '02' => 'titik-sementara',
        '03' => 'titik-akhir',
    ];

    const POINT_TYPE_MAPPING = [
        self::POINT_TYPE['01'] => 'Titik Awal',
        self::POINT_TYPE['02'] => 'Titik Sementara',
        self::POINT_TYPE['03'] => 'Titik Akhir',
    ];

    const POINT_TYPE_ARR = [
        self::POINT_TYPE['01'],
        self::POINT_TYPE['02'],
        self::POINT_TYPE['03'],
    ];

    const SHORT_LINK_BASE_URL = 'simulasibencana.com'; // Change web to short link base url

    // const FRONT_END_BASE_URL = 'https://disaster-resilience-initiatives.netlify.app';
    const FRONT_END_BASE_URL = 'https://inovtek.bnpb.go.id';

    const CODE_EXISTING_APP = [
        '01' => 'vr-simulator',
        '02' => 'vr-360-tour',
        '03' => 'ar-wayfinder',
    ];

    const DISPLAY_EXISTING_APP = [
        '01' => 'VR Simulator',
        '02' => '360 VR Tour',
        '03' => 'AR Wayfinder',
    ];

    const MAP_EXISTING_APP = [
        self::CODE_EXISTING_APP['01'] => self::DISPLAY_EXISTING_APP['01'],
        self::CODE_EXISTING_APP['02'] => self::DISPLAY_EXISTING_APP['02'],
        self::CODE_EXISTING_APP['03'] => self::DISPLAY_EXISTING_APP['03'],
    ];

    const ALLOW_HOST = [
        'sangkuriang-cms.ganeshcomdemo.com',
        'disaster-resilience-initiatives-project.vercel.app',
        '172.16.10.38:8000',
        '127.0.0.1:8000',
        'cms.360demo.cloud',
        'inovtek.360demo.cloud',
        'localhost:3000',
        'disaster-resilience-initiatives.netlify.app',
        'cms.simulasibencana.com',
        'inovtek.simulasibencana.com',
        'inovtek.bnpb.go.id',
        '360.bnpb.go.id',
        'inovtek.bnpb.go.id',
        'cms-inovtek.bnpb.go.id',
        'user-inovtek.bnpb.go.id',
        'matomo-inovtek.bnpb.go.id',
    ];
}
