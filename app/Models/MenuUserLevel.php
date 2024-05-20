<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuUserLevel extends Model
{
    use HasFactory;

    protected $fillable = ['id_menu', 'id_user_level'];
}
