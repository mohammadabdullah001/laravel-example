<?php

namespace App\Action;



class AddNumberAction
{
    public function run(int $number): int
    {
        return $number + 2;
    }
}
