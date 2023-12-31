<?php

namespace BasePack\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'rolename',
        'created_by',
        'modified_by'
    ];
    
    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';


    public function permissions(){
        return $this->hasManyThrough(
            Permission::class, 
            RoleHasPermission::class, 
            'permission_id','id');
    }    

}
