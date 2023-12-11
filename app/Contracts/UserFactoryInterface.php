<?php

namespace App\Contracts;

use App\Models\Tenant;
use App\Models\Agent;
use App\Models\Admin;


interface UserFactoryInterface
{
    public function create(array $data): Tenant|Agent|Admin;
}