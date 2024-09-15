<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Network;

use Mail;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class UserController extends Controller
{
    public function loadRegister(){
        return view('authentication.register');
    }

    public function registered(Request $request){
        $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $referralCode = Str::random(10);
        $token = Str::random(50);

        if(isset($request->referral_code)){

           $userData = User::where('referral_code', $request->referral_code)->get();

           if(count($userData) > 0){

                $userId = User::insertGetId([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'referral_code' => $referralCode,
                    'remember_token' => $token,
                ]);

                Network::insert([
                    'referral_code' => $request->referral_code,
                    'user_id' => $userId,
                    'parent_user_id' => $userData[0]['id'],
                ]);

           }else{
            return back()->with('error', 'Please enter valide referral code');
           }

        }else{
            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'referral_code' => $referralCode,
                'remember_token' => $token,
            ]);
        }

        // send mail when user register

        $domain = URL::to('/');
        $url = $domain.'/referral-register?ref='.$referralCode;

        $data['url'] = $url;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['title'] = 'Registered';

        $sendMail = Mail::send('content.emails.registerMail', ['data' =>$data], function($message) use($data){
            $message->to($data['email'])->subject($data['title']);
        });


        // email varifications
        $url = $domain.'/email-verification/'.$token;

        $data['url'] = $url;
        $data['name'] = $request->name;
        $data['title'] = 'Referral Verification Mail';

        $sendMail = Mail::send('content.emails.verifyMail', ['data' =>$data], function($message) use($data){
            $message->to($data['email'])->subject($data['title']);
        });


        return back()->with('success', 'Your Registration has been successfully & Please verify your mail!.');
    }

    public function loadReferralRegister(Request $request){
        if(isset($request->ref)){
            $referral = $request->ref;
            $userData = User::where('referral_code', $referral)->get();

            if(count($userData) > 0){

                return view('authentication.referralRegister', compact('referral'));

            }else{
                return view('404');
            }
        }else{
            return redirect('/');
        }
    }

    public function emailVerifications($token){

       $userData = User::where('remember_token', $token)->get();

        if(count($userData) > 0){
            if($userData[0]['is_varified'] ==1){
                return view('verified', ['message'=>'Your mail is already verified']);
            }

            User::where('id', $userData[0]['id'])->update([
                'is_varified' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
            return view('verified', ['message'=>'Your '. $userData[0]['email'] . 'mail is successfully verified']);
        }else{
            return view('verified', ['message'=> '404 Page not Found']);
        }
    }

    public function loadLogin(){
        return view('authentication.login');
    }

    public function userLogin(Request $request){

        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required',
        ]);

        $userData = User::where('email', $request->email)->first();
        if(!empty($userData)){
            if($userData->is_varified == 0){
                return back()->with('error', 'Please verify your mail');
            }
        }else{
            return back()->with('error', 'User not found');
        }

        $userCredentials = $request->only('email' , 'password');
        if(Auth::attempt($userCredentials)){
            return redirect('/dashboard');
        }else{
            return back()->with('error', 'Invalid email or password');
        }
    }

    public function loadDashboard(){

        $networkCount = Network::where('parent_user_id', Auth::user()->id)->orwhere('user_id', Auth::user()->id)->count();
        $networkData = Network::with('user')->where('parent_user_id', Auth::user()->id)->get();

        $shareComponents = \Share::page(
            URL::to('/').'referral-register?ref='.Auth::user()->referral_code,
            'Share and Earn Points by Referral Link',
        )
        ->facebook()
        ->twitter()
        ->linkedin()
        ->telegram()
        ->whatsapp();
        return view('content.dashboard', compact(['networkCount', 'networkData', 'shareComponents']));
    }

    public function logout(Request $request){

        $request->session()->flush();
        Auth::logout();
        return redirect('/login');
    }

    public function referralTrack(){

        $dateLabels = [];
        $dateData = [];

        for($i=30; $i >= 0; $i--){

            $dateLabels[] = Carbon::now()->subDays($i)->format('d-m-Y');
            $dateData[] = Network::whereDate('created_at', Carbon::now()->subDays($i)->format('Y-m-d'))->where('parent_user_id', Auth::user()->id)->count();
        }

        $dateLabels = json_encode($dateLabels);
        $dateData = json_encode($dateData);

        return view('content.referralTrack', compact(['dateLabels', 'dateData']));
    }

    public function deleteAccount(){
        try{

            User::where('id', Auth::User()->id)->delete();
            // Network::where('user_id', Auth::User()->id)->delete();
            $request->session()->flush();
            Auth::logout();

            return responce()->json(['success'=>true, 'msg'=>'Account deleted successfully']);
        }catch(\Exception $e){
            return responce()->json(['success'=>false, 'msg'=>$e->getMessage()]);
        }
    }
}
