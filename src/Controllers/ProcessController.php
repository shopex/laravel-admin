<?php

namespace Shopex\LubanAdmin\Controllers;

use App\Http\Controllers\Controller;
use Shopex\LubanAdmin\Finder;

use Artisan;
use File;
use Illuminate\Http\Request;
use Response;
use Session;
use View;
use Shopex\CrudGenerator\Commands\CrudViewCommand;
use Shopex\LubanAdmin\Models\Generator;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $dataSet = Generator::class;

        $finder = Finder::create($dataSet, '模型列表')
                    ->setId('id')
                    ->addAction('新建模型', [$this, 'getGenerator'])
                    ->addSort('按修改时间倒排', 'created_at', 'desc')
                    ->addSort('按修改时间正排', 'created_at')
                    ->addBatchAction('删除', [$this, 'destroy'])
                    ->addBatchAction('表关联', [$this, ''])
                    ->addColumn('操作', 'id')->modifier(function($id){
                        return '<a href="'.url("/admin/generator/$id/edit").' " title="编辑"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>编辑</button></a>';
                    })->html(true)->size(1)
                    ->addColumn('模型名称', 'model_title')->size(1)
                    ->addColumn('模型', 'crud_name')->size(1)
                    ->addColumn('模型类型', 'model_type')->size(1)
                    ->addColumn('模型命名空间', 'model_namespace')->size(1)
                    ->addColumn('控制器命名空间', 'controller_namespace')->size(1)
                    ->addColumn('路由组前缀', 'route_group')->size(1)
                    ->addColumn('视图路径', 'view_path')->size(1)
                    ->addColumn('生成路由', 'is_route')->size(1)
                    ->addColumn('生成视图', 'is_view')->size(1)
                    ->addColumn('生成控制器', 'is_controller')->size(1)
                    ->addColumn('生成Migration', 'is_migration')->size(1)
                    ->addColumn('是否暂存', 'staging')->size(1)
                    ->addTab("全部", [])
                    ->addTab("暂存", [['staging','=','yes']])
                    ->addTab("已生成", [['staging','=','no']])
                    ->addSearch('模型名称', 'model_title', 'string')
                    ->addSearch('模型', 'crud_name', 'string')
                    ->addSearch('模型类型', 'model_type', 'string')
                    
                    ->addInfoPanel('基本信息', [$this, 'detail']);

        return $finder->view();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $generator = Generator::findOrFail($id);
        $files_array = $generator->checkFile();
        $generator->fields = json_decode($generator->fields,1);
        $curdview = new CrudViewCommand();
        $curdtype = array_keys($curdview->getTypeLookup());
        $curdtype = array_combine($curdtype,$curdtype);
        return view('admin::generator', compact('generator','curdtype','files_array'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $generator = Generator::findOrFail($id);
        $files_array = $generator->checkFile();
        return view('admin::generator-detail', compact('generator','files_array'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Generator::destroy($id);

        Session::flash('flash_message', 'Generator deleted!');

        return redirect('admin/generator');
    }
    /**
     * Display generator.
     *
     * @return Response
     */
    public function getGenerator()
    {
        $generator = new Generator();
        $generator->id = 0;
        $generator->crud_name = "Tt";
        $generator->controller_namespace = "Admin";
        $generator->model_type = 'entity';
        $generator->route_group = 'admin';
        $generator->view_path = 'admin';
        $generator->model_title  = '模型';
        $generator->is_route  = 'yes';
        $generator->is_view  = 'yes';
        $generator->is_controller  = 'yes';
        $generator->is_migration  = 'yes';

        $fields_array['fields'][] = 'name'; 
        $fields_array['field_descs'][] = '名称'; 
        $fields_array['fields_type'][] = "1"; 
        $fields_array['fields_required'][] = '1'; 
        $fields_array['fields_search'][] = '1'; 
        $fields_array['fields_list'][] = '1'; 
        $generator->fields = $fields_array;
        $files_array = [];
        $curdview = new CrudViewCommand();
        $curdtypekey = array_keys($curdview->getTypeLookup());
        $curdtype = array_combine($curdtypekey, $curdtypekey);
        return view('admin::generator',compact('generator','curdtype','files_array'));
    }

    /**
     * Process generator.
     *
     * @return Response
     */
    public function postGenerator(Request $request)
    {
        $commandArg = [];
        $requestData = [
            'model_title' => $request->model_title,
            'crud_name' => $request->crud_name,
            'model_type' => $request->model_type,
            'model_namespace' => $request->model_namespace,
            'controller_namespace' => $request->controller_namespace,
            'route_group' => $request->route_group,
            'view_path' => $request->view_path,
            'is_route' => $request->has('is_route')?"yes":"no",
            'is_view' => $request->has('is_view')?"yes":"no",
            'is_controller' => $request->has('is_controller')?"yes":"no",
            'is_migration' => $request->has('is_migration')?"yes":"no",
            'fields' => json_encode([
                'fields' => $request->fields,
                'field_descs'=> $request->field_descs,
                'fields_type'=> $request->fields_type,
                'fields_required'=> $request->fields_required,
                'fields_search'=> $request->fields_search,
                'fields_list'=> $request->fields_list,
            ]),
            'staging' => $request->has('staging')?"yes":"no",
        ];
        if ($request->remove_file == 'on' && $request->id > 0) {
            $generator = Generator::findOrFail($request->id);
            $generator->removeAllFile();
            $requestData['files'] = '';
            $requestData['staging'] = 'yes';
            $generator->update($requestData);
            return redirect('admin/generator');
        }
        if ($request->id > 0 ) {
            $generator = Generator::findOrFail($request->id);
            $generator->update($requestData);
            if ($request->regen == 'on'  ) {
                $generator->removeAllFile();
            }
        }else{
            $requestData['files'] = '';
            $generator = Generator::create($requestData);
        }
        
        $commandArg['--id'] = $generator->id;

        if ($requestData['staging'] == 'yes') {
            return redirect('admin/generator');
        }
        $commandArg['name'] =  $request->crud_name;

        if ($request->has('fields')) {
            $fieldsArray = [];
            $validationsArray = [];
            $searchArray = [];//搜索
            $inlistArray = [];//列表显示
            $inlistTitleArray = [];//列表显示
            $x = 0;
            
            foreach ($request->fields as $field) {
                if ($request->fields_required[$x] == 1) {
                    $validationsArray[] = $field;
                }
                if ($request->fields_search[$x] == 1) {
                    $searchArray[] = $field. '#' . $request->field_descs[$x];
                }
                if ($request->fields_list[$x] == 1) {
                    $inlistArray[] = $field. '#' . $request->field_descs[$x];;
                }

                $fieldsArray[] = $field . '#' . $request->fields_type[$x] . "#" . $request->field_descs[$x];

                $x++;
            }

            $commandArg['--fields'] = implode(";", $fieldsArray);
        }

        if (!empty($searchArray)) {
            $commandArg['--searchs'] = implode(";", $searchArray);
        }
        if (!empty($inlistArray)) {
            $commandArg['--inlists'] = implode(";", $inlistArray);
        }

        if (!empty($validationsArray)) {
            $commandArg['--validations'] = implode("#required;", $validationsArray) . "#required";
        }
        $commandArg['--route'] = $request->has('is_route')?"yes":"no";
        $commandArg['--view'] = $request->has('is_view')?"yes":"no";
        $commandArg['--controller'] = $request->has('is_controller')?"yes":"no";
        $commandArg['--migration'] = $request->has('is_migration')?"yes":"no";

        if ($request->has('model_title')) {
            $commandArg['--model-title'] = $request->model_title;
        }

        if ($request->has('view_path')) {
            $commandArg['--view-path'] = $request->view_path;
        }
        if ($request->has('controller_namespace')) {
            $commandArg['--controller-namespace'] = $this->formatNamespace($request->controller_namespace);
        }

        if ($request->has('model_namespace')) {
            $commandArg['--model-namespace'] = $this->formatNamespace($request->model_namespace);
        }

        if ($request->has('route_group')) {
            $commandArg['--route-group'] = $request->route_group;
        }
        try {
            Artisan::call('crud:generate', $commandArg);
        } catch (\Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
       
        Session::flash('flash_message', 'Your CRUD has been generated. ');

        return redirect('admin/generator');
    }
    private function formatNamespace($namespace)
    {
        $namespace = str_replace('/', '\\', $namespace);
        $namespace = str_replace('\\', ' ', $namespace);
        return str_replace(' ', '\\', ucwords($namespace));
    }
}
