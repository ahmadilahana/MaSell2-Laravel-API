<?php

namespace App\Http\Controllers\api;


use Carbon\Carbon;
use App\Models\Seller;
use App\Mail\EmailVerify;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class SellerController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:seller',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            $emailErrorMessage = $validator->errors()->messages()['email'][0];
            $passwordErrorMessage = $validator->errors()->messages()['password'][0];
            return response()->json([
                "status" => "fails",
                "message" => !empty($emailErrorMessage) ? $emailErrorMessage : $passwordErrorMessage
            ], 400);
        }else{


            $locationInfo = AppHelper::getLocationInfoByIp();
            $current_date_time = time();
            $deviceId = $request->header("user-agent");

            $verifyCode = rand(10000,99999);

            AppHelper::sendEmail($request->get('email'), $verifyCode);


            $id = DB::table('seller')->insertGetId([
                'email' => $request->get('email'),
                'emailVerify' => 0,
                'emailVerifyId' => $verifyCode,
                'emailVerifyIdExpired' => $current_date_time + 3000,
                'countryCode' => $locationInfo['countryCode'],
                'country' => $locationInfo['countryName'],
                'registerAt' => $current_date_time,
                'deviceId' => $deviceId,
                'isActived' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'password' => Hash::make($request->get('password'))
            ]);

            if($id){
                return response()->json([
                    "status" => "success",
                    "message" => 'We send you a code verify to ' . $request->get('email'),
                    "sellerId" => $id,
                ], 200);
            }else{
                return response()->json([
                    "status" => "fails",
                    "message" => 'failed register!!'
                ], 400);
            }
        }
    }

    public function verification(Request $request){
        $code = $request->get('code');
        $sellerId = $request->get("sellerId");

        $seller = DB::table('seller')->where('sellerId', $sellerId)->get()->first();

        if($seller){

            if(time() > $seller->emailVerifyIdExpired){

                return response()->json([
                    "status" => "fails",
                    "message" => 'Expired code!'
                ], 400);

            }else{

                if($seller->emailVerifyId == $code){

                    $sellerUpdate['emailVerify'] = 1;
                    $sellerUpdate['emailIsVerifyAt'] = time();
                    $sellerUpdate['emailVerifyId'] = '';
                    $sellerUpdate['emailVerifyIdExpired'] = 0;
                    $sellerUpdate['isActived'] = 1;
                    $sellerUpdate['updated_at'] = Carbon::now()->toDateTimeString();

                    $affected = DB::table('seller')->where('sellerId', $sellerId)->update($sellerUpdate);

                    if($affected){

                        return response()->json([
                            "status" => "success",
                            "message" => 'Successfully verified!',
                        ], 200);

                    }

                }else{
                    return response()->json([
                        "status" => "fails",
                        "message" => 'Invalid code!'
                    ], 400);
                }
            }

        }else{
            return response()->json([
                "status" => "fails",
                "message" => 'Unautorization seller'
            ], 400);
        }

    }

    public function resendCode(Request $request){

        $sellerId = $request->get('sellerId');
        $email = $request->get('email');

        $validator = Validator::make($request->all(), [
            'sellerId' => 'required',
            'email' => 'required',
        ]);


        if($validator->fails()){
            $sellerIdError = $validator->errors()->messages()['sellerId'][0];
            $emailError = $validator->errors()->messages()['email'][0];
            return response()->json([
                "status" => "fails",
                "message" => !empty($sellerIdError) ? $sellerIdError : $emailError
            ], 400);
        }



        $verifyCode = rand(10000,99999);

        $dataSeller['emailVerifyId'] = $verifyCode;
        $dataSeller['emailVerifyIdExpired'] = time() + 300;
        $dataSeller['updated_at'] = Carbon::now()->toDateTimeString();



        $affected = DB::table('seller')->where('sellerId', $sellerId)->update($dataSeller);

        if($affected){

            AppHelper::sendEmail($email, $verifyCode);

            return response()->json([
                "status" => "success",
                "message" => 'Resend Code Successfully!',
            ], 200);

        }else{

            return response()->json([
                "status" => "fails",
                "message" => 'Resend Code Failed!',
            ], 400);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $email = $request->get('email');
        $password = $request->get('password');



        try {
            if (! $token = auth('seller-api')->attempt(['email' => $email, 'password' => $password, 'isActived' => 1])) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $status = 'success';
        $message = 'Is LoggedIn!';
        $data = [
            "token" => $token,
            "seller" => auth('seller-api')->user()->only(['sellerId', 'username', 'avatarUrl', 'email', 'country'])
        ];

        return response()->json(compact('status', 'message', 'data'));
    }

    public function refreshToken()
    {
        $token = auth('seller-api')->refresh();

        $status = 'success';
        $message = 'Is LoggedIn!';
        $data = [
            "token" => $token,
            "seller" => auth('seller-api')->user()->only(['sellerId', 'username', 'avatarUrl', 'email', 'country'])
        ];

        return response()->json(compact('status', 'message', 'data'));
        
    }
}
