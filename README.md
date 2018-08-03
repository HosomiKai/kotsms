# Kotsms 簡訊王發送簡訊API

適用對象:以Laravel 5 欲使用簡訊服務

實作版本：Laravel 5.6

## Step 1 : Download the package

composer命令安裝	
```
composer require hosomikai/kotsms
```
或者是新增package至composer.json
```
"require": {
  "hosomikai/kotsms": "dev-master"
},
```
然後更新安裝
```
composer update
```
或全新安裝
```
composer install
```

## Step 2 : Modify config file**

增加`config/app.php`中的`providers`和`aliases`的參數 。
```
'providers' => [ 
        // ... 
        Hosomikai\Kotsms\KotsmsServiceProvider::class, 
]

'aliases' => [ 

        // ... 
        
        'Kotsms' => Hosomikai\Kotsms\Facade\Kotsms::class, 
]
```

## Step 3 : Publish config to your project**

執行下列命令，將package的config檔配置到你的專案中
```
php artisan vendor:publish
```
至config/kotsms.php中確認 Kotsms 設定：

    return [
        'username' => env('Kotsms_Username', ''),       //簡訊王帳號
        'password' => env('Kotsms_Password', ''),       //簡訊王密碼
        'ReturnURL' => env('Kotsms_ReturnURL', ''),     //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報。
    ];

**How To Use -->發送簡訊

在Controller中
      
    use Kotsms; 
    public function Demo()
    {   
        $content = 'hello world!';   //發送內容
        $to_number = '0987987978';   //發送對象手機號碼
        
        Kotsms::to(to_number)->content($content)->send();               //發送簡訊
        $result = Kotsms::to(to_number)->content($content)->send()->getStatus();  //發送簡訊，並回傳發送結果狀態
    }
