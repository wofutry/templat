<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuUserLevel;
use App\Models\UserLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $parents = Menu::select('id', 'name')->where('type', 'parent')->orderBy('menus.name')->get();
        $userLevels = UserLevel::select('id', 'name')->orderBy('user_levels.name')->get();
        return view('pages.menu.index')->with(['parents' => $parents, 'userLevels' => $userLevels]);
    }

    public function data(Request $request)
    {
        $where = [];
        $search = $request->search['value'];
        //search param
        $where[] = ['menus.name', 'LIKE', '%' . $search . '%'];
        if ($request->type) {
            $where[] = ['menus.type', '=', $request->type];
            if ($request->type == 'child' && $request->id_parent) {
                $where[] = ['menus.id_parent', '=', $request->id_parent];
            }
        }
        //get request page
        $request['page'] = $request->start == 0 ? 1 : round(($request->start + $request->length) / $request->length);
        //get data 
        $datas = Menu::select('menus.id', 'menus.name', 'menus.slug', 'menus.type', 'menus.id_parent', 'menus.order')
            ->with('parent')->where($where)
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
            'name' => 'required|unique:menus',
            'slug' => 'required|string|max:100',
            'type' => 'required|in:parent,child',
            'order' => 'required|numeric',
        ]);

        $validator->sometimes('id_parent', 'required|integer|min:0', function ($input) {
            return $input->type == 'child';
        });


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()));
        }
        // Retrieve the validated input...
        $validated = $validator->validated();
        try {
            DB::beginTransaction();
            $menu = Menu::create($validated);
            DB::commit();
            return ResponseFormatter::success('Success save data', $menu);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:menus,id',
            'name' => 'required|unique:menus,name,' . $request->id,
            'slug' => 'required|string|max:100',
            'type' => 'required|in:parent,child',
            'order' => 'required|numeric',
        ]);

        $validator->sometimes('id_parent', 'required|integer|min:0', function ($input) {
            return $input->type == 'child';
        });


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        // Retrieve the validated input...
        $validated = $validator->safe()->except(['id']);
        $validated['type'] == 'parent' && $validated['id_parent'] = null;
        try {
            DB::beginTransaction();
            $menu = Menu::where('id', $validator->safe()->only(['id']))->update($validated);
            if ($menu) {
                DB::commit();
                return ResponseFormatter::success('Success save data', $menu);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:menus,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        try {
            DB::beginTransaction();
            $menu = Menu::where('id', $validator->safe()->only(['id']))->delete();
            if ($menu) {
                DB::commit();
                return ResponseFormatter::success('Success delete data', $menu);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage());
        }
    }

    public function dataUser(Request $request)
    {
        $where = [];
        //search param
        $search = $request->search['value'];
        $where[] = ['user_levels.name', 'LIKE', '%' . $search . '%'];
        //get request page
        $request['page'] = $request->start == 0 ? 1 : round(($request->start + $request->length) / $request->length);

        $MenuUserLevels = MenuUserLevel::select('menu_user_levels.id_menu', 'menu_user_levels.id_user_level', 'user_levels.name')
            ->join('user_levels', 'user_levels.id', '=', 'menu_user_levels.id_user_level');
        if ($request->id_menu) {
            $where[] = ['menu_user_levels.id_menu', '=', $request->id_menu];
        }
        //get data 
        $datas = $MenuUserLevels->where($where)->paginate($request->length ?? 10)->toArray();
        $final['draw'] = $request['draw'];
        $final['recordsTotal'] = $datas['total'];
        $final['recordsFiltered'] = $datas['total'];
        $final['data'] = $datas['data'];
        return response()->json($final, 200);
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_menu' => 'required|numeric|exists:menus,id',
            'id_user_level' => 'required|numeric|exists:user_levels,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()));
        }
        // Retrieve the validated input...
        $validated = $validator->validated();

        try {
            DB::beginTransaction();
            if (MenuUserLevel::where($validated)->count() == 0) {
                $menu = MenuUserLevel::create($validated);
            } else {
                throw new Exception("Data already exists");
            }
            DB::commit();
            return ResponseFormatter::success('Success save data', $menu);
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function destroyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_menu' => 'required|numeric|exists:menus,id',
            'id_user_level' => 'required|numeric|exists:user_levels,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        // Retrieve the validated input...
        $validated = $validator->validated();
        try {
            DB::beginTransaction();
            $menu_user_level = MenuUserLevel::where($validated)->delete();
            if ($menu_user_level) {
                DB::commit();
                return ResponseFormatter::success('Success delete data', $menu_user_level);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage());
        }
    }
}
