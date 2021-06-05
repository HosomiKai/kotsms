<?php

namespace Hosomikai\Kotsms\Resources;

class KotsmsQueryStatusResponse extends KotsmsResponse
{
    /**
     * statusstr 成功值
     */
    public const SUCCESSED = 'SUCCESSED';

    /**
     * 回傳是否成功
     */
    public function isSuccess(): bool
    {
        return self::SUCCESSED == $this->value;
    }

    /**
     * 處理回來的字串結果.
     */
    protected function handleResult(string $result): void
    {
        //查詢結果範例: statusstr=SUCCESSED
        parse_str($result, $data);

        $this->value = $data['statusstr'] ?? null;
    }
}
