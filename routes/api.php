<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ----------- Authentification --------------
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login'); // Login web
Route::post('loginMobile', 'UserController@loginMobile'); // Login mobile
Route::get('logout', 'UserController@logout');
// Route::get('profile', 'UserController@getAuthenticatedUser');//pas utilisé

// ----------- (Patient) Remplissage de la fiche ------------ 
Route::post('addFiche', 'FicheController@addFiche');
Route::post('addInfos', 'InfosUserController@addInfos');
Route::post('userReponse', 'FicheController@userReponse');

// ------------- (Médecin) Vérification ---------------
Route::post('showInfos', 'InfosUserController@showInfos'); //Affichage des informations d'un utilisateur
// Route::get('showReponsesUser', 'FicheController@showReponsesUser'); //pas utilisé
Route::get('showTraite', 'FicheController@showTraite'); //Affichage des fiches traités
Route::get('showReponses', 'FicheController@showReponses'); //Affichage des Reponses des fiches non traités
Route::post('repondre', 'FicheController@repondre'); //Pour passer la réponse (Positif ou Négatif)
Route::get('graphes', 'GraphsController@setGraphs'); // Récuperation des données pour dessiner les graphes

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['jwt.verify']], function() {
//     Route::get('user', 'UserController@getAuthenticatedUser');
// });
