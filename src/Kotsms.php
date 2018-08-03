<?php
namespace Hosomikai\Kotsms;


class Kotsms
{
    /**
     * api網址
     * @var string
     */
    protected $ServiceURL = 'http://api.kotsms.com.tw/kotsmsapi-1.php';

    /**
     * api大量發送
     * @var string
     */
    protected $ServiceURL2 = 'http://api.kotsms.com.tw/kotsmsapi-2.php';

    /**
     * 簡訊王帳號
     * @var string
     */
    protected $Username = 'username';

    /**
     * 簡訊王密碼
     * @var string
     */
    protected $Password = 'password';

    /**
     * 目標門號
     * @var null
     */
    protected $to_number = null;

    /**
     * 傳送內容
     * @var null
     */
    protected $Content = null;

    /**
     * 傳送參數
     * @var array|string
     */
    protected $Send = 'Send';

    /**
     * 回傳結果
     * @var string
     */
    protected $Result = 'Result';

    public function __construct()
    {
        $this->Send = array(
            "username" => config('kotsms.username'),   //帳號
            "password" => config('kotsms.password'),   //密碼
            "dstaddr" => '',                                //發送門號
            "smbody" => '',                                 //簡訊內容 BIG5 須url編碼
            "dlvtime" => '0',                               //預約發送時間 YYYY/MM/DD hh24:mm:ss or 0=即時 須url編碼
            "vldtime" => '0',                               //有效期限 單位是秒或 YYYY/MM/DD hh24:mm:ss 須url編碼
            "response" => config('kotsms.ReturnURL'),  //發送簡訊是否成功的狀態回報網址, 若不宣告此參數時為不回報。
        );
    }

    /**
     * send sms
     * @param array $arParameters
     * @return $this
     */
    public function send(array $arParameters = array()){
        $this->Send['dstaddr'] = $this->to_number;
        $this->Send['smbody'] = $this->Content;

        $query = self::process($arParameters);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->ServiceURL.'?'.$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //他會將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出
        $result = curl_exec($ch);
        curl_close($ch);

        //$result = json_decode($result,true);
//        $fp = fopen("sendmsg.txt","a+");
//        fwrite( $fp, $result);
//        fclose($fp);

        $this->Result = $result;
        return $this;
    }

    /**
     * set phone number
     * @param $number
     * @return $this
     */
    public function to($number){
        $this->to_number = $number;
        return $this;
    }

    /**
     * set content
     * @param $content
     * @return $this
     */
    public function content($content){
        $this->Content = mb_convert_encoding($content, "BIG5","auto");
        return $this;
    }

    /**
     * http build query
     * @param array $arParameters
     * @return string
     */
    protected function process(array $arParameters){
        foreach ($this->Send as $key => $value){
            if(isset($arParameters[$key])){
                $this->Send[$key] = $arParameters[$key];
            }
        }
        $query = http_build_query($this->Send);
        return $query;
    }

    /**
     * get send sms status
     * @param null $str
     * @return array
     */
    public function getStatus($str = null){
        $str = $this->Result;

        $status = explode('=',$str);
        $resp = array(
            "data" => [],
            "errors" => [],
            "success" => false,
            "status_code" => 400
        );
//        return $status;
        $data = [
            $status[0] => rtrim($status[1]),
            'message' => ''
        ];
        switch ($data[$status[0]]){
            case "-1":
                $data['message'] = 'CGI string error ，系統維護中或其他錯誤 ,帶入的參數異常,伺服器異常';
                break;
            case '-2':
                $data['message'] = '授權錯誤(帳號/密碼錯誤)';
                break;
            case '-4':
                $data['message'] = 'A Number違反規則 發送端 870短碼VCSN 設定異常';
                break;
            case '-5':
                $data['message'] = 'B Number違反規則 接收端 門號錯誤 -';
                break;
            case '-6':
                $data['message'] = 'Closed User 接收端的門號停話異常090 094 099 付費代號等';
                break;
            case '-20':
                $data['message'] = 'Schedule Time錯誤 預約時間錯誤 或時間已過';
                break;
            case '-21':
                $data['message'] = 'Valid Time錯誤 有效時間錯誤';
                break;
            case '-1000':
                $data['message'] = '發送內容違反NCC規範';
                break;
            case '-59999':
                $data['message'] = '帳務系統異常 簡訊無法扣款送出';
                break;
            case '60002':
                $data['message'] = '您帳戶中的點數不足';
                break;
            case '-60014':
                $data['message'] = '該用戶已申請 拒收簡訊平台之簡訊 ( 2010 NCC新規)';
                break;
            case '-999959999':
                $data['message'] = '在12 小時內，相同容錯機制碼';
                break;
            case '-999969999':
                $data['message'] = '同秒, 同門號, 同內容簡訊';
                break;
            case '-999979999':
                $data['message'] = '鎖定來源IP';
                break;
            case '-999989999':
                $data['message'] = '簡訊為空';
                break;
            default:
                if((int)$data[$status[0]] > 0 ){
                    $data['message'] = '發送成功';
                    $resp['success'] = true;
                    $resp['status_code'] = 200;
                }else{
                    $data['message'] = '發送失敗';
                    $resp['success'] = false;
                    $resp['status_code'] = 400;
                }
                break;
        }
        $resp['data'] = $data;

        if(!$resp['success']){

            $resp['errors'] = [
                'message' => $data['message']
            ] ;
        }else{
            unset($resp['errors']);
        }

        return $resp;
    }
//-1	        CGI string error ，系統維護中或其他錯誤 ,帶入的參數異常,伺服器異常
//-2	        授權錯誤(帳號/密碼錯誤)
//-4	        A Number違反規則 發送端 870短碼VCSN 設定異常
//-5	        B Number違反規則 接收端 門號錯誤 -
//-6	        Closed User 接收端的門號停話異常090 094 099 付費代號等
//-20	        Schedule Time錯誤 預約時間錯誤 或時間已過
//-21	        Valid Time錯誤 有效時間錯誤
//-1000	        發送內容違反NCC規範
//-59999	    帳務系統異常 簡訊無法扣款送出
//-60002 	    您帳戶中的點數不足
//-60014	    該用戶已申請 拒收簡訊平台之簡訊 ( 2010 NCC新規)
//-999959999	在12 小時內，相同容錯機制碼
//-999969999	同秒, 同門號, 同內容簡訊
//-999979999	鎖定來源IP
//-999989999	簡訊為空

    /**
     * count sms price
     * @param $content
     * @param bool $is_local
     * @return int
     */
    public function getPrice($content, $is_local = true){
//簡訊發送點數計算方式  
//70個字以內-----扣1點
// 134個字以內----扣2點   
//201個字以內----扣3點 
// 268個字以內----扣4點   
//335個字以內----扣5點
//國際簡訊以3倍計點 (70個字以內------扣3點….依此類推) 
        $price = 0;
        $count = str_word_count($content);
        if($count <= 70){
            $price = 1;
        }elseif($count <=134){
            $price = 2;
        }elseif($count <= 201){
            $price = 3;
        }elseif($count <= 268){
            $price = 4;
        }elseif($count <= 335){
            $price = 5;
        }else{
            $price = 5;
        }

        if(!$is_local){
            $price = $price*3;
        }
        return $price;
    }
}