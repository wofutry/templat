<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // $parents = Menu::select('id', 'name')->where('type', 'parent')->orderBy('menus.name')->get();
        $userLevels = UserLevel::select('id', 'name')->orderBy('user_levels.name')->get();
        $with = [
            'userLevels' => $userLevels,
        ];
        return view('pages.user.index')->with($with);
    }

    public function data(Request $request)
    {
        $where = [];
        $search = $request->search['value'];
        //search param
        $where[] = ['users.name', 'LIKE', '%' . $search . '%'];
        //get request page
        $request['page'] = $request->start == 0 ? 1 : round(($request->start + $request->length) / $request->length);
        //get data 
        $datas = User::select('users.id', 'users.name', 'users.email', 'users.id_user_level', 'users.status')
            ->with('user_level')->where($where)
            ->paginate($request->length ?? 10)->toArray();
        $final['draw'] = $request['draw'];
        $final['recordsTotal'] = $datas['total'];
        $final['recordsFiltered'] = $datas['total'];
        $final['data'] = $datas['data'];
        return response()->json($final, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
            'email' => 'required|unique:users',
            'id_user_level' => 'required|string|exists:user_levels,id',
            'status' => 'required|in:active,non_active',
            'password' => 'required|min:8',
        ]);


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()));
        }
        // Retrieve the validated input...
        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = date('Y-m-d H:i:s');
        try {
            DB::beginTransaction();
            $user = User::create($validated);
            DB::commit();
            return ResponseFormatter::success('Success save data', $user);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'name' => 'required|max:150',
            'email' => 'required|unique:users,email,' . $request->id,
            'id_user_level' => 'required|string|exists:user_levels,id',
            'status' => 'required|in:active,non_active',
            'password' => 'sometimes|min:8',
        ]);


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        // Retrieve the validated input...
        $validated = $validator->safe()->except(['id']);
        if (array_key_exists('password', $validated)) {
            $validated['password'] = Hash::make($validated['password']);
        }
        try {
            DB::beginTransaction();
            $user = User::where('id', $validator->safe()->only(['id']))->update($validated);
            if ($user) {
                DB::commit();
                return ResponseFormatter::success('Success save data', $user);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:users,id',
            'password' => 'sometimes|min:8',
        ]);


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        // Retrieve the validated input...
        $validated = $validator->safe()->except(['id']);
        try {
            DB::beginTransaction();
            $user = User::where('id', $validator->safe()->only(['id']))->update($validated);
            if ($user) {
                DB::commit();
                return ResponseFormatter::success('Success save data', $user);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }
}
