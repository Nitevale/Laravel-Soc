<?php

namespace App\Http\Controllers;

use App\Models\User;
use Nette\Utils\Image;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class pagecontroller extends Controller
{
    public function showcorrecthomepage() {
        if (auth()->check()) {
            return view('homepage-feed');
        } else {
            return view('home-guest');
        }
    }
    public function login(Request $request) {
        $incomingfields = $request->validate([
            'loginusername'=>'required',
            'loginpassword'=>'required'
        ]);

        if (auth()->attempt(['username'=>$incomingfields['loginusername'], 'password'=>$incomingfields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success','You have successfully logged in!');
        } else {
            return redirect('/')->with('failure', 'invalid login');
        }
    }

    public function logout() {
        auth()->logout();
        return redirect('/')->with('failure','You have successfully logged out!');
    }

    public function register(Request $request) {
        
        $incomingfields = $request->validate([
            'username'=>['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email'=>['required', 'email', Rule::unique('users', 'email')],
            'password'=>['required', 'min:8', 'confirmed']
        ]);
        
        $incomingfields['password'] = bcrypt($incomingfields['password']);

        User::create($incomingfields);

        return 'Hey! welcome to my vlog controller page!';

   }

   public function profile(User $user) {
        return view('profile-posts', ['avatar' => $user->avatar, 'username' => $user->username, 'posts' => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
   }

   public function showAvatarForm() {
        return view('avatar-form');
   }

   public function storeAvatar(Request $request) {
        $request->validate([
            'avatar'=>'required|image|max:4000'
        ]);

        $user = auth()->user();
        
        $filename = $user->id . '-' . uniqid() . '.jpg';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file("avatar"));
        $imgData = $image->cover(120, 120)->toJpeg();
        Storage::put("public/avatar/" . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != '/fallback-avatar.png') {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with('success', 'Updated!');
   }

//    public function storeAvatar(Request $request) {
//         $request->file('avatar')->store('public/avatar');
//         return 'hey';
//    }
}
