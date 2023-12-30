<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Helpers\GlobalFunctions as CommonHelper;
use Validator;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{

    public function register(Request $request)
    {
      try{
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string||min:6|confirmed',
            'type' => 'required',
            'gender' => 'required',
            'phone_number' => 'required|numeric|min:10',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number'=>$request->phone_number,
            'type'=>$request->type,
            'gender'=>$request->gender,
            'country_id'=>$request->country_id,
            'state_id'=>$request->state_id,
            'city_id'=>$request->city_id,
        ]);
        $res = $user->save();
        if($res)
        {
        return response()->json([
            "success"=>"true",
            'message' => 'Register Successfully'
        ], 201);
       }
       else
       {
         return response()->json([
             "success"=>"false",
             'message' => 'Register does not Successful'
         ], 401);
       }
     }
     catch(\Exception $e)
      {
        return response()->json([
          "success"=>"false",
          'message'=>$e->getMessage(),
         ]);
       }
    }


    public function login(Request $request)
    {
        try
        {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
               "success"=>"false",
                'message' => 'Invalid email and Password'
            ], 401);
          }

         $user = $request->user();
         $token = $user->createToken('api-token')->plainTextToken;
         if($user->image)
         {
           $image = url("public/user_profile/".$user->image);
         }
         else
         {
           $image = url("public/user_profile/dp.png");
         }

        return response()->json([
            'success'=> "true",
            "message"=>"Login Successfully",
            'access_token' => $token,
            'user_id'=>$user->id,
            'email'=>$user->email,
            'name'=>$user->name,
            'type'=>$user->type,
            'phone_number'=>$user->phone_number,
            'gender'=>$user->gender,
            'image'=>$image,
            'token_type' => 'Bearer',

        ]);
      }
      catch(\Exception $e)
       {
         return response()->json([
           "success"=>"false",
           'message'=>$e->getMessage(),
          ]);
        }
    }


    public function logout(Request $request)
    {
      try
      {
      $request->user()->tokens()->delete();


        return response()->json([
            'success'=>'true',
            'message' => 'Logout Successfully'
        ]);
      }
      catch(\Exception $e)
       {
         return response()->json([
           "success"=>"false",
           'message'=>$e->getMessage(),
          ]);
        }
    }


    public function getuser(Request $request)
    {
      try
      {
        $country = Country::where('id',$request->user()->country_id)->first();
        $state = States::where('id',$request->user()->state_id)->first();
        $city = Cities::where('id',$request->user()->city_id)->first();
        $data['user_id'] = $request->user()->id;
        $data['email'] = $request->user()->email;
        $data['name'] = $request->user()->name;
        $data['type'] = $request->user()->type;
        $data['phone_number'] = $request->user()->phone_number;
        $data['gender'] = $request->user()->gender;
        $data['country_id'] = $request->user()->country_id;
        $data['state_id'] = $request->user()->state_id;
        $data['city_id'] = $request->user()->city_id;
        $data['country_name'] = $country->name;
        $data['state_name'] = $state->name;
        $data['city_name'] = $city->name;
        if($request->user()->image)
        {
          $data['image'] = url("public/user_profile/".$request->user()->image);
        }
        else
        {
          $data['image'] = url("public/user_profile/dp.png");
        }
        return response()->json([
            'success'=>'true',
            'result'=>$data,
            'message' => 'User profile'
        ]);
      }
      catch(\Exception $e)
       {
         return response()->json([
           "success"=>"false",
           'message'=>$e->getMessage(),
          ]);
        }
    }


    public function forgetPassword(Request $request)
    {
      try
      {
       $validator = Validator::make($request->all(), [
       'email' => 'required|string'
       ]);
      if ($validator->fails())
      {
        return response()->json(['error'=>$validator->errors()], 401);
      }
      else
     {
         $users = User::where('email',$request->input('email'))->first();

       if(!empty($users))
       {
         $name = $users->name;
         $password = rand(6,999999);
         $email = $users->email;

         $mailData = array('newpassword'=>$password,'name'=>$users->name);
         $emailresult = CommonHelper::sendmail('info@faruhi.com', 'Faruhi', $email, $name, 'New Password' , ['data'=>$mailData], 'emails.forgotpassword','',$attachment=null);


         if($emailresult)
         {
           $isUpdate =  User::where('email',$request->input('email'))->update(array('password' => bcrypt($password),'updated_at'=> Carbon::now()));

            return response()->json([
            'success'=>"true",
            'message' => 'New Password is Send To Email'
        ]);

         }
          else
           {
            return response()->json([
                'success'=>"false",
                'message' => 'Password is Not Send to Email'
            ], 200);
           }
       }
       else
        {
         return response()->json([
            'success'=>"false",
            'message' => 'Email is Not Exist'
          ], 200);
       }
      }
    }
    catch(\Exception $e)
     {
       return response()->json([
         "success"=>"false",
         'message'=>$e->getMessage(),
        ]);
      }

    }

    public function changePassword(Request $request)
    {
        try
        {
           $validator = Validator::make($request->all(), [
             'password' => 'required|string|confirmed|min:6',
             'old_password' => 'required|min:6',

           ]);
           if ($validator->fails())
           {
              return response()->json(['error'=>$validator->errors()], 401);
           }
           else
           {
             if (Hash::check(request('old_password'), $request->user()->password))
             {
               $updated_password = bcrypt(request('password'));
               $users = User::where('id',$request->user()->id)->update(['password' => $updated_password]);
               if($users)
               {
                   return response()->json([
                  'success'=>"true",
                  'message' => 'Password Updated Successfully'
              ]);

               }
               else
               {
                 return response()->json([
                  'success'=>"false",
                  'message' => 'Password is Not Update'
                ], 200);
               }
             }
             else
             {
               return response()->json([
                'success'=>"false",
                'message' => 'The old password is incorrect'
              ], 200);
             }

           }
         }
         catch(\Exception $e)
          {
            return response()->json([
              "success"=>"false",
              'message'=>$e->getMessage(),
             ]);
           }
    }

    public function updateProfile(Request $request)
    {
        try
        {
           $validator = Validator::make($request->all(), [
             'name' => 'required|string',
             'gender' => 'required',
             'phone_number' => 'required|numeric|digits:10',
             'country_id' => 'required',
             'state_id' => 'required',
             'city_id' => 'required',

           ]);
           if ($validator->fails())
           {
              return response()->json(['error'=>$validator->errors()], 401);
           }
           else
           {
             if (!empty($request->image))
             {
               if (preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $request->image))
               {
                 $folderPath = public_path().'/user_profile/';
                 $image_parts = explode(";base64,", $request->image);
                 $image_type_aux = explode("image/", $image_parts[0]);
                 $image_type = $image_type_aux[1];
                 $image_base64 = base64_decode($image_parts[1]);
                 $file = time().uniqid() . '.'.$image_type;
                 $file1 = $folderPath .$file;
                 file_put_contents($file1, $image_base64);
                 $data['image'] = $file;
               }
             }
             $data['name'] = $request->name;
             $data['gender'] = $request->gender;
             $data['phone_number']= $request->phone_number;
             $data['country_id']= $request->country_id;
             $data['state_id']= $request->city_id;
             $data['city_id']= $request->city_id;

               $users = User::where('id',$request->user()->id)->update($data);
               if($users)
               {
                   return response()->json([
                  'success'=>"true",
                  'message' => 'Profile Updated Successfully'
              ]);

               }
               else
               {
                 return response()->json([
                  'success'=>"false",
                  'message' => 'Profile is Not Update'
                ], 200);
               }
           }
         }
         catch(\Exception $e)
          {
            return response()->json([
              "success"=>"false",
              'message'=>$e->getMessage(),
             ]);
           }
    }

    public function allCountry(Request $request)
    {
      try{
      $user = Country::get();

      if($user)
       {
         $output['success'] ="true";
         $output['message'] ="get Country";
         $output['result'] = $user;
       }
       else
       {
         $output['success'] ="true";
         $output['message'] ="No record found";
       }

      echo json_encode($output);
      exit;
    }
    catch(\Exception $e)
     {
       return response()->json([
         "success"=>"false",
         'message'=>$e->getMessage(),
        ]);
      }
    }

    public function getStates($id)
    {
      try{
      $user = States::where('country_id',$id)->get();

      if($user)
       {
         $output['success'] ="true";
         $output['message'] ="get state";
         $output['result'] = $user;
       }
       else
       {
         $output['success'] ="true";
         $output['message'] ="No record found";
       }

      echo json_encode($output);
      exit;
    }
    catch(\Exception $e)
     {
       return response()->json([
         "success"=>"false",
         'message'=>$e->getMessage(),
        ]);
     }
    }

    public function getCity($id)
    {
      try{
      $user = Cities::where('state_id',$id)->get();

      if($user)
       {
         $output['success'] ="true";
         $output['message'] ="get city";
         $output['result'] = $user;
       }
       else
       {
         $output['success'] ="true";
         $output['message'] ="No record found";
       }
       echo json_encode($output);
       exit;
     }
     catch(\Exception $e)
      {
        return response()->json([
          "success"=>"false",
          'message'=>$e->getMessage(),
         ]);
      }

    }

    public function getCompanies()
    {
      try{
      $users = User::where('type',2)->get();

      if(!empty($users))
      {
        foreach($users as $user)
        {
          if($user->image)
          {
            $user->image  = url("public/user_profile/".$user->image);
          }
          else
          {
            $user->image  = url("public/user_profile/dp.png");
          }

        }
      }

      if($user)
       {
         $output['success'] ="true";
         $output['message'] ="get Country";
         $output['result'] = $user;
       }
       else
       {
         $output['success'] ="true";
         $output['message'] ="No record found";
       }

      echo json_encode($output);
      exit;
    }
    catch(\Exception $e)
     {
       return response()->json([
         "success"=>"false",
         'message'=>$e->getMessage(),
        ]);
      }
    }








}
