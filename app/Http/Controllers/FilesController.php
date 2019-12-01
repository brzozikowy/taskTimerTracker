<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class FilesController extends Controller
{
    public function download(){
        $date = 'tasks.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$date.'"');
        $tasks = Task::get();
     
        $fp = fopen('php://output', 'wb');
        fputcsv($fp, ["name", "description"]);
        foreach ( $tasks as $line ) {
            $val = explode(",", $line->name. ', '. $line->description);
            fputcsv($fp, $val);
            $timer  = $line->timer;
            if(count($timer) > 0){
                
                 $val = explode(",", 'Start of task, End of task');
                fputcsv($fp, $val);
                foreach($timer as $t){
                    $val = explode(",", $t->start . ', ' . $t->end);
                    fputcsv($fp, $val);
                }
            }
        }
        fclose($fp);
    }
}
