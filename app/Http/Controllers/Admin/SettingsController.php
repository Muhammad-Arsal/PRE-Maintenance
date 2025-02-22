<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use App\Helper\Helpers;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use App\Models\SocialOptions;

class SettingsController extends Controller
{

    public function themeOptions(){
        $page['page_title'] = 'Manage Theme Options';
         $setting = Setting::pluck( 'value', 'name' )->toArray();
        return view('admin.settings.theme-options', compact('page', 'setting'));
    }

    public function setThemeOptions(Request $request){

        // dd($request->all());

        $user = Admin::where('id', Auth::guard('admin')->user()->id)->first();

        $data = $request->except('_token');

        if($request->hasFile('logo')){
              $logoImage = $request->file('logo');
              if(!empty($logoImage)){
                  $ext =  strtolower($logoImage->getClientOriginalExtension());
                  if(!($ext == 'jpg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif')){
                      return redirect()->back()
                              ->withFlashMessage('Please Select Valod Logo Extension')
                              ->withFlashType('errors');
                  }
                  $logoData = Setting::where('name', 'logo')->first();
                  //Delete the previous image
                  if(!empty($logoData)){
                    $existingLogo = $logoData->value;
                    File::delete(config('siteapp.logo').$existingLogo);
                  }
                  $logo =  'logo'.'.'.$ext;
                  $logoImage->move(config('siteapp.logo'), $logo);
                //   Setting::where('name','logo')->delete();
                Setting::updateOrCreate([
                    'name' => 'logo'
                ],[
                    'value'=>$logo,
                    'name' => 'logo',
                    'key' => 'logo',
                ]);
              }
        }
        if($request->hasFile('favicon')){
            $favicon = $request->file('favicon');
            if(!empty($favicon)){
                $ext =  strtolower($favicon->getClientOriginalExtension());
                if(!($ext == 'jpg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif')){
                    return redirect()->back()
                            ->withFlashMessage('Please Select Valod favicon Extension')
                            ->withFlashType('errors');
                }
                $data = Setting::where('name', 'favicon')->first();
                //Delete the previous image
                if(!empty($data)){
                  $existing = $data->value;
                  File::delete(config('siteapp.logo').$existing);
                }
                $faviconUpdated =  'favicon'.'.'.$ext;
                $favicon->move(config('siteapp.logo'), $faviconUpdated);
                // Setting::where('name','favicon')->delete();
                Setting::updateOrCreate([
                    'name' => 'favicon'
                ],[
                    'value'=>$faviconUpdated,
                    'name' => 'favicon',
                    'key' => 'logo',
                ]);
            }
      }
        return redirect()
        ->route( 'admin.settings.themeOptions' )
        ->withFlashMessage('Theme Options updated successfully!' )
        ->withFlashType( 'success' );

    }

    /*------ General Settings ------*/

    // public function generalSettingsView(){
    //     $page['page_title'] = 'Manage General Settings';
    //     $data = Setting::all();
    //    // dd($setting);
    //     return view('admin.settings.GeneralSettings.index', compact('page', 'data'));
    // }

    // public function editGeneralSetting(Setting $id){
    //      $data = $id;
    //     $page['page_title'] = 'Manage Settings';
    //     return view('admin.settings.GeneralSettings.editSetting', compact('page', 'data'));
    // }

    // public function saveGeneralSetting(Setting $id, Request $request){

    //     $request->validate([
    //         'name' =>'required',
    //         'key' =>'required',
    //         'value' =>'required',
    //     ]);

    //     $data =  $request->except('_token');

    //     $setting = $id;

    //     $setting->update($data);

    //     return redirect()
    //     ->route( 'admin.settings.generalSettingsView' )
    //     ->withFlashMessage( 'General Settings updated successfully!' )
    //     ->withFlashType( 'success' );
    // }

    // public function searchGeneralSettings(Request $request){
    //     $keywords =  $request['keywords'];

