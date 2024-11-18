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
            $shoe = new Shoe($request->all());
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
}
