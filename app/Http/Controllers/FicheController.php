<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class FicheController extends Controller
{
    public function addFiche(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'test' => 'required|string',
            'isolement' => 'required|string',
            'situation' => 'required',
            'symptome' => 'required',
            'tempsTousse' => 'required|integer',
            'user_id' => 'required'
            // 'reponseTest_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }  

        $test = $request->input('test');
        $isolement = $request->input('isolement');
        $situation = $request->input('situation');
        $symptome = $request->input('symptome');
        $tempsTousse = $request->input('tempsTousse');
        $user_id = $request->input('user_id');
        $reponseTest_id = $request->input('reponseTest_id');

        $data = array("test"=>$test, "isolement"=>$isolement, "situation"=>$situation, "symptome"=>$symptome, "tempsTousse"=>$tempsTousse, "user_id"=>$user_id, "reponseTest_id"=>$reponseTest_id);
          
        DB::table('questions')->insert($data);

        // $testData = array("fiche_id"=>$data->$id, "test_id"=>1); 
        // DB::table('testcovidfiche')->insert($testData);

        // $reponses = DB::table('questions')->where('reponseTest_id','=',1)->get();

        $yes = "inserted :)";
        return response()->json(compact('yes'), 201);
    }

    // public function showReponsesUser(Request $request)
    // {
    //     $id = $request->json()->all();
    //     // $reponses = DB::select('select * from questions where user_id = ?', $id);
    //     $reponses = DB::table('questions')->where('user_id','=',$id)->where('reponseTest_id','=',1)->get(); 
    //     // foreach($reponses as $rp)
    //     // {
    //     //     return $rp;
    //     // }
    //     return $reponses;
    // }

    public function showReponses()
    {
        // $ids = DB::select('select fiche_id from testcovidfiche where test_id = ?', [1]);
        // $in = collect()
        // $reponses = DB::select('select * from questions where id in ( ? )', json_code($ids));//, implode("','" ,$ids)); 
        // $in = implode(",", $ids);
        // foreach($ids as $id)
        // {
        //     $reponses = DB::select('select * from questions where id = ?', [$id]); 
        // }
        $reponses = DB::select('select * from questions where reponseTest_id = ?', [1]);
        return $reponses;
    }

    public function showTraite()
    {
        $reponses = DB::select('select * from questions where reponseTest_id != ?', [1]);
        return $reponses;
    }

    public function repondre(Request $request)
    {
        // $id = $request->json()->all();
        $idFiche = $request->input('id');
        $idTest = $request->input('reponseTest_id');
        DB::update('update questions set reponseTest_id = ? where id = ?', [$idTest, $idFiche]);

        $yes = "updated :)";
        return response()->json(compact('yes'), 201);
    }

    public function userReponse(Request $request)
    {
        // $id = $request->json()->all();
        $id = $request->input('user_id');

        $count = DB::select('select (select count(reponseTest_id) from questions where user_id = ?) as nbre', [$id]);
        $result = DB::select('select reponseTest_id from questions where user_id = ?', [$id]);

        return response()->json(compact('count','result'), 201);
    }
}
