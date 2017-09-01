<?php

namespace Shopex\LubanAdmin\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use File;
use Illuminate\Http\Request;
use Response;
use Session;
use View;

class ProcessController extends Controller
{
    /**
     * Display generator.
     *
     * @return Response
     */
    public function getGenerator()
    {
        return view('admin::generator');
    }

    /**
     * Process generator.
     *
     * @return Response
     */
    public function postGenerator(Request $request)
    {
        $commandArg = [];
        $commandArg['name'] = $request->crud_name;

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
        if ($request->has('route')) {
            $commandArg['--route'] = $request->route;
        }
        if ($request->has('model_title')) {
            $commandArg['--model-title'] = $request->model_title;
        }

        if ($request->has('view_path')) {
            $commandArg['--view-path'] = $request->view_path;
        }

        if ($request->has('controller_namespace')) {
            $commandArg['--controller-namespace'] = $request->controller_namespace;
        }

        if ($request->has('model_namespace')) {
            $commandArg['--model-namespace'] = $request->model_namespace;
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
}
