<?php

namespace Hosomikai\Kotsms\Helper;

class Faker
{
    /**
     * demo使用
     * 隨機產生驗證碼
     */
    public function generatePIN(int $digits = 6): string
    {
        $counter = 0;
        $pin = '';

        while ($counter < $digits) {
            $pin .= mt_rand(0, 9);
            ++$counter;
        }

        return $pin;
    }

    /**
     * 產生demo內容.
     */
    public function demoContent(): string
    {
        $verificationCode = $this->generatePIN();

        $content = <<<MSG
您的驗證碼為 {$verificationCode}。
此驗證碼10分鐘內有效。
提醒您，請勿將此驗證碼提供給其他人以保障您的使用安全。
MSG;

        return $content;
    }
}
