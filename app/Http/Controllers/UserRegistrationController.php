<?php
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Factories\TenantFactory;
use App\Factories\AgentFactory;

class UserRegistrationController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


    public function showTenantRegister()
    {
        return view('RegisterTenant');
    }

    public function showAgentRegister()
    {
        return view('RegisterAgent');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTenant(Request $request)
    {
        try {
            $data = $request->all();
        
            $data['tenantDOB'] = date("d-m-Y", strtotime($data['tenantDOB']));


            $TenantFactory = new TenantFactory();
            $tenant = $TenantFactory->create($data);
            $tenant->save();


            //store in user
            $user = new User([
                'id' => $tenant->tenantID,
                'name' => $tenant->tenantName, 
                'password' => $tenant->password,
                'email' => $tenant->tenantEmail,
                'avatar' =>'landlord.png'
            ]);
            $user->save();
        
            return redirect()->back()->with('success', 'You are successfully registered as Tenant');

        } catch (\InvalidArgumentException $ex) {
            
            $errors = json_decode($ex->getMessage(), true); 
        
            return redirect()->back()->withErrors($errors)->withInput();
        }

    }

    public function storeAgent(Request $request)
    {
        try {
            $data = $request->all();
            $AgentFactory = new AgentFactory();
            $agent = $AgentFactory->create($data);
            $agent->save();

            //store in user
            $user = new User([
                'id' => $agent->agentID,
                'name' => $agent->agentName, 
                'password' => $agent->password,
                'email' => $agent->agentEmail,
                'avatar' =>'agent.png'
            ]);
            $user->save();

            
        
            return redirect()->back()->with('success', 'You are successfully registered as Agent');
        } catch (\InvalidArgumentException $ex) {
           
            $errors = json_decode($ex->getMessage(), true); 
        
            return redirect()->back()->withErrors($errors)->withInput();
        }

    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
