<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'permission_name',
        'created_by',
        'modified_by',
    ];
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';
}
