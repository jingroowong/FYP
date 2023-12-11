<?php

namespace App\Factories;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class TenantFactory extends AbstractUserFactory
{
    public function create(array $data): Tenant
    {
        $rules = [

            //validate the rules
            'tenantName' => 'required|max:20',
            'password' => 'required|max:15|confirmed|min:6',
            'tenantEmail' => 'required|email|max:255|unique:tenants,tenantEmail|unique:agents,agentEmail|unique:admins,adminEmail',
            'tenantPhone' => ['required', 'regex:/^01\d{1}-\d{7,8}$/','unique:tenants,tenantPhone','unique:agents,agentPhone','unique:admins,adminPhone'],
            'tenantDOB' => [
                'required',
                'date_format:d-m-Y',
                function ($attribute, $value, $fail) {
                    $currentDate = date('d-m-Y');
                    if (strtotime($value) > strtotime($currentDate)) {
                        $fail('The selected Date of Birth must be a date before the current date.');
                    }
                },
            ],
            'gender' => 'required',
        ];

        //execute the rules
        // $this->validate($data, $rules); 
       $this->validate($data, $rules);


        $latestTenant = Tenant::orderBy('tenantID', 'desc')->first();
        $latestTenantID = $latestTenant ? $latestTenant->tenantID : 'TNT0000000';

        
        $newTenantID = 'TNT' . str_pad((int)substr($latestTenantID, 3) + 1, 7, '0', STR_PAD_LEFT);

        $tenant = new Tenant([
            'tenantID' => $newTenantID,
            'tenantName' => $data['tenantName'], 
            'password' => Hash::make($data['password']),
            'tenantEmail' => $data['tenantEmail'], 
            'tenantPhone' => $data['tenantPhone'], 
            'tenantDOB' => $data['tenantDOB'], 
            'gender' => $data['gender'],
            'registerDate' => Carbon::now()
        ]);

        return $tenant;
    }
}