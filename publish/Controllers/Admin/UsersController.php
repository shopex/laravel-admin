<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Shopex\LubanAdmin\Finder;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Session;
use App\Models\Entity\Goods;
use Shopex\LubanAdmin\Facades\Admin;
use Shopex\LubanAdmin\Permission\Configs;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $dataSet = User::class;

        $finder = Finder::create($dataSet, '用户列表')
                    ->setId('id')
                    ->addAction('新建用户', [$this, 'create'])
                    ->addSort('按修改时间倒排', 'created_at', 'desc')
                    ->addSort('按修改时间正排', 'created_at')
                    ->addBatchAction('删除', [$this, 'destroy'])
                    ->addColumn('操作', 'id')->modifier(function($id){
                        return '<a href="'.url("/admin/users/$id/edit").' " title="编辑"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>编辑</button></a>';
                    })->html(true)
                    ->addColumn('名称', 'name')
                    ->addColumn('邮箱', 'email')
                    
                    ->addSearch('名称', 'name', 'string')
                    ->addSearch('邮箱', 'email', 'string')
                    ->addTab("全部", [])
                    ->addInfoPanel('基本信息', [$this, 'detail']);

        return $finder->view();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $roles = 0;
        return view('admin::users.create', compact('roles'));
    }
    /**
     * 根据用户ID和角色ID获取角色对应的需要model的权限
     *
     * @return void
     */
    public function getUserRolesModel(Request $request)
    {
        $this->validate($request, [
            //'user_id' => 'required', 
            'roles' => 'array'
            ]
        );
        $config = new Configs();
        $dataPermission = $config->getPermission('data');
        $nowHasData = [];
        if ($request->user_id > 0) {
            $user = User::findOrFail($request->user_id);
            foreach ($user->roles as $hasrole) {
                $datas = json_decode($hasrole->pivot->datas,1);
                if ($datas) {
                    $nowHasData[$hasrole->pivot->role_id] = $datas;
                }
            }
        }
        $roles_id = array_pluck($request->roles,'value');
        $roles = Role::findOrFail($roles_id);
        $roleData = [];
        foreach ($roles as $role) {
            $data = $role->datas();
            $roleData[$role->id]['name'] = $role->name;
            $roleData[$role->id]['role_id'] = $role->id;
            foreach ($data as $model_name => $row) {
                if (array_has($dataPermission,$model_name)) {
                    $permissiondata = array_get($dataPermission,$model_name);
                    $permission = $permissiondata['permissions'];
                    foreach ($row['row'] as $key) {
                        if (array_has($permission,$key) && $permission[$key]['type'] == 'model') {
                            $typeobject = Admin::getObjectInputName($permission[$key]['model']);
                            $value = [];
                            if ($typeobject) {
                                if (isset($nowHasData[$role->id][$model_name][$permission[$key]['field']])) {
                                    $value  = $nowHasData[$role->id][$model_name][$permission[$key]['field']];
                                }

                                $roleData[$role->id]['model'][$model_name][] = [
                                    'field'=> $permission[$key]['field'],
                                    'name'=> $permission[$key]['name'],
                                    'object_name' => $typeobject,
                                    'value'=>$value,
                                ];
                            }
                            
                        }
                    }
                }
            }
        }
        
        return response()->json(['status'=>'succ','data'=>$roleData]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required', 'password' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        $datas = $request->role_datas;
        $roles = explode(',', $request->roles);
        foreach ($roles  as $role_id) {
            $datass = [];
            if (isset($datas[$role_id])) {
                $datass = json_encode($datas[$role_id]);
            }
            $user->assignRole($role_id,$datass);
        }

        Session::flash('flash_message', 'User added!');

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $user = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);
        $roles = $user->roles->pluck('id')->implode(','); 
        $roles = $roles? $roles:0;       
        return view('admin::users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required', 
            'email' => 'required', 
            'roles' => 'required']
        );
        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);
        $user->roles()->detach();
        
        $datas = $request->role_datas;
        $roles = explode(',', $request->roles);
        foreach ($roles  as $role_id) {
            $datass = [];
            if (isset($datas[$role_id])) {
                $datass = json_encode($datas[$role_id]);
            }
            $user->assignRole($role_id,$datass);
        }
        Session::flash('flash_message', 'User updated!');

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        User::destroy($id);

        Session::flash('flash_message', 'User deleted!');

        return redirect('admin/users');
    }
}
