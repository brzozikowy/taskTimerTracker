<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';


    public function scopeOrderbyStatus($query){
        $query->orderBy('status');
    }

    public function scopeSelectToFront($query){
        $query->select('id', 'status', 'name', 'description', 'created_at');
    }

    public function scopeSearchByName($query, $request){
        if(isset($request['search']) && $request['search'] !=''){
                return $query->where('name', 'like', '%'. $request['search'] .'%');
        }
    }

    public static function getAll($request){
        return Task::selectToFront()->searchByName($request)->orderbyStatus()->get();
    }


    
}
