<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTheme extends Model
{
    use HasFactory;

    protected $table = 'setting_themes';

    protected $fillable = [
        'name',
        'active',
        'created',
        'modified'
    ];
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';
}