    //     $data =  Setting::where('view','general_settings')
    //                         ->where(function($query) use ($keywords){
    //                          $query->where('name', 'like', '%'.$keywords.'%')
    //                           ->orWhere('key', 'like', '%'.$keywords.'%')
    //                           ->orWhere('value', 'like', '%'.$keywords.'%')->get();
    //                         })->get();
    //     return view('admin.ajax.generalSettingsSearchData', compact('data'));
    // }

    // public function resetGeneralSettingsForm(){
    //     $data = Setting::all();
    //    // dd($setting);
    //     return view('admin.ajax.resetGeneralSettings', compact('data'));
    // }

    // public function socialOptions(){
    //     $page['page_title'] = 'Manage Social Configuration';
    //     $data = SocialOptions::all();
    //    return view('admin.settings.social-configuration', compact('page', 'data'));
    // }

    // public function setSocialOptions(Request $request){
    //     $data =  $request->except('_token');

    //     SocialOptions::truncate();
    //     $cols_len =  count($data['title']);

    //     for($i = 0; $i < $cols_len; $i++){
    //             SocialOptions::create( [
    //                 'title' => $data['title'][$i],
    //                 'url' => $data['url'][$i],
    //                 'icon' => $data['icon'][$i],
    //                 'sort' => $data['sort'][$i],
    //             ] );
    //     }

    //     return redirect()
    //     ->route( 'admin.settings.socialConfiguration' )
    //     ->withFlashMessage( 'Social Configuration updated successfully!' )
    //     ->withFlashType( 'success' );
    // }


    // public function smtpDetails(){
    //     $page['page_title'] = 'Smtp Details';
    //     $data = Setting::pluck( 'value', 'key' )->toArray();

    //    return view('admin.settings.smtp-details', compact('page', 'data'));
    // }

    // public function setSmtpDetails(Request $request){
    //     $data =  $request->except('_token');

    //     foreach ($data as $key => $value) {
    //         $setting = Setting::where('key', $key)->first();
    //         if(!isset($setting)){
    //             $setting = new Setting;
    //             $setting->key = $key;
    //             $setting->name =  str_replace('_',' ',ucfirst($key));
    //             $setting->value = $value;

    //             //update Mail env variables

    //             Helpers::envUpdate($key, $value);

    //             $setting->save();
    //         }else{
    //              //update Mail env variables
    //             Helpers::envUpdate($key, $value);
    //             $setting->value = $value;
    //             $setting->save();
    //         }
    //     }



    //     // $path = base_path('.env');
    //     // $key = 'DB_PASSWORD';
    //     // $value = 'root123';

    //     // if (file_exists($path)) {

    //     //     file_put_contents($path, str_replace(
    //     //         $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
    //     //     ));
    //     // }

    //     return redirect()
    //     ->route( 'admin.settings.smtpDetails' )
    //     ->withFlashMessage( 'Smtp Details updated successfully!' )
    //     ->withFlashType( 'success' );
    // }




    // public function settings(){

    //     // $user = \App\Models\Admin::where('id', Auth::guard('admin')->user()->id)->first();
    //     // $user_role = Role::find($user->role_id);
    //     // if(!isset($user_role)){
    //     //       return abort(404);
    //     // }else{

    //     //     $user_permissions =  json_decode($user_role->permisions, true);
    //     //     if(!in_array('manage_settings', $user_permissions)){
    //     //         return abort(404);
    //     //     }
    //     // }
    //     $page['page_title'] = 'Settings';
    //     $setting = Setting::pluck( 'value', 'name' )->toArray();
    //     return view('admin.settings.index', compact('setting','page'));
    // }

    // public function saveSettings(Request $request){
    //     $user = \App\Models\Admin::where('id', Auth::guard('admin')->user()->id)->first();
    //     // $user_role = Role::find($user->role_id);
    //     // if(!isset($user_role)){
    //     //       return abort(404);
    //     // }else{

    //     //     $user_permissions =  json_decode($user_role->permisions, true);
    //     //     if(!in_array('manage_settings', $user_permissions)){
    //     //         return abort(404);
    //     //     }
    //     // }
    //     $data = $request->except('_token');

