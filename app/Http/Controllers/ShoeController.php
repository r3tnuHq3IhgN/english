<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoeRequest;
use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\ShoeImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class ShoeController extends Controller {
    public function create(ShoeRequest $request) {

        $validated = $request->validated();

        if (!$validated) {
            return response()->json($validated, 400);
        }
        DB::beginTransaction();
        try {
            $shoe = new Shoe([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'brand' => $request->brand,
                'size' => $request->size,
                'price' => $request->price,
                'description' => $request->description,
                'category' => $request->category,
            ]);
            $shoe->save();
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($shoe, 201);
    }

    public function createShoeImages(Request $request) {

        DB::beginTransaction();
        try {
            $uploadResult = Cloudinary::upload(
                $request->file('images')->getRealPath(),
                [
                    'folder' => 'shoes',
                ]
            );
            $image = new ShoeImage([
                'shoe_id' => $request->shoe_id,
                'image_url' => $uploadResult->getSecurePath(),
            ]);
            $image->save();
            DB::commit();
            return response()->json($image, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }

    public function getAllShoes(Request $request) {
        $shoes = Shoe::getAllShoesOfUser($request->uid);
        return response()->json($shoes, 200);
    }
    public function deleteShoe(Request $request) {
        $shoe = Shoe::findShoeOfUserWithId($request->uid, $request->shoe_id)->first();
        if (!$shoe) {
            return response()->json(['message' => 'Shoe not found'], 404);
        }
        $shoe->images()->delete();
        $shoe->delete();
        return response()->json(['message' => 'Shoe deleted'], 200);
    }

}
