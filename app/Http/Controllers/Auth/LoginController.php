<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SuccessReponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function getUserBySessionId(Request $request) {
        $sessionId = $request->header('SessionId');
        $user = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('sessions.session_id', '=', $sessionId)
            ->select('users.*')
            ->get();

        return $user;
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        $sessionId = uniqid();
        DB::table('sessions')->insert(
          array(
              "session_id" => $sessionId,
              "user_id" => $authUser->id
          )
        );
        return redirect("http://localhost:3000/login/$sessionId");
    }

    public function findOrCreateUser($user, $provider) {
        $authUser = User::where('provider_id', $user->id)->first();
        if($authUser) {
            return $authUser;
        }
        return User::create([
            'name'   => $user->name,
            'email'  => $user->email,
            'provider' => strtoupper($provider),
            'provider_id' => $user->id
        ]);
    }

    public function logout(Request $request) {
        $sessionId = $request->header('SessionId');

        DB::table('sessions')->where('session_id', '=', $sessionId)->delete();
        Auth::logout();
        $response = new SuccessReponse("Logout Success");

        return response()->json($response);
    }
}
