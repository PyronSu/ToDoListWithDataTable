<?php

namespace App\Http\Controllers;

use datatables;
use App\Models\ajaxAgain;
use Illuminate\Http\Request;

class AjaxAgainController extends Controller
{
    public function show(){
        if(request()->ajax()){
            return datatables()->of(ajaxAgain::select('*'))
            ->addColumn('action','take-actions')
            ->rawColumns(['action'])
            ->addIndexColumn()->make(true);
        }
        return view('again');
    }

    public function storeArea(Request $req){
        $areaID = $req->id;
        $area = ajaxAgain::updateOrCreate(
            ['id'=>$areaID],
            ['title'=>$req->category]
        );
        return Response()->json($area);
    }

    public function editCategory(Request $req){
        $where = array('id'=>$req->id);
       // $newArea = ajaxAgain::whre($where)->update(array('title'=>$req->category));
        $area = ajaxAgain::where($where)->first();
        return Response()->json($area);
    }
}
