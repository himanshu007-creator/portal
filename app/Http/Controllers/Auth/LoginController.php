<?php

namespace App\Http\Controllers\Auth;

use Modules\User\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        switch ($provider) {
            case 'google':
                return Socialite::driver($provider)->with(['hd' => config('constants.gsuite.client-hd')])->redirect();
                break;

            default:
                return Socialite::driver($provider)->redirect();
                break;
        }
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        if ($authUser && $authUser->trashed()) {
            return redirect('login');
        }

        Auth::login($authUser, true);
        /**
         * Update user avatar to keep it update with gmail
         */
        $authUser->update(['avatar' => $user->avatar_original]);

        if (session('saml_request_for_website')) {
            if (!$authUser->website_user_role) {
                Auth::logout();
                return redirect('login');
            }

            return redirect(config('constants.website_url') . '/wp/wp-admin/');
        }

        return redirect('home');
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::withTrashed()
        ->where('provider_id', $user->id)
        ->orWhere('email', $user->email)
        ->first();

        if ($authUser) {
            $authUser->provider_id = $user->id;
            $authUser->save();
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
            'avatar' => '',
        ]);
    }
}
