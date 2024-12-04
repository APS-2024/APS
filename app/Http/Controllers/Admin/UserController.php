<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUser;
use App\Models\Admin\Adunitpercen;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    use HasRoles;

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|User access|User create|User edit|User delete', ['only' => ['index','show']]);
        $this->middleware('role_or_permission:Admin|User create', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|User edit', ['only' => ['edit','update']]);
        $this->middleware('role_or_permission:Admin|User delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        foreach ($users as $user) {
            foreach ($user->roles as $role){
                if ($role->name == 'SuperAdmin' ) {
                    $adminId = $user->id;
                }
            }
        }

        $users = User::whereNotIn('id', [$adminId])->get();
       // $users = User::get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles = Role::whereNotIn('name', ['SuperAdmin'])->get();
       // $selectedOptions = $request->input('options', []);
        $selectedOptions = session('selectedOptions', []);

        return view('admin.users.create', compact('roles','selectedOptions'));
    }

    public function processCheckboxes(Request $request)
    {
        $selectedOptions = $request->input('options', []);
        // Store the selected options in the session
        session(['selectedOptions' => $selectedOptions]);

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'redirectUrl' => route('admin.users.create')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUser $request, FlasherInterface $flasher)
    {
        $inputs = $request->all();
        $inputs['plain_password']=$request->password;
        $user = User::create($inputs);

      $unitdata=  $request->ad_unit_percen;
if(!empty($unitdata)){
      foreach($unitdata as $list){

$table = new Adunitpercen();

$table->ad_unit_id= $list['ad_unit_id'];
$table->site_name= $list['site_name'];
$table->percentage= $list['percentage'];
$table->user_id= $user->id;
$table->save();

      }
    }

        if($inputs['role'] != 0 ) {
           
            $user->syncRoles('Client');
        } else {
            $user->assignRole('Client');
        }



        $flasher->addSuccess('User Created', ['Dash UI']);
        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('admin.users.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        // If User Role is SuperAdmin it will be Redirected;
        foreach ($user->roles as $role){
            if ($role->name == 'SuperAdmin' || auth()->user()->hasRole($role->name)) {
                $flasher->addError('Not Allowed', 'Dash UI');
                return redirect(route('admin.users.index'));
            }
        }

        $roles = Role::whereNotIn('name', ['SuperAdmin'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function editUser($id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        // If User Role is SuperAdmin it will be Redirected;
        foreach ($user->roles as $role){
            if ($role->name == 'SuperAdmin' || auth()->user()->hasRole($role->name)) {
                $flasher->addError('Not Allowed', 'Dash UI');
                return redirect(route('admin.users.index'));
            }
        }

        $roles = Role::whereNotIn('name', ['SuperAdmin'])->get();
        return view('admin.users.edit-user', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);
        $user->username = $request->usermname;
        $userRole = $request->role;
        $inputs = $request->all();

        if($user->isDirty('username')) {
            $user->update([
                'username' => $request->username
            ]);
        }

        $user->syncRoles($userRole);

        $user->update($inputs);



        $flasher->addSuccess('User "'.$user->name.'" updated.', 'Dash UI');
        return redirect(route('admin.users.index'));
    }

    public function updateUser(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);
        // $user->username = $request->usermname;
        $userRole = $request->role;
        $inputs = $request->all();

        // if($user->isDirty('username')) {
        //     $user->update([
        //         'username' => $request->username
        //     ]);
        // }

        $user->syncRoles($userRole);

        $user->update($inputs);

        $unitdata=  $request->ad_unit_percen;
        if(!empty($unitdata)){
              foreach($unitdata as $list){

          $unitlist=  Adunitpercen::select('*')->where('ad_unit_id',$list['ad_unit_id'])->first();
        if(empty($unitlist)){
        $unitlist = new Adunitpercen();
        }

        $unitlist->ad_unit_id= $list['ad_unit_id'];
        $unitlist->site_name= $list['site_name'];
        $unitlist->percentage= $list['percentage'];
        $unitlist->user_id=$id;
        
        $unitlist->save();

        $adUnitIds[] = $list['ad_unit_id'];
       
              }
              $adUnitIdsString = implode(',', $adUnitIds);

              // Update the user's ad_unit_id field with the new value
              $user->ad_unit_id = $adUnitIdsString;
              $user->save();

            }

        $flasher->addSuccess('update successfull.', ['Dash UI']);
        return redirect(route('admin.users.index'));
    }


    public function passUpdate(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|confirmed|min:5'
        ]);
        $user->password = $request->password;
        $user->plain_password=$request->password;

        if($user->isDirty('password')) {
            $hashPass = bcrypt($request->password);
            $user->update([
                'password' => $hashPass
            ]);
            //$flasher->addSuccess('Password Updated');
        }

        return redirect(route('admin.users.index'));
    }

    public function othersUpdate(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        $request->validate([
           'phone'      => 'required|numeric|min:11',
        ]);

        $inputs = $request->all();
        $user->phone = $inputs['phone'];

        if ($user->isDirty('phone')) {
            $user->update($inputs);
        } else {
            $user->update([
                'location'  =>  $inputs['location'],
                'about'     =>  $inputs['about']
            ]);
        }

      //  $flasher->addSuccess('Profile Updated', 'Dash UI');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FlasherInterface $flasher, User $user)
    {
        // If User Role is SuperAdmin it will be Redirected;
        foreach ($user->roles as $role){
            if ($role->name == 'SuperAdmin' || auth()->user()->hasRole($role->name)) {
               // $flasher->addError('Not Allowed', 'Dash UI');
                return redirect(route('admin.users.index'));
            }
        }

        $user->delete();
      //  $flasher->addInfo('User Deleted Successfully', 'Dash UI');
        return redirect()->back();
    }



    public function deleteUnit($unitid){

      $delete= Adunitpercen::where('ad_unit_id',$unitid)->delete();
    
      $user = User::whereRaw("FIND_IN_SET(?, REPLACE(ad_unit_id, ' ', ''))", [$unitid])->first();

      if ($user) {
          // Get the current ad_unit_id value
          $adUnitIds = explode(',', $user->ad_unit_id);
      
          // Remove the specific value
          $adUnitIds = array_filter($adUnitIds, function($value) use ($unitid) {
              return $value != $unitid;
          });
      
          // Join the remaining values back into a string
          $user->ad_unit_id = implode(',', $adUnitIds);
      
          // Save the updated record
          $user->save();
        }
if(!empty($delete)){

    return response()->json([
        'status' => true,
        'message' => 'Delete successfull!'
    ]);

}else{

    return response()->json([
        'status' => false,
        'message' => 'Something wentwrong!'
    ]);

}

    }

    public function allowDisallow(Request $request, $id)
    {

        // Get the value from the request
        $allow = $request->input('user_allow');

        // Find the user by id
        $user = User::where('id', $id)->first();
        $unitid= $user->ad_unit_id;
        
        // Update the user_allow field with the new value
        if ($user) {

        $user->user_allow = $allow;
            $user->save();  // Save the changes to the database

            $unitIds = explode(',', $unitid);

            // Update the adunit_reports table
            DB::table('adunit_reports')
                ->whereIn('ad_unit_id', $unitIds)  // Filter by ad_unit_id values
                ->update(['user_allow' => $allow]);

        }
    
        // Optionally, return a response
        return response()->json([
            'success' => true,
            'user_allow' => $allow,
            'message' => 'User allow/disallow status updated successfully',
        ]);
    }

    public function view($client_id){

 
    
    $user= User::where('id',$client_id)->first();
    $roles = Role::get();
    return view('admin.users.view',compact('user','roles'));
    
    
        
    }


}
