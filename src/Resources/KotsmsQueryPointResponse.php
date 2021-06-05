<?php

namespace Hosomikai\Kotsms\Resources;

class KotsmsQueryPointResponse extends KotsmsResponse
{
    /**
     * 處理回來的字串結果.
     */
    protected function handleResult(string $result): void
    {
        $this->value = $result;
    }
}
