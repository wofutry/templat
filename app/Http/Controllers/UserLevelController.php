<?php

namespace App\Http\Controllers;

use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserLevelController extends Controller
{
    public function index()
    {
        return view('pages.user-level.index');
    }

    public function data(Request $request){
        $search = $request->search['value'];
        //search param
        $searchParam = [
            ['name','LIKE','%'.$search.'%']
        ];
        $request['page'] = $request->start == 0 ? 1 : round(($request->start + $request->length) / $request->length);
        $datas = UserLevel::where($searchParam)->paginate($request->length??10)->toArray();
        $final['draw'] = $request['draw'];
        $final['recordsTotal'] = $datas['total'];
        $final['recordsFiltered'] = $datas['total'];
        $final['data'] = $datas['data'];
        return response()->json($final, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'required|unique:user_levels,name,',
        ]
        );
        if($validator->fails()){
            return ResponseFormatter::error(500, implode('<br>',$validator->errors()->all()));
        }
        try{
            DB::beginTransaction();
            //add
            $userLevel = UserLevel::create($request->only('name'));
            if($userLevel){
                DB::commit();
                return ResponseFormatter::success('Save data success', $userLevel);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        }catch(\Exception $e){
            DB::rollBack();
            return ResponseFormatter::error(500, $e->getMessage());
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),
        [
            'id' => 'required|exists:user_levels,id',
            'name' => 'required|unique:user_levels,name,'.$request->id,
        ]
        );
        if($validator->fails()){
            return ResponseFormatter::error(500, implode('<br>',$validator->errors()->all()));
        }

        try{
            DB::beginTransaction();
            //update
            $userLevel = UserLevel::find($request->id);
            if($userLevel){
                $userLevel->name = $request->name;
                $userLevel->save();
                DB::commit();
                return ResponseFormatter::success('Save data success', $userLevel);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        }catch(\Exception $e){
            DB::rollBack();
            return ResponseFormatter::error(500, $e->getMessage());
        }
    }
}
