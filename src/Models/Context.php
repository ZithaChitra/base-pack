<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    use HasFactory;

    protected $table = 'contexts';

    protected $fillable = [
        'name',
        'active',
        'created_by',
        'modified_by',
    ];

    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';
}
