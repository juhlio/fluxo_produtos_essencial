<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'guard_name'];
    // timestamps existem na migration do Spatie; se não tiver, pode desabilitar:
    // public $timestamps = false;
}
