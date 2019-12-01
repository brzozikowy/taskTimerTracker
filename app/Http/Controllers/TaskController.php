<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;
use \App\Timer;
use Response;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function getAllTasks(Request $request){
        // return ($request->all());
        $dailyTimer = "0:01";
        $allTimer = "0:23";
        $currentTimer = "0:01";
        $daily = Timer::dailyTimer();
        $tasks = Task::getAll($request->all());
        $all=Timer::getAllTimer();
        $i = 0;
        $currentTime = "";
        $calc = false;
        foreach($tasks as $task){
            $temp = $task->calcTimer();
            $tasks[$i]['time'] = $temp;
            if($task->status == Task::DURING_){
                $currentTime = $temp;
                $calc = true;
            }
            $i ++ ;
        }

        $result = [
            'tasks' => $tasks, 
            'dailyTimer' => $daily,
            'allTimer' => $all,
            'calc' =>  $calc,
            //'calc' =>  Task::checkTimer(),
            'currentTime' => $currentTime
        ];

        return $result;
    }

    public function create(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'desc' => 'required',
        ]);
        
        $task = new Task();
        if(!is_null($request->user_id)){
            $task->user_id = (int)$request['user_id'];
        }
        $task->name = $request['name'];
        $task->description = $request['desc'];

        $task->save();
        return Response::json(true);
    }

    
    public function edit(Request $request, $id){
        $validatedData = $request->validate([
            'name' => 'required',
            'desc' => 'required',
        ]);
        
        $task = Task::findOrFail($id);
       
        $task->name = $request['name'];
        $task->description = $request['desc'];

        $task->save();
        return Response::json(true);
    }

    public function delete($id){
        
        try{
            $task = Task::findOrFail($id);
            $task->delete();

        }catch(\Exception $e){
            return response($e->getMessage(), 422);
        }
    }

    public function changeStatus(Request $request, $id){
        try{
            $task = Task::findOrFail($id);
            $userId = null;
            if(isset($request->auth) && !is_null($request->auth)){
                $userId = (int)$request->auth;
            }
            if($request->status == TASK::DURING_){
                $count  = Task::canDuringStatus($request->date);
                if(!$count){
                    return response("Finish the active task", 422);
                }
            }
            $task->status = $request->status;
            $task->save();
            
            if($task->status == TASK::DONE_){
                $timer = Timer::where('task_id', $task->id)->whereNull('end')->first();
                $timer->end = date('Y-m-d H:i:s');
                $timer->completed = '1';
                $timer->task_id = $task->id;
                $timer->save();
            }elseif($request->status == TASK::DURING_){
                $timer = new Timer;
                $timer->start = date('Y-m-d H:i:s');
                $timer->task_id = $task->id;
                $timer->save();
            }elseif($request->status == TASK::PAUSED_){
                $timer = Timer::where('task_id', $task->id)->whereNull('end')->first();
                $timer->end = date('Y-m-d H:i:s');
                $timer->task_id = $task->id;
                $timer->completed = '0';
                $timer->save();
            }

        }catch(\Exception $e){
            return $e;
            return response($e->getMessage(), 422);
        }
        return response("OK");

    }
}
