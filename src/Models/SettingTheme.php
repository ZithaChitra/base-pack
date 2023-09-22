<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTheme extends Model
{
    use HasFactory;

    protected $table = 'setting_themes';
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';
}

