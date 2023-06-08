<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

    public function index (){
       return response()->json([
         'message'=>'get all categories here'
       ]);
    }


    
    //================== create a new categorie===== access only admin=============//
    public function create(){
        return response()->json([
          'message'=>'categorie created'
        ]);
    }
}
