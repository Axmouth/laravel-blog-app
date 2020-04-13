<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;

class MyprofileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user_id = auth()->user()->id;
        $user =  User::find($user_id);

        $data = array(
            'posts' => $user->posts,
            'user' => $user,
        );

        return view('userprofiles.dashboard')->with($data);
    }

    public function show()
    {
        $user =  auth()->user();

        if (!$user) {
            abort(404, 'Resource not found');
        }

        $data = array(
            'user' => $user,
        );

        return view('userprofiles.myprofile')->with($data);
    }

    public function security()
    {
        $user =  auth()->user();

        if (!$user) {
            abort(404, 'Resource not found');
        }

        $data = array(
            'user' => $user,
        );

        return view('userprofiles.mysecurity')->with($data);
    }

    public function profileUpdate(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'nullable',
                'profile_image' => 'image|nullable|max:1999',
            ]
        );

        // Edit Post
        $user = auth()->user();

        if ($request->input('name')) {
            $name_used = User::whereName($request->input('name'))->first();
            if ($name_used) {
                return redirect('/myprofile')->with('error', 'Cannot change to name that is already in use');
            }
        }

        // Handle file upload
        if ($request->hasFile('profile_image')) {
            // Get filename with the extension
            $filenameWithExt =  $request->file('profile_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload image
            $path = $request->file('profile_image')->storeAs('public/profile_images', $fileNameToStore);
        }

        if ($request->input('name')) {
            $user->name = $request->input('name');
        }

        if ($request->hasFile('profile_image')) {
            $old_image = $user->profile_image;
            $user->profile_image = $fileNameToStore;
            // Delete old image
            Storage::delete('public/profile_images/' . $old_image);
        }
        $user->save();

        return redirect('/myprofile')->with('success', 'Profile Updated');
    }

    public function removeProfileImage(Request $request)
    {
        $user = auth()->user();
        $old_image = $user->profile_image;
        $user->profile_image = '';
        $user->save();
        Storage::delete('public/profile_images/' . $old_image);

        return redirect('/myprofile')->with('success', 'Profile Image Removed');
    }
}
