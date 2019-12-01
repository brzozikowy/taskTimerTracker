<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\Task;
use Response;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function createTemplate(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'desc' => 'required',
        ]);

        try{
            $template = new Template();
        
            $template->name = $request['name'];
            $template->description = $request['desc'];
            $template->save();

            return Response::json(true);
        }catch(\Exception $e){
            return response($e->getMessage(), 422);
        }
    }

    public function addTemplate(){
        DB::beginTransaction();
        try{
            $template = \App\Template::get();
            foreach($template as $t){
                $task = new Task;
                $task->name = $t->name;
                $task->description = $t->description;
                
                $task->save();
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response($e->getMessage(), 422);
        }
        DB::commit();
        
        return Response::json(true);
    }
}
