<?php
 namespace App\Helper;

 use File;
 use URL;
 use Illuminate\Support\Facades\Auth;
 use App\Models\Setting;
 use App\Models\UserProfile;
 use App\Models\AdminProfile;

  class Helpers {

 /**
     * get Logo URL
     */
    public static function get_logo_url($logo_type=""){
        $logoData = Setting::where('name',$logo_type)->first();
        if(isset($logoData->value) && $logoData->value!=''){
          return   URL::asset('storage/logo/'.$logoData->value);
        }
    }

    public static function hasPermission($route) {
        $user = Auth::user();
        if (!$user) return false;

        $permissions = json_decode($user->permissions, true) ?? [];

        if (in_array($route, $permissions)) {
            return true;
        }

        return false;
    }

    // public static function get_user_profile_img($user_id=""){
    //   $profile_image =  asset('/assets/images/resources/no-user.jpg');
    //   $profile_image_data =  UserProfile::where('user_id', $user_id)->where('name', 'profile_image')->first();
    //  // dd($profile_image_data);
    //   if(isset($profile_image_data)){
    //         if(file_exists(public_path('/uploads/admin-'.$user_id.'/'.$profile_image_data->value))){
    //            $profile_image =   asset('/uploads/admin-'.$user_id.'/'.$profile_image_data->value);
    //        }
    //    }
    //    return $profile_image;

    // }

    // public static function get_admin_profile_img($admin_id=""){
    //   $profile_image =  asset('/assets/images/resources/no-user.jpg');
    //   $profile_image_data =  AdminProfile::where('admin_id', $admin_id)->where('name', 'profile_image')->first();
    //  // dd($profile_image_data);
    //   if(isset($profile_image_data)){
    //         if(file_exists(public_path('/uploads/admin-'.$admin_id.'/'.$profile_image_data->value))){
    //            $profile_image =   asset('/uploads/admin-'.$admin_id.'/'.$profile_image_data->value);
    //        }
    //    }
    //    return $profile_image;
    // }

    // public static function envUpdate($key, $value){
    //     $path = base_path('.env');
    //       $old_env = env($key);
    //       // if($key == 'MAIL_PASSWORD'){
    //       //     $old_env = "'".env($key)."'";
    //       // }
    //     if(file_exists($path)){
    //       file_put_contents($path, str_replace(
    //         $key. '='. $old_env, $key . '=' .$value, file_get_contents($path)
    //       ));
    //     }

    // }

    // public static function slug_format($string)
    // {
    //     $base_string = $string;

    //     $string = preg_replace('/\s+/u', '-', trim($string));
    //     $string = str_replace('/', '-', $string);
    //     $string = str_replace('\\', '-', $string);
    //     $string = strtolower($string);

    //     $slug_string = $string;

    //     return $slug_string;
    // }
}
