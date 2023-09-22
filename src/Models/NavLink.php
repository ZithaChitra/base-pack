<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavLink extends Model
{
    use HasFactory;

    protected $table = 'nav_links';

    protected $fillable = [
        'text',
        'url',
        'icon',
        'access',
        'visible',
        'classes',
        'active',
        'enabled',
        'parent_id',
        'created_by',
        'modified_by',
    ];
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';


    public function permissions(){
        return $this->hasManyThrough(
            Permission::class, 
            RoleHasPermission::class, 
            'permission_id','id');
    } 
    
    protected $casts = [
        'active' => 'array',
    ];
    

}