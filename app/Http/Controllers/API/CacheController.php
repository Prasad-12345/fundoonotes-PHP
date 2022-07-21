<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CacheController extends Controller
{
    public function getUser(){
        $user = Cache::remember('users',10, function(){
            return User::all();
        });
        return $user;
    }

    public function getNotesAndLabel(){
        $notes = Cache::remember('notes',10, function(){
            return DB::table('Notes')->join('labels', 'Notes.id', '=', 'labels.title_id')
            ->select('Notes.*', 'labels.label')->get();;
        });
        return $notes;
    }

    // public function getNotes(){
    //     $notes = Cache::remember('users',10, function(){
    //         return Notes::all();
    //     });
    //     return $notes;
    // }
}
