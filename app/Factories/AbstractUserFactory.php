<?php

namespace App\Factories;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Contracts\UserFactoryInterface;


abstract class AbstractUserFactory implements UserFactoryInterface
{
    protected function validate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new InvalidArgumentException(json_encode($validator->errors()->toArray()));
        }
    }
}
