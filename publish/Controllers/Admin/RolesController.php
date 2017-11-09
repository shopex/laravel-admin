<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Session;
use Shopex\LubanAdmin\Finder;
use Shopex\LubanAdmin\Permission\Configs;
class RolesController extends Controller
{
    public function __construct()
    {
        $config = new Configs();
        $data = $config->getRouterPermission();
        view()->share("data",$data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $dataSet = Role::class;

        $finder = Finder::create($dataSet, '角色列表')
                    ->setId('id')
                    ->addAction('新建角色', [$this, 'create'])
                    ->addSort('按修改时间倒排', 'created_at', 'desc')
                    ->addSort('按修改时间正排', 'created_at')
                    ->addBatchAction('删除', [$this, 'destroy'])
                    ->addColumn('操作', 'id')->modifier(function($id){
                        return '<a href="'.url("/admin/roles/$id/edit").' " title="编辑"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>编辑</button></a>';
                    })->html(true)
                    ->addColumn('名称', 'name')
                    ->addColumn('标签', 'label')
                    
                    ->addSearch('名称', 'name', 'string')
                    ->addSearch('标签', 'label', 'string')
                    
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
        return view('admin::roles.create',compact('data'));
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
        $this->validate($request, ['name' => 'required']);
        $data = $request->all();
        $data['permissions'] = json_encode($data['permissions']);
        Role::create($data);

        Session::flash('flash_message', 'Role added!');

        return redirect('admin/roles');
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
        $role = Role::findOrFail($id);
        $role->permissions = json_decode($role->permissions,1);
        return view('admin::roles.show', compact('role'));
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
        $role = Role::findOrFail($id);
        $role->permissions = json_decode($role->permissions,1);
        return view('admin::roles.edit', compact('role'));
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
        $this->validate($request, ['name' => 'required']);

        $role = Role::findOrFail($id);
        $data = $request->all();
        $data['permissions'] = json_encode($data['permissions']);
        $role->update($data);

        Session::flash('flash_message', 'Role updated!');

        return redirect('admin/roles');
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
        Role::destroy($id);

        Session::flash('flash_message', 'Role deleted!');

        return redirect('admin/roles');
    }
}
