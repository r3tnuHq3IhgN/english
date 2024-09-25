<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

class WordController extends Controller
{
    //
    public function createWord(Request $request)
    {
        $request->validate(
            [
                'eng' => 'required',
                'type' => 'required',
                'vi' => 'required',
            ],
            [
                'eng.required' => 'Trường english không được để  trống !',
                'type.required' => 'Trường type không được để  trống !',
                'vi.required' => 'Trường vietnamese không được để  trống !',

            ]
        );
        $temp = DB::table('word')->where('user_id', $request->uid)->where('eng', $request->eng)->where('type', $request->type)->first();
        if ($temp != '') {
            return 'had';
        } else {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('word')->insert([
                'user_id' => $request->uid,
                'eng' => $request->eng,
                'type' => $request->type,
                'vi' => $request->vi,
                'example' => $request->ex,
                'date_created' => now(),
            ]);
            return 'success';
        }
    }
    public function getDates(Request $request)
    {
        $temp = DB::table('word')->where('user_id', $request->uid)->distinct('date_created')->get('date_created');
        return response()->json($temp);
    }
    public function getWWDate(Request $request)
    {
        if ($request->type == 'sort2') {
            $temp = DB::table('word')->where('user_id', $request->uid)->where('date_created', $request->date)->orderBy('eng', 'asc')->get();
            return response()->json($temp);
        } else {
            $temp = DB::table('word')->where('user_id', $request->uid)->where('date_created', $request->date)->get();
            return response()->json($temp);
        }
    }
    public function getAllWords(Request $request)
    {
        if ($request->type == 'sort2') {
            $temp = DB::table('word')->where('user_id', $request->uid)->orderBy('eng', 'asc')->get();
            return response()->json($temp);
        } else {
            $temp = DB::table('word')->where('user_id', $request->uid)->get();
            return response()->json($temp);
        }
    }
    public function searchWord(Request $request)
    {
        $temp = DB::table('word')->where('user_id', $request->uid)->where('eng', $request->word)->get();
        if ($temp != null) {
            return response()->json($temp);
        } else {
            return 'no';
        }
    }
    public function deleteWord(Request $request)
    {
        DB::table('word')->where('user_id', $request->uid)->where('id', $request->id)->delete();
        return 'success';
    }
    public function getRandWord(Request $request)
    {
        $temp = DB::table('word')->where('user_id', $request->uid)->where('date_created', $request->date)->inRandomOrder()->get();
        $count = DB::table('word')->where('user_id', $request->uid)->where('date_created', $request->date)->count();
        return response()->json(['words' => $temp, 'count' => $count]);
    }
    public function getWordsRandom(Request $request){
        $temp = DB::table('word')->where('user_id', $request->uid)->inRandomOrder()->take($request->num)->get();
        return response()->json(['words' => $temp]);
    }
    public function editWord(Request $request)
    {
        $request->validate(
            [
                'eng' => 'required',
                'type' => 'required',
                'vi' => 'required',
            ],
            [
                'eng.required' => 'Trường english không được để  trống !',
                'type.required' => 'Trường type không được để  trống !',
                'vi.required' => 'Trường vietnamese không được để  trống !',

            ]
        );
        DB::table('word')->where('id', $request->id)->update([
            'eng' => $request->eng,
            'type' => $request->type,
            'vi' => $request->vi,
            'example' => $request->example,
        ]);
        return 'success';
    }
}
