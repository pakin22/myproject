<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;




class AuthController extends Controller
{
    public function showLogin() {
        return view('content.login');
    }

    public function showRegister()
    {
        return view('content.register');
    }


    public function processRegister(Request $request)

    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create a new user record
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Redirect to the desired page after registration
        return redirect('/content');

}



    public function checkLogin(Request $request) {
        $credentials = $request->validate([
            //required เป็นค่าว่างไหม รูปแบบเป็น email
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //ค่าที่อยู่ใน $credentials มีหรือไม่
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/content');
        }
        return back()->withErrors([
            'email' => 'Creadentials do not match our records',
        ]);
    }
}
