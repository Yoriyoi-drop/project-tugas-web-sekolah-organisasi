<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //1 array multi dimensi data
    // private $users = [
    //     ['username' => 'user1@example.com',
    //      'password' => 'password1',
    //      'nama' => 'admin'],
    //     ['username' => 'user2@example.com',
    //      'password' => 'password2',
    //      'nama' => 'User '],
    // ];
    private function getUser(): array{
        return[
            [
                ['username' => 'user1@example.com',
                   'password' => '$2y$12$aNhhQbuHo8c9Vk5ju4pj/OZixWJPghv3.nvHCMSdHeahrJCGTLFkG',
                   'nama' => 'admin'],
                ['username' => 'user2@example.com',
                   'password' =>'$2y$12$haONlgeql5c56fYOxW998eDw82.KfXU7Sved5dqkinN77SWH2L1Qm',
                   'nama' => 'User '],
            ]
            ];
    }
    public function index(){
        return view('login');
    }
    public function login(Request $request){
        // logic login
        $auth = $request->only('username','password');
        foreach($this->getUser()[0] as $user){
            if($user['username'] == $auth['username'] && Hash::check($auth['password'],$user['password'])){
                Session::put('user', $user);
                return redirect()->route('home');
            }
        }
        return back()->withErrors(['error'=> 'username/password anda salah']);
    }
    public function dashboard(){
        if (!Session::has('user')) {
            return redirect()->route('login');
        }
        $user = Session::get('user');
        return view('welcome',compact('user'));
    }
    public function login2(){
        return view('login2');
    }
}


