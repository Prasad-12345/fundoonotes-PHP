<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\Request;
use App\Models\Notes;
use App\Models\Labels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotesAndLabelController extends Controller
{
            /**
     * @OA\Post(
     *   path="/api/addToNotes",
     *   summary="add to notes",
     *   description="add notes",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"Title","Description"},
     *               @OA\Property(property="Title", type="string"),
     *               @OA\Property(property="Description", type="text"),
     *   
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToNotes(Request $request){
        $request->validate([
            'Title' => 'required',
            'Description' => 'required'
        ]);

        $notes = new Notes();
        $notes->Title = $request->input('Title');
        $notes->Description = $request->input('Description');
        $notes->save();
        return response()->json(['data'=>$notes, 'success'=>200]);
    }

                /**
     * @OA\Post(
     *   path="/api/addToLabel",
     *   summary="add to label",
     *   description="add label",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"label","title_id"},
     *               @OA\Property(property="label", type="string"),
     *               @OA\Property(property="title_id", type="string"),
     *   
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToLabel(Request $request){
        $request->validate([
            'label' => 'required',
            'title_id' => 'required'
        ]);

        $label = new Label();
        $label->label = $request->input('label');
        $label->title_id = $request->input('title_id');
        $label->save();
        return response()->json(['data'=>$label, 'success'=>200]);
    }

     /**
     * @OA\get(
     *   path="/api/joinTable",
     *   summary="join table",
     *   description="join table",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinTable(){
        $data = DB::table('Notes')->join('labels', 'Notes.id', '=', 'labels.title_id')
                        ->select('Notes.*', 'labels.label')->get();
        if(!$data){
            Log::channel('custom')->error("Table does not exists");
        }
        return $data;
    }

    /**
     * @OA\Post(
     *   path="/api/delete",
     *   summary="delete",
     *   description="delete",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"userId"},
     *               @OA\Property(property="userId", type="string")
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        $request->validate([
            'userId' => 'required'
        ]);
        $data = DB::table('Notes')->join('labels', 'Notes.id', '=', 'labels.title_id')
                        ->where('Notes.id', $request->userId)->delete();

        DB::table('labels')->where('title_id', $request->userId)->delete();

        if(!$data){
            Log::channel('custom')->error("You entered invalid id");
        }
        return $data;
    }

    /**
     * @OA\Post(
     *   path="/api/updateNotes",
     *   summary="updateNotes",
     *   description="updateNotes",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"userId","Title", "Description"},
     *               @OA\Property(property="userId", type="string"),
     *               @OA\Property(property="Title", type="string"),
     *               @OA\Property(property="Description", type="text"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="User successfully registered"),
     *   @OA\Response(response=401, description="The email has already been taken"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotes(Request $request,){
        $request->validate([
            'userId' => 'required',
            'Title' => 'required',
            'Description' => 'required'
        ]);

        $data = DB::table('Notes')->where('id', $request->userId)->update(['Title'=>$request->Title, 'Description'=>$request->Description]);
        if(!$data){
            Log::channel('custom')->error("You entered invalid id");
        }
        return response()->json(['data'=>$data, 'success'=>200]);
    }

    /**
     * @OA\Post(
     *   path="/api/updateLabel",
     *   summary="updateLabel",
     *   description="updateLabel",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"userId","label", "title_id"},
     *               @OA\Property(property="userId", type="string"),
     *               @OA\Property(property="label", type="string"),
     *               @OA\Property(property="title_id", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="User successfully registered"),
     *   @OA\Response(response=401, description="The email has already been taken"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLabel(Request $request){
        $request->validate([
            'userId' => 'required',
            'label' => 'required',
            'title_id' => 'required'
        ]);

        $data = DB::table('labels')->where('id', $request->userId)->update(['label'=>$request->label, 'title_id'=>$request->title_id]);
        if(!$data){
            Log::channel('custom')->error("You entered invalid id");
        }
        return response()->json(['data'=>$data, 'success'=>200]);
    }
}
