<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FicheCov19;
use DB;

class GraphsController extends Controller
{
    public function setGraphs() 
    {

        // $nonTraite = FicheCov19::where('id_test', 1)->count();
        // $positif =  FicheCov19::where('id_test', 2)->count();
        // $negatif =  FicheCov19::where('id_test', 3)->count();

        $nonTraite = DB::select('select count(reponseTest_id) as nonTraite from questions where reponseTest_id = 1 ');
        $positif = DB::select('select count(reponseTest_id) as positif from questions where reponseTest_id = 2 ');
        $negatif = DB::select('select count(reponseTest_id) as negatif from questions where reponseTest_id = 3 ');

         return response()->json(compact('nonTraite', 'positif', 'negatif'));
    }
}
