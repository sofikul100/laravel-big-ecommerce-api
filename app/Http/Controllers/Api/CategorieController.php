<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

  public function index()
  {
    $categories = Categorie::all();
    return response()->json([
      'categories' => $categories
    ]);
  }



  //================== create a new categorie===== access only admin=============//
  public function create(Request $request)
  {
    $request->validate([
      'categorie_name' => 'required|unique:categories',
      'categorie_icon' => 'required|image|mimes:jpeg,jpg,png,svg',
      'categorie_description' => 'required|max:150'
    ]);


    $categorie = new Categorie();

    $imageName = time() . "." . $request->categorie_icon->extension();

    $request->categorie_icon->move(public_path('categories_images'), $imageName);

    $categorie->categorie_name = $request->categorie_name;
    $categorie->categorie_icon = $imageName;
    $categorie->categorie_description = $request->categorie_description;

    $categorie->save();

    return response()->json([
      'success' => true,
      'message' => 'Successfully categorie Stored'
    ], 200);
  }



  public function getSingleCategorie($id)
  {
    $categorie = Categorie::find($id);

    if (!$categorie) {
      return response()->json(['success' => false, 'message' => 'Categorie not found']);
    }

    return response()->json([
      'success' => true,
      $categorie
    ]);
  }



  //=============== update categorie========= access only admin===========//
  public function update(Request $request, $id)
  {
    $request->validate([
      'categorie_name' => 'required',
      'categorie_icon' => 'image|mimes:jpeg,jpg,png,svg',
      'categorie_description' => 'required|max:150'
    ]);

    $categorie = Categorie::find($id);
    if (!$categorie) {
      return response()->json(['success' => false, 'message' => 'Categorie not found']);
    }

    if ($request->categorie_icon) {
      if (file_exists(public_path('categories_images/' . $categorie->categorie_icon))) {
        unlink(public_path('categories_images/' . $categorie->categorie_icon));
      }

      $imageName = time() . "." . $request->categorie_icon->extension();

      $request->categorie_icon->move(public_path('categories_images/'), $imageName);

      $categorie->categorie_name = $request->categorie_name;
      $categorie->categorie_icon = $imageName;
      $categorie->categorie_description = $request->categorie_description;

      $categorie->save();
      return response()->json([
        'success' => true,
        'message' => 'Successfully categorie Updated'
      ], 200);
    } else {


      $categorie = Categorie::find($id);
      $oldIcon = $categorie->categorie_icon;
      $categorie->categorie_name = $request->categorie_name;
      $categorie->categorie_icon = $oldIcon;
      $categorie->categorie_description = $request->categorie_description;
      $categorie->save();
      return response()->json([
        'success' => true,
        'message' => 'Successfully categorie Updated'
      ], 200);
    }
  }








  //====================== delete single categorie=========== access only admin==========//
  public function delete($id)
  {
    $categorie = Categorie::find($id);

    if (!$categorie) {
      return response()->json(['success' => false, 'message' => 'Categorie not found']);
    }

    if (file_exists(public_path('categories_images/' . $categorie->categorie_icon))) {
      unlink(public_path('categories_images/' . $categorie->categorie_icon));
    }

    $categorie->delete();

    return response()->json([
      'success' => true,
      'message' => 'Successfully Categorie deleted'
    ], 200);
  }
}
