<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Corcel\Model\User;

class AuthController extends Controller
{
    private $request;

    // Instantiate a new controller instance
    public function __construct(Request $request) {
        //$this->request = json_decode($request->input('postContent'));
        //$this->request = json_decode($request->input('postContent'));
        $this->request = json_decode($request->input('postContent'));
    }

    /**
     * adminValidate
     * Validate if user login is an admin in WP
     *
     * @param string $name
     * @return bool
     */
    public function adminValidate($name): bool {
        $user = User::where("user_login", $name)->orWhere("user_email", $name)->first();
        $isAdmin = strpos($user->meta->clk_027e37803a_capabilities, "administrator");
        if ($isAdmin !== false) {
            /*echo $name.": IsAdmin / ";
            echo $user->meta->clk_027e37803a_capabilities." / ";*/
            return true;
        } else {
            /*echo $name.": NotAdmin / ";
            echo $user->meta->clk_027e37803a_capabilities." / ";*/
            return false;
        }
    }

    /**
     * adminLoggedInTest
     * Validate if adminLoggedIn Session is set or not
     *
     * @return response json
     */
    public function adminLoggedInTest(Request $request) {
        if (Session::get('adminLoggedIn')) {
            return response()->json([
                'success' => true,
                'message' => 'Is logged in',
                'data'    => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Is NOT logged in',
                'data'    => false
            ], 200);
        }
    }

    /**
     * adminLogout
     * Log out WP admin user by deleting the Session
     *
     * @return response json
     */
    public function adminLogout() {
        Session::forget('adminLoggedIn');
        Session::flush();
        //$request->session()->forget('adminLoggedIn');
        //$request->session()->flush();
        return response()->json([
            'success' => true,
            'message' => 'Is logged out',
            'data'    => true
        ], 200);
    }

    /**
     * adminLogin
     * Login WP admin with username/email and password credentials
     *
     * @param  string $request->username
     * @param  string $request->password
     * @return response json
     */
    public function adminLogin(Request $request) {
        $theUsername = $this->request->username ?? $request->username;
        $thePassword = $this->request->password ?? $request->password;

        // Validate that input request is filled
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        /*$validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);*/

        $loginFailed = false;
        $errorMsg = "";

        // Login attempts with both email and name
        $authResultWithEmail = Auth::validate([
            'email' => $theUsername, // or using 'username' too
            'password' => $thePassword,
        ]);
        $authResultWithName = Auth::validate([
            'username' => $theUsername, // or using 'email' too
            'password' => $thePassword,
        ]);
        
        // If a login attempt succeeds, check if it's an admin
        if ($authResultWithEmail || $authResultWithName) {
            $isAdmin = $this->adminValidate($theUsername);
            
            // Login attempt succeeded and it's an admin
            if ($isAdmin) {
                Session::put('adminLoggedIn', 'yes');
                return response()->json([
                    'success' => true,
                    'message' => 'Admin Login',
                    'data'    => true
                ], 200);
            // Login attempt succeeded but it's not an admin
            } else {
                $loginFailed = true;
                $errorMsg = "Not an Admin";
            }
        // Login attempt failed
        } else if (!$validator->fails() && empty($errorMsg)) {
            $loginFailed = true;
            $errorMsg = "Login Attempt Failed";
        }

        // If input request fails validation
        if ($validator->fails() && empty($errorMsg)) {
            $loginFailed = true;
            $errorMsg = "Empty request";
        }

        if ($loginFailed) {
            return response()->json([
                'success' => false,
                'message' => (!empty($errorMsg) ? $errorMsg : 'Admin Login Failed '),
                'data'    => false
            ], 200);
        }
    }
}
