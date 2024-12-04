<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        $roles = Role::get();
        return view('admin.profile', compact('user','roles'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
       // $user->username = $request->usermname;
       // $inputs = $request->all();
        $inputs = $request->except('image'); // Exclude 'image' from inputs for special handling

        // Check if an image was uploaded
        if ($request->hasFile('image')) {
            // Validate the image file
            $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            ]);
    
            // Store the image and get its path
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public'); // Store image in the 'public/images' directory
    
            // Optionally delete the old image
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
    
            // Add the image path to the inputs array
            $inputs['image'] = $imagePath;
        }
        // if($user->isDirty('username')) {
        //     $user->update([
        //         'username' => $request->username
        //     ]);
        // }

        $user->update($inputs);
        $flasher->success('Updated Successfull!.', ['Dash UI']);
        return redirect()->back();
    }

    public function passUpdate(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|confirmed|min:5'
        ]);
        $user->password = $request->password;

        if($user->isDirty('password')) {
            $hashPass = bcrypt($request->password);
            $user->update([
                'password' => $hashPass
            ]);
            $flasher->success('Password Updated', ['Dash UI']);
        }
        return redirect()->back();
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

        $flasher->success('Profile Updated', ['Dash UI']);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
