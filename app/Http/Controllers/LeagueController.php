<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeagueController extends Controller
{
    //
    public function getLeague(){
        $data = DB::table('league')->get();
        return response()->json($data);
    }
}
