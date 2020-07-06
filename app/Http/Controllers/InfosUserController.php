<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class InfosUserController extends Controller
{
    public function addInfos(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'age' => 'required|integer',
            'sexe' => 'required|string',
            'adresse' => 'required|string',
            'telephone' => 'required',
            'ville' => 'required|string',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }  

        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $age = $request->input('age');
        $sexe = $request->input('sexe');
        $adresse = $request->input('adresse');
        $telephone = $request->input('telephone');
        $ville = $request->input('ville');
        $user_id = $request->input('user_id');

        $data = array("nom"=>$nom, "prenom"=>$prenom, "age"=>$age, "sexe"=>$sexe, "adresse"=>$adresse, "telephone"=>$telephone, "ville"=>$ville, "user_id"=>$user_id);
        
        DB::table('infosuser')->insert($data);

        $yes = "inserted :)";
        return response()->json(compact('yes'), 201);
    }

    public function showInfos(Request $request)
    {
        $id = $request->json()->all();
        $reponses = DB::table('infosuser')->where('user_id','=',$id)->get();  
        return $reponses;
    }
}
