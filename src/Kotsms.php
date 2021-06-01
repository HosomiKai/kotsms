<?php

namespace Hosomikai\Kotsms;

use Hosomikai\Kotsms\Exceptions\KotsmsException;
use Hosomikai\Kotsms\KotsmsResponse;

/**
 * 串接簡訊王 API 用來發送簡訊
 * https://www.kotsms.com.tw/.
 * https://www.kotsms.com.tw/index.php?selectpage=pagenews&kind=4&viewnum=238  api 文件.
 */
class Kotsms
{
    /**
     * api網址
     *
     * @var string
     */
    protected $apiUrl = 'http://api.kotsms.com.tw/kotsmsapi-1.php';

    /**
     * api大量發送
     *
     * @var string
     */
    protected $apiUrl2 = 'http://api.kotsms.com.tw/kotsmsapi-2.php';

    /**
     * 點數查詢api.
     *
     * @var string
     */
    protected $apiPoints = 'https://api.kotsms.com.tw/memberpoint.php';

    /**
     * 查詢訊息是否發送成功api.
     *
     * @var string
     */
    protected $apiStatusQuery = 'https://api.kotsms.com.tw/msgstatus.php';

    /**
     * 發送簡訊是否成功的狀態回報網址
     *
     * @var string optional
     */
    protected $returnUrl;

    /**
     * 簡訊王帳號
     *
     * @var string
     */
    protected $username;

    /**
     * 簡訊王密碼
     *
     * @var string
     */
    protected $password;

    /**
     * 目標門號
     *
     * @var string
     */
    protected $smsNumber;

    /**
     * 傳送內容.
     *
     * @var string
     */
    protected $content;

    public function __construct(string $username = null, string $password = null)
    {
        $this->username = is_null($username) ? config('kotsms.username') : $username;
        $this->password = is_null($password) ? config('kotsms.password') : $password;
        $this->returnUrl = config('kotsms.return_url');
    }

    /**
     * 設定收簡訊號碼
     *
     * @param $number 手機號碼
     *
     * @return $this
     */
    public function to(string $number): self
    {
        $this->smsNumber = $number;

        return $this;
    }

    /**
     * 設定簡訊內容.
     *
     * @param $content
     *
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = mb_convert_encoding($content, 'BIG5', 'auto');

        return $this;
    }

    /**
     * 設定簡訊發送成功回傳網址
     */
    public function setReturnUrl(string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * 發送簡訊.
     *
     * @return $this
     */
    public function send(): KotsmsResponse
    {
        $queryString = $this->getQueryString(
            $this->getPostData()
        );

        return new KotsmsResponse(
            $this->makeHttpGetRequest($this->apiUrl, $queryString)
        );
    }

    /**
     * 查詢會員剩餘可用點數.
     *
     * @return int|KotsmsException
     */
    public function queryUserPoints(): int
    {
        $queryString = $this->getQueryString([
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $response = new KotsmsResponse(
            $this->makeHttpGetRequest($this->apiPoints, $queryString)
        );

        if (!$response->isSuccess()) {
            throw new KotsmsException($response->getMessage());
        }

        return $response->getResult();
    }

    /**
     * 查詢訊息發送結果.
     *
     * @return void
     */
    public function queryStatus(string $kmsgid): KotsmsResponse
    {
        $queryString = $this->getQueryString([
            'username' => $this->username,
            'password' => $this->password,
            'kmsgid' => $kmsgid,
        ]);

        return new KotsmsResponse(
            $this->makeHttpGetRequest($this->apiStatusQuery, $queryString)
        );
    }

    /**
     * return url 接收回傳結果.
     *
     * @return void
     */
    public function parseResponse()
    {
        // kmsgid= 簡訊發送編號 (請以此編號核對發送結果)
        // dstaddr= 接收門號
        // dlvtime= 電信系統發出時間
        // donetime= 手機用戶端回報狀態時間(包含成功發送,無法投遞….等狀態)
        // statusstr= 狀態字串 DELIVERED, EXPIRED, DELETED, UNDELIVERABLE, ACCEPTED, UNKNOWN, REJECTED, SYNTAXERROR
    }

    public function getPostData(): array
    {
        return [
            'username' => $this->username,          //帳號
            'password' => $this->password,          //密碼
            'dstaddr' => $this->smsNumber,          //發送門號
            'smbody' => $this->content,             //簡訊內容 BIG5 須url編碼
            'dlvtime' => '0',                       //預約發送時間 YYYY/MM/DD hh24:mm:ss or 0=即時  須url編碼
            'vldtime' => '0',                       //有效期限 單位是秒或 YYYY/MM/DD hh24:mm:ss     須url編碼
            'response' => $this->returnUrl,         //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報。
        ];
    }

    public function getQueryString(array $postData): string
    {
        return http_build_query(
            $postData
        );
    }

    /**
     * 計算簡訊價錢.
     * 簡訊發送點數計算方式.
     * 70個字以內-----扣1點
     * 134個字以內----扣2點
     * 201個字以內----扣3點
     * 268個字以內----扣4點
     * 335個字以內----扣5點
     * 國際簡訊以3倍計點 (70個字以內------扣3點….依此類推).
     *
     * @param string $content 發送內容
     * @param bool   $isLocal 國內發送
     */
    public function countAmount(string $content = null, bool $isLocal = true): int
    {
        $amount = 0;
        $count = str_word_count($content);

        if ($count <= 70) {
            $amount = 1;
        } elseif ($count <= 134) {
            $amount = 2;
        } elseif ($count <= 201) {
            $amount = 3;
        } elseif ($count <= 268) {
            $amount = 4;
        } elseif ($count <= 335) {
            $amount = 5;
        } else {
            $amount = 5;
        }

        if (!$isLocal) {
            $amount = $amount * 3;
        }

        return $amount;
    }

    protected function makeHttpGetRequest(string $apiUrl, $queryString): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl . '?' . $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                    //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
