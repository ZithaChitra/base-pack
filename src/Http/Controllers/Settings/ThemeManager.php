<?php

namespace BasePack\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use BasePack\Models\SettingTheme;

class ThemeManager extends Controller
{
    public static function isThemeInstalled($themeName){
        $theme = SettingTheme::where('name', $themeName)->first();        
        if($theme){
            return true;
        }
        return false;
    }


    public function installTheme($themeName){
        // TODO: Not needed yet.
    }
}
