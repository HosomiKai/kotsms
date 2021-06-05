<?php

namespace Hosomikai\Kotsms\Resources;

class KotsmsQueryPointResponse extends KotsmsResponse
{
    /**
     * 處理回來的字串結果.
     */
    protected function handleResult(string $result): void
    {
        //查詢結果範例 -2
        //查詢結果範例 308
        $this->value = $result;
    }
}
