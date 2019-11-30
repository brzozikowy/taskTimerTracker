<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;

class TaskController extends Controller
{
    public function getAllTasks(Request $request){
        //dd($request->all());
        $tasks = Task::getAll($request->all());

        return $tasks;
    }


    public function create(Request $request){
        return $request->all();
    }
}
