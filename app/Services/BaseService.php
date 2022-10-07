<?php

namespace App\Services;

class BaseService
{
    public function checkMedia($input, $count)
    {
        if (count(array($input)) > $count) {
            return (
            [
                'is_fail' => true,
                'code' => '002',
                'message' => 'Error media',
            ]
            );
        }
        return ['is_fail' => false];
    }
}
