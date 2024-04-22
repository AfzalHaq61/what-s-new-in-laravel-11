<?php

namespace App\Services;

class MemoizationService
{
    public function getResult()
    {
        once(fn() => $this->cachedResult = $this->expensiveOperation());
    }

    private function expensiveOperation()
    {
        sleep(2);
        return "Result of expensive operation";
    }
}