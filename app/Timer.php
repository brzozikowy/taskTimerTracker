<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timer extends Model
{
    protected $table = 'timer';
    protected $primaryKey = 'id';

    public static function canChangeStatus($start, $end){
        return Timer::where('start', '>', $date)->get();
    }

    public static function dailyTimer(){
        $iterator = 0;
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime($start. ' + 1 days'));
        $timer =  Timer::where('created_at', '>', $start)
        ->where('created_at', '<', $end)
        ->get();

        foreach($timer as $time){
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
    public static function getAllTimer(){
        $iterator = 0;
        $timer =  Timer::get();
        foreach($timer as $time){
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
