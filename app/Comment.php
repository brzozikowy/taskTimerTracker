<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comment';
    protected $primaryKey = 'id';

    public function task() {
        return $this->hasOne( 'App\Task', 'id', 'task_id');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
} 
