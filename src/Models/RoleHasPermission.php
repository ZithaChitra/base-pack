<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;

    protected $table = 'role_has_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'created_by',
        'modified_by',
    ];
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';
}
