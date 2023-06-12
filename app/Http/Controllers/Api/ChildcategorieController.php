<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Childcategorie;
use Illuminate\Http\Request;

class ChildcategorieController extends Controller
{
    public function index(){
       $childCategories = Childcategorie::all(); 
       return response()->json([
          'child-categories'=>$childCategories
       ]);
    }


    //========== create new child categorie======== access only admin=========//
    public function create (Request $request){
        $request->validate([
            'categorie_id' => 'required',
            'childcategorie_name' => 'required|unique:childcategories',
            'childcategorie_icon' => 'required|image|mimes:jpeg,jpg,png,svg',
            'childcategorie_description'=>'required|max:150'
          ]);
      
      
          $childcategorie = new Childcategorie();
      
          $imageName = time() . "." . $request->childcategorie_icon->extension();
      
          $request->childcategorie_icon->move(public_path('childcategories_images'), $imageName);
          
          $childcategorie->categorie_id = $request->categorie_id;
          $childcategorie->childcategorie_name = $request->childcategorie_name;
          $childcategorie->childcategorie_icon = $imageName;
          $childcategorie->childcategorie_description = $request->childcategorie_description;
      
          $childcategorie->save();
      
          return response()->json([
            'success' => true,
            'message' => 'Successfully child-categorie Stored'
          ], 200);
    }




    public function getSingleChildcategorie ($id){
        $childcategorie = Childcategorie::find($id);
        

        if(!$childcategorie){
            return response()->json([
                'success' => false,
                'message' => 'ChildCategorie not found'
              ], 400);
        }


        return response()->json([
            $childcategorie
          ], 200);
    }

    //====== update child categories ========== acccess only admin=========//
    public function update(Request $request,$id){
        $request->validate([
            'categorie_id'=>'required',
            'childcategorie_name' => 'required',
            'childcategorie_icon' => 'image|mimes:jpeg,jpg,png,svg',
            'childcategorie_description' => 'required|max:150'
          ]);
      
          $childcategorie = Childcategorie::find($id);
          if (!$childcategorie) {
            return response()->json(['success' => false, 'message' => 'ChildCategorie not found']);
          }
      
          if ($request->childcategorie_icon) {
            if (file_exists(public_path('childcategories_images/' . $childcategorie->childcategorie_icon))) {
              unlink(public_path('childcategories_images/' . $childcategorie->childcategorie_icon));
            }
      
            $imageName = time() . "." . $request->childcategorie_icon->extension();
      
            $request->childcategorie_icon->move(public_path('childcategories_images/'), $imageName);

            $childcategorie->categorie_id = $request->categorie_id; 
            $childcategorie->childcategorie_name = $request->childcategorie_name;
            $childcategorie->childcategorie_icon = $imageName;
            $childcategorie->childcategorie_description = $request->childcategorie_description;
      
            $childcategorie->save();
            return response()->json([
              'success' => true,
              'message' => 'Successfully child-categorie Updated'
            ], 200);
          } else {
      
      
            $childcategorie = Childcategorie::find($id);
            $oldIcon = $childcategorie->childcategorie_icon;
           
            $childcategorie->categorie_id = $request->categorie_id; 
            $childcategorie->childcategorie_name = $request->childcategorie_name;
            $childcategorie->childcategorie_icon = $oldIcon;
            $childcategorie->childcategorie_description = $request->childcategorie_description;
            $childcategorie->save();
            return response()->json([
              'success' => true,
              'message' => 'Successfully child-categorie Updated'
            ], 200);
          }
    }


    //========== delete single childcategorie ======= access only admin==========//
    public function delete ($id){
        $childcategorie = Childcategorie::find($id);

        if (!$childcategorie) {
          return response()->json(['success' => false, 'message' => 'ChildCategorie not found']);
        }
    
        if (file_exists(public_path('childcategories_images/' . $childcategorie->childcategorie_icon))) {
          unlink(public_path('childcategories_images/' . $childcategorie->childcategorie_icon));
        }
    
        $childcategorie->delete();
    
        return response()->json([
          'success' => true,
          'message' => 'Successfully ChildCategorie deleted'
        ], 200);
    }






}
