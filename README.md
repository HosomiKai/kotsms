# Kotsms 簡訊王非官方 Laravel 發送簡訊 API

## Installation


Via Composer

### 1. Require the Package

```
$ composer require hosomikai/kotsms
```

### 2. Set up env `.env`

```
KOTSMS_USERNAME="your username"
KOTSMS_PASSWORD="your password"
```

如果你想載入demo範例
```
KOTSMS_LOAD_DEMO=true
```

如果你要接收簡訊王發送簡訊後的結果
```
KOTSMS_RETURN_URL="your url for accepts kotsms request after send sms"
```

## Usage

### 1. 發送簡訊

```
use Kotsms; 

...
    $content = 'hello world!';   //發送內容
    $smsNumber = '0911123456';   //發送對象手機號碼
    
    $costPoints = Kotsms::countAmount($content);    //試算此封簡訊會花費多少點數

    //發送簡訊
    $response = Kotsms::to($smsNumber)
                    ->content($content)
                    ->send();
    
    $response->isSuccess();     //是否成功
    $response->getMessage();    //回傳成功或錯誤訊息
    $response->toArray();

```

toArray格式：

```
//成功
[
    'value' => 'kmsgid',
    'message' => '成功',
    'success' => true,
];

//失敗
[
    'value' => '-60002',
    'message' => '您帳戶中的點數不足',
    'success' => false,
];

```

### 2. 查詢點數

```
use Kotsms; 

...

//剩餘點數
$points = Kotsms::queryUserPoints();
    
```

### 3. 查詢簡訊發送狀態

```
use Kotsms; 
...
    $reposnse = Kotsms::queryStatus($kmsgid);

    if ($response->isSuccess()) {
        //成功
    } else {
        //失敗
    }
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## TODO
- 
