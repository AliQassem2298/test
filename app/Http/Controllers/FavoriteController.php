<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    //////////////////////////////////////// add product to favorite /////////////////////////////////

    public function add_to_Favorite($product_id)
    {
        try {
            $user = User::find(auth()->user()->id);
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized user',
                ]);
            }
            $product = Product::find($product_id);
            if (!$product) {
                return response()->json(['message' => 'product not found', 'status' => 404]);
            }

            $favorite = DB::table('favorites')->where('user_id', $user->id)->where('product_id', $product->id)->first();
            if ($favorite) {
                return response()->json(['message' => 'product already in favorite']);
            }

            DB::table('favorites')->insert([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            return response()->json(['message' => 'product add to your favorite', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }


    ////////////////////////////////// show favorite for user ////////////////////////////////////////////////////

    public function showFavorites()
    {
        try {

            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401]);
            }


            $user = auth()->user();

            $favorite = DB::table('favorites')->where('user_id', $user->id)->get();
            if ($favorite->isEmpty()) {
                return response()->json(['message' => 'User has no favorite products', 'status' => 200]);
            }
            $user_favorite = $user->products->with('image')->get();
        
            return response()->json(['data' => $user_favorite, 'message' => 'User favorites retrieved successfully', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }


    ////////////////////////////////////////// remove product from favorite /////////////////////////////////////////////

    public function remove_from_Favorite($product_id)
    {
        try {

            if (!auth()->check()) {
                return response()->json(['message' => 'Unauthorized', 'status' => 401]);
            }


            $user = auth()->user();

            $Favorite = DB::table('favorites')->where('user_id', $user->id)->where('product_id', $product_id)->first();
            if (!$Favorite) {
                return response()->json(['message' => 'product not found in favorite', 'status' => 404]);
            }


            DB::table('favorites')->where('user_id', $user->id)->where('product_id', $product_id)->delete();

            return response()->json(['message' => 'product removed from favorite', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 400]);
        }
    }
}
