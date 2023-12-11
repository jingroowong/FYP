<?php

namespace App\Factories;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class AgentFactory extends AbstractUserFactory
{
    public function create(array $data): Agent
    {
        $rules = [

            //validate the rules
            'agentName' => 'required|max:30',
            'password' => 'required|max:15|confirmed|min:6',
            'agentEmail' => 'required|email|max:255|unique:tenants,tenantEmail|unique:agents,agentEmail|unique:admins,adminEmail',
            'agentPhone' => ['required', 'regex:/^01\d{1}-\d{7,8}$/','unique:tenants,tenantPhone','unique:agents,agentPhone','unique:admins,adminPhone'],
            'licenseNum' => ['nullable','regex:/^(?i)(ren|rea)\d{5}$/','unique:agents,licenseNum'],
        ];

        //execute the rules
        // $this->validate($data, $rules); 
       $this->validate($data, $rules);


        $latestAgent = Agent::orderBy('agentID', 'desc')->first();
        $latestAgentID = $latestAgent ? $latestAgent->agentID : 'AGT0000000';

        
        $newAgentID = 'AGT' . str_pad((int)substr($latestAgentID, 3) + 1, 7, '0', STR_PAD_LEFT);

        $agent = new Agent([
            'agentID' => $newAgentID,
            'agentName' => $data['agentName'], 
            'password' => Hash::make($data['password']),
            'agentEmail' => $data['agentEmail'], 
            'agentPhone' => $data['agentPhone'], 
            'licenseNum' => strtoupper($data['licenseNum']),
            'registerDate' =>Carbon::now()
        ]);

        return $agent;
    }
}