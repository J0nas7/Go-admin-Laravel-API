<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Corcel\Model\User;

class AuthController extends Controller
{
    // Test if user logon is an admin in WP
    public function adminValidate($name): bool {
        $user = User::where("user_login", $name)->orWhere("user_email", $name)->first();
        $isAdmin = strpos($user->meta->clk_027e37803a_capabilities, "administrator");
        if ($isAdmin !== false) {
            echo $name.": IsAdmin / ";
            echo $user->meta->clk_027e37803a_capabilities." / ";
            return true;
        } else {
            echo $name.": NotAdmin / ";
            echo $user->meta->clk_027e37803a_capabilities." / ";
            return false;
        }
    }

    // Login with WP credentials
    public function adminLogin(Request $request) {
        $theUsername = $request->username ?? "";
        $thePassword = $request->password ?? "";

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

        // If input request fails validation
        if ($validator->fails()) {
            $loginFailed = true;
            $errorMsg = "Empty username or password.";
        }

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
        } else {
            $loginFailed = true;
            $errorMsg = "Login Attempt Failed";
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