    //     Setting::where([['name','!=', 'logo'],['name','!=','secondary_logo']])->delete();

    //     foreach ( $data as $key => $value ) {
    //         Setting::create( [
    //             'name' => $key,
    //             'key' => $key,
    //             'value' => $value==''?'  ':$value,
    //         ] );
    //     }
    //     if($request->hasFile('logo')){
    //           $logoImage = $request->file('logo');
    //           if(!empty($logoImage)){
    //               $ext =  strtolower($logoImage->getClientOriginalExtension());
    //               if(!($ext == 'jpg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif')){
    //                   return redirect()->back()
    //                           ->withFlashMessage('Please Select Valod Logo Extension')
    //                           ->withFlashType('errors');
    //               }
    //               $logoData = Setting::where('name', 'logo')->first();
    //               //Delete the previous image
    //               if(!empty($logoData)){
    //                 $existingLogo = $logoData->value;
    //                 File::delete(config('siteapp.logo').$existingLogo);
    //               }
    //               $logo =  base64_encode('logo-'.time()).'.'.$ext;
    //               $logoImage->move(config('siteapp.logo'), $logo);
    //               Setting::where('name','logo')->delete();
    //               Setting::create([
    //                   'name' => 'logo',
    //                   'value' => $logo
    //               ]);
    //           }
    //     }
    //     if($request->hasFile('secondary_logo')){
    //         $logoImage = $request->file('secondary_logo');
    //         if(!empty($logoImage)){
    //             $ext =  strtolower($logoImage->getClientOriginalExtension());
    //             if(!($ext == 'jpg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif')){
    //                 return redirect()->back()
    //                         ->withFlashMessage('Please Select Valod Logo Extension')
    //                         ->withFlashType('errors');
    //             }
    //             $logoData = Setting::where('name', 'secondary_logo')->first();
    //             //Delete the previous image
    //             if(!empty($logoData)){
    //               $existingLogo = $logoData->value;
    //               File::delete(config('siteapp.logo').$existingLogo);
    //             }
    //             $logo =  base64_encode('secondarylogo-'.time()).'.'.$ext;
    //             $logoImage->move(config('siteapp.logo'), $logo);
    //             Setting::where('name','secondary_logo')->delete();
    //             Setting::create([
    //                 'name' => 'secondary_logo',
    //                 'value' => $logo
    //             ]);
    //         }
    //   }
    //     return redirect()
    //     ->route( 'admin.settings' )
    //     ->withFlashMessage( 'Settings updated successfully!' )
    //     ->withFlashType( 'success' );
    //     // if($request->hasFile('logo')){

    //     //     $logoImage = $request->file('logo');

    //     //     if (!empty($logoImage)){

    //     //         $ext = strtolower($logoImage->getClientOriginalExtension());
    //     //         if(!($ext == 'jpg' || $ext == 'png' || $ext=='svg' || $ext=='gif')){
    //     //             return redirect()->back()
    //     //             ->withFlashMessage( 'Please Select Valid Logo Extension')
    //     //             ->withFlashType( 'errors' );
    //     //         }

    //     //         $logoData = Setting::where('name','logo')->first();

    //     //         if(!empty($logoData)){
    //     //             // deleting the previous image
    //     //             $existingLogo = $logoData->value;
    //     //             File::delete(config('iq.logo') . $existingLogo);
    //     //         }

    //     //         $logo = base64_encode('logo-'.time()) . '.' . $logoImage->getClientOriginalExtension();
    //     //         $logoImage->move(config('iq.logo'),$logo);

    //     //         Setting::where('name','=','logo')->delete();
    //     //         Setting::create([
    //     //                 'name' =>'logo',
    //     //                 'value'=> $logo
    //     //             ]);
    //     //     }


    //     // }

    //     // return redirect()
    //     // ->route( 'admin.settings.index' )
    //     // ->withFlashMessage( 'Settings updated successfully!' )
    //     // ->withFlashType( 'success' );


    // }
}
