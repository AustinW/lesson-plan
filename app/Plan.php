<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'description', 'date', 'group'];

    public function sections() {
        return $this->hasMany('App\Section');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
