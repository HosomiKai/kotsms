<?php

namespace Hosomikai\Kotsms\Resources;

/**
 * Response Model
 *  -1	        CGI string error ，系統維護中或其他錯誤 ,帶入的參數異常,伺服器異常
 *  -2	        授權錯誤(帳號/密碼錯誤)
 *  -4	        A Number違反規則 發送端 870短碼VCSN 設定異常
 *  -5	        B Number違反規則 接收端 門號錯誤 -
 *  -6	        Closed User 接收端的門號停話異常090 094 099 付費代號等
 *  -20	        Schedule Time錯誤 預約時間錯誤 或時間已過
 *  -21	        Valid Time錯誤 有效時間錯誤
 *  -1000	    發送內容違反NCC規範
 *  -59999	    帳務系統異常 簡訊無法扣款送出
 *  -60002	    您帳戶中的點數不足
 *  -60014	    該用戶已申請 拒收簡訊平台之簡訊(2010 NCC新規)
 *  -999949999  境外IP限制(只接受台灣IP發送，欲申請過濾請洽簡訊王客服)
 *  -999959999	在12 小時內，相同容錯機制碼
 *  -999969999	同秒, 同門號, 同內容簡訊
 *  -999979999	鎖定來源IP
 *  -999989999	簡訊為空.
 */
class KotsmsResponse
{
    /**
     * 回傳訊息.
     *
     * @var string
     */
    protected $message;

    /**
     * 回傳結果.
     *
     * @var string
     */
    protected $value;

    public function __construct(string $result)
    {
        $this->handleResult($result);
    }

    /**
     * 回傳是否成功
     */
    public function isSuccess(): bool
    {
        return $this->value > 0;
    }

    /**
     * 取得回傳結果.
     *
     * @return void
     */
    public function getResult()
    {
        return $this->value;
    }

    /**
     * 回傳編碼取得結果訊息.
     */
    public function getMessage(): string
    {
        $message = '失敗';

        switch ($this->value) {
            case '-1':
                $message = 'CGI string error ，系統維護中或其他錯誤 ,帶入的參數異常,伺服器異常';
                break;
            case '-2':
                $message = '授權錯誤(帳號/密碼錯誤)';
                break;
            case '-4':
                $message = 'A Number違反規則 發送端 870短碼VCSN 設定異常';
                break;
            case '-5':
                $message = 'B Number違反規則 接收端 門號錯誤 -';
                break;
            case '-6':
                $message = 'Closed User 接收端的門號停話異常090 094 099 付費代號等';
                break;
            case '-20':
                $message = 'Schedule Time錯誤 預約時間錯誤 或時間已過';
                break;
            case '-21':
                $message = 'Valid Time錯誤 有效時間錯誤';
                break;
            case '-1000':
                $message = '發送內容違反NCC規範';
                break;
            case '-59999':
                $message = '帳務系統異常 簡訊無法扣款送出';
                break;
            case '-60002':
                $message = '您帳戶中的點數不足';
                break;
            case '-60014':
                $message = '該用戶已申請 拒收簡訊平台之簡訊 ( 2010 NCC新規)';
                break;
            case '-999949999':
                $message = '境外IP限制(只接受台灣IP發送，欲申請過濾請洽簡訊王客服)';
                break;
            case '-999959999':
                $message = '在12 小時內，相同容錯機制碼';
                break;
            case '-999969999':
                $message = '同秒, 同門號, 同內容簡訊';
                break;
            case '-999979999':
                $message = '鎖定來源IP';
                break;
            case '-999989999':
                $message = '簡訊為空';
                break;
            default:
                $message = $this->isSuccess()
                    ? '成功'
                    : '失敗';
                break;
        }

        return $message;
    }

    /**
     * 自訂結果格式.
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'message' => $this->getMessage(),
            'success' => $this->isSuccess(),
        ];
    }

    /**
     * 處理回來的字串結果.
     */
    protected function handleResult(string $result): void
    {
        //送出訊息格式範例: kmsgid=-2
        //送出訊息格式範例 kmsgid=308109377
        parse_str($result, $data);

        $this->value = $data['kmsgid'] ?? null;
    }
}
