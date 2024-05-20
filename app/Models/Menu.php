<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type', 'id_parent', 'order'];

    public function parent()
    {
        return $this->belongsTo($this, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany($this, 'id_parent');
    }
}
