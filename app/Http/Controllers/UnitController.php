<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitModel;
use App\Models\Project;
use App\Imports\UnitImport;
use App\Exports\UnitExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Units';
        $subtitle = 'Units Data';
        $units = Unit::with('project', 'unit_model')->orderBy('unit_no','asc')->get();
        
        return view('unit.index', compact('title', 'subtitle', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Units';
        $subtitle = 'Add Unit Data';
        $unitModels = UnitModel::orderBy('model_no','asc')->get();
        $projects = Project::orderBy('code','asc')->get();
        return view('unit.add', compact('title','subtitle','unitModels', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_no' => 'required|unique:units',
            'unit_desc' => 'required',
            'unit_model_id' => 'required',
            'project_id' => 'required',
        ], [
            'unit_no.required' => 'Unit Number is required',
            'unit_desc.required' => 'Unit Description is required',
            'unit_model_id.required' => 'Unit Model is required',
            'project_id.required' => 'Project is required'
        ]);
        
        // cara insert 3 : Quick Mass Assignment, dengan syarat field table di database dan name input di view harus sama
        Unit::create($request->all()); 

        return redirect('units')->with('status', 'Unit added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        $title = 'Units';
        $subtitle = 'Unit Data Detail';
        $unit->makeHidden(['created_at', 'updated_at']);
        return view('unit.show', compact('title','subtitle','unit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        $title = 'Units';
        $subtitle = 'Edit Unit Data';
        $unitModels = UnitModel::orderBy('model_no','asc')->get();
        $projects = Project::orderBy('code','asc')->get();
        return view('unit.edit', compact('title','subtitle','unit','unitModels', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_no' => 'required',
            'unit_desc' => 'required',
            'unit_model_id' => 'required',
            'project_id' => 'required',
        ], [
            'unit_no.required' => 'Unit Number is required',
            'unit_desc.required' => 'Unit Description is required',
            'unit_model_id.required' => 'Unit Model is required',
            'project_id.required' => 'Project is required'
        ]);

        //cara update 2 : Mass Assignment
        Unit::where('id', $unit->id)->update([
            'unit_no' => $request->unit_no,
            'unit_desc' => $request->unit_desc,
            'unit_model_id' => $request->unit_model_id,
            'project_id' => $request->project_id
        ]);

        return redirect('units')->with('status', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect('units')->with('status', 'Unit deleted successfully');
    }

    public function import()
    {
        return view('unit.import',
        [
            'title' => 'Units',
            'subtitle' => 'Import Unit'
        ]);
    }

    public function importProcess()
    {
        Excel::import(new UnitImport, request()->file('file'));

        return redirect('units')->with('status', 'Unit uploaded successfully');
    }

    public function export()
    {
        return Excel::download(new UnitExport, 'Units.xlsx');
    }

    public function index_data()
    {
        $units = Unit::orderBy('unit_no', 'asc')->get();

        return datatables()->of($units)
            ->addIndexColumn()
            ->addColumn('unit_no', function($units){
                return $units->unit_no;
            })
            ->addColumn('unit_desc', function($units){
                return $units->unit_desc;
            })
            ->addColumn('model_no', function($units){
                return $units->unit_model->model_no;
            })
            ->addColumn('code', function($units){
                return $units->project->code;
            })
            ->addColumn('action', 'unit.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
