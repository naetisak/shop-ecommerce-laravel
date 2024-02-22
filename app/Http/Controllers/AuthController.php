<?php

namespace App\Http\Controllers;

use App\Mail\ForgotMail;
use App\Models\User;
use DD4You\Dpanel\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 401);

        if (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            return response()->json(['msg' => 'Login Success'], 200);
        }

        return response()->json(['msg' => 'The provided credentials do not match our records'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->messages(), 401);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if (Auth::loginUsingId($user->id)) {
            $request->session()->regenerate();

            return response()->json(['msg' => 'Register Success'], 200);
        }

        return response()->json(['msg' => 'Failed! Please try again.'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) return response()->json(['msg' => 'The email field is required.'], 401);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(64);
            $user->remember_token = $token;
            $user->save();
            $link = route('update-password', ['token' => $token]);
            Mail::to($request->email)->send(new ForgotPassword($link));

            return response()->json(['msg' => 'Reset Email Send Successfully.'], 200);
        }

        return response()->json(['msg' => 'The provided email do not match our records'], 401);
    }

    public function updatePassword(Request $request){

        if($request->method() == "GET") return view('reset_password');

        $request->validate([
            'token'=>'required',
            'password'=>'required|confirmed'
        ]);

        $user = User::where('remember_token',$request->token)->first();
        if($user){
            $user->remember_token = null;
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('landing-page')->withSuccess('Password Reset Successfully');
        }
        return redirect()->route('landing-page')->withError('Invalid Token');
    }

}
