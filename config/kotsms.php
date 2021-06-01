<?php

return [
    'username' => env('Kotsms_Username', ''),       //簡訊王帳號
    'password' => env('Kotsms_Password', ''),       //簡訊王密碼
    'ReturnURL' => env('Kotsms_ReturnURL', ''),     //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報。

    'load_demo_service' => false,
];
