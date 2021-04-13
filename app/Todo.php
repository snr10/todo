<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title','date','todo','user_id'];


    public function user(){
        return User::where('id',$this->user_id)->first();
    }
}
