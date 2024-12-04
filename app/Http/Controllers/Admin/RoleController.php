<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:Admin|Role access|Role create|Role edit|Role delete', ['only' => ['index','show']]);
        $this->middleware('role_or_permission:Admin|Role create', ['only' => ['create','store']]);
        $this->middleware('role_or_permission:Admin|Role edit', ['only' => ['edit','update']]);
        $this->middleware('role_or_permission:Admin|Role delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['SuperAdmin'])->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);
    
        // Create the role
        $role = Role::create(['name' => $request->name]);
    
        // Retrieve permissions by ID and sync
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
    
        // Success message
       // $flasher->addSuccess('Role Created', 'Dash UI');
    
        // Redirect to roles index
        return redirect(route('admin.roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('admin.roles.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FlasherInterface $flasher)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        if (auth()->user()->hasRole($role->name) != $role->name & $role->name != 'SuperAdmin') {
            return view('admin.roles.edit', compact('role', 'permissions'));
        } else {
            $flasher->addError('Not Allowed', 'Dash UI');
            return redirect(route('admin.roles.index'));
        }

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
        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);
    
        // Find the role and update its name
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
    
        // Retrieve permissions by their IDs and sync them
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);
    
        // Add a success message
    
        // Redirect back to roles index
        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, FlasherInterface $flasher)
    {
        $role->delete();
        $flasher->addInfo('Role Deleted!', 'Dash UI');

        return redirect(route('admin.roles.index'));
    }
}
