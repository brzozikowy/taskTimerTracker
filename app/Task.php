<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'id';
    const DURING_= 'DURING';
    const PAUSED_ = 'PAUSED';
    const WAITING_ = 'WAITING';
    const DONE_ = 'DONE';
    const enum_Array = [
        'DURING',
        'PAUSED',
        'WAITING',
        'DONE'
    ];


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
        $date = isset($request['date']) ? $request['date'] : date('Y-m-d');
        return Task::selectToFront()->searchByName($request)->with('comment.user')->whereDate('created_at', $date )->orderbyStatus()->get();
    }

    public function comment() {
        return $this->hasMany( 'App\Comment');
    }

    public function timer(){
        return $this->hasMany('App\Timer');
    }

    public function timerCanGo(){
        return $this->hasMany('\App\Timer');
    }

    public static function canDuringStatus(){
        return \App\Task::where('status', 'DURING')->count() == 0 ;
        
    }

    public static function checkTimer(){
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime($start. ' + 1 days'));
        return \App\Task::where('created_at', '>=', $start)->where('created_at', '<', $end)->where('status', Task::DURING_)->count() > 0 ;
    }

    public function calcTimer(){
        $iterator = 0;
       foreach( $this->timer as $time){
            $startAll = Carbon::parse($time->start);
            if(is_null($time->end)){
                $endAll = Carbon::now();
            }else{
                $endAll = Carbon::parse($time->end);
            }
            $iterator += $startAll->diffInSeconds($endAll);
       }
       return $iterator;
    }
    
}
