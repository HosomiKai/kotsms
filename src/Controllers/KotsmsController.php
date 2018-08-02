<?php

namespace Hosomikai\Kotsms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Hosomikai\Kotsms\Facade\Kotsms;
use Illuminate\Support\Facades\Validator;

class KotsmsController extends Controller
{
    /**
     * Demo 寄送簡訊頁面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $message = 'Hello Kotsms';
        return view('Kotsms::demo', compact('message'));
    }

    /**
     * 寄送簡訊
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function send(){
        $rules = [];
        $rules['to_number'] = 'required';
        $rules['send_content'] = 'required';

        $validator = Validator::make(request()->all(),$rules);
        if($validator->fails()){
            $message = $validator->fails()->first();
        }else{
            $verification_code = self::generatePIN();

            $content =<<<MSG
您的驗證碼為 {$verification_code} 。 此驗證碼10分鐘內有效。
提醒您，請勿將此驗證碼提供給其他人以保障您的使用安全。
MSG;
            $custom = request()->send_content;
            $content.= $custom . ' ' .$content;

            $kotsms = Kotsms::to(request()->to_number)->content($content)->send()->getStatus();
            $message = print_r($kotsms,true);
        }

        return view('Kotsms::welcome', compact('message'));
    }

    /**
     * 隨機產生驗證碼
     * @param int $digits
     * @return string
     */
    public function generatePIN($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }
}
