<?php

return [
    'username' => env('KOTSMS_USERNAME'),                   //簡訊王帳號
    'password' => env('KOTSMS_PASSWORD'),                   //簡訊王密碼
    'return_url' => env('KOTSMS_RETURN_URL'),               //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報。
];
