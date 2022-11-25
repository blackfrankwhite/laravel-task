<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'is_verified' => 'boolean',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_verified' => $request->is_verified
        ]);
       
        $token = $user->tokens()->create([
            'access_token' => hash('sha256', Str::random(40)),
            'expires_at' => Carbon::now()->addDays(30)
        ]);
     
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();
            
        if(!$user) return false;

        if(!Hash::check($request->password, $user->password)) return 'wrong credentials';

        $token = $user->tokens()->create([
            'access_token' => hash('sha256', Str::random(40)),
            'expires_at' => Carbon::now()->addDays(30)
        ]);
     
        return response()->json(['token' => $token], 200);
    }   
}