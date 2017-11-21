<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Shopex\LubanAdmin\Finder;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Session;
use App\Models\Entity\Goods;
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
        $roles = [];
        return view('admin::users.create', compact('roles'));
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

        foreach ($request->roles as $role) {
            $role['datas'] = json_encode( [
                App\Models\Entity\Goods::class=>[
                    'shop_id'=>[1,2],
                ]
            ]);
            $user->assignRole($role);
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
        $datas[2] = json_encode( [
                Goods::class=>[
                    'shop_id'=>[1,2],
                ]
            ]);
        $roles = explode(',', $request->roles);
        $datass = '';
        foreach ($roles  as $role_id) {
            if (isset($datas[$role_id])) {
                $datass = $datas[$role_id];
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
