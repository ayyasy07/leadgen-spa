<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gallery — Max Featured
    |--------------------------------------------------------------------------
    |
    | Berapa banyak foto gallery yang bisa di-featured sekaligus.
    | Kalo lebih dari ini → yang paling lama (featured_at paling awal)
    | otomatis ke-unfeatured.
    |
    */

    'gallery_max_featured' => (int) env('SPA_GALLERY_MAX_FEATURED', 10),

];