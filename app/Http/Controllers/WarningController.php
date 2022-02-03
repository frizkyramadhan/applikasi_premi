<?php

namespace App\Http\Controllers;

use App\Models\Warning;
use App\Models\WarningCategory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class WarningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Warnings (SP)';
        $subtitle = 'Warnings Data';
        // cara menampilkan semua data sesuai urutan di table yang tidak dihapus
        // $warnings = Warning::all();

        // cara menampilkan semua data dengan urutan tertentu
        // $warnings = Warning::with('employee.project')->orderBy('id','desc')->paginate(5)->withQueryString();
        $warnings = Warning::with('employee.project')->latest()->get();
        
        // cara menampilkan semua data termasuk yang soft deleted
        // $warnings = Warning::withTrashed()->with('employee.project')->orderBy('id','desc')->get();

        // return $warnings;
        return view('warning.index', compact('title','subtitle','warnings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Warnings (SP)';
        $subtitle = 'Add Warnings';
        $employees = Employee::with('project')->orderBy('nik','asc')->get();
        $warning_categories = WarningCategory::orderBy('warning_name','asc')->get();
        return view('warning.add', compact('title','subtitle','employees', 'warning_categories'));
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
            'employee_id' => 'required',
            'warning_category_id' => 'required',
            'warning_date' => 'required',
        ], [
            'employee_id.required' => 'Employee is required',
            'warning_category_id.required' => 'Warning Category is required',
            'warning_date.required' => 'Warning Date is required'
        ]);
        // return $request;
        
        // cara insert 1 : Eloquent ORM
        // $warning = new Warning;
        // $warning->employee_id = $request->employee_id;
        // $warning->warning_category_id = $request->warning_category_id;
        // $warning->warning_date = $request->warning_date;
        // $warning->save();

        //cara insert 2 : Mass Assignment
        // Warning::create([
        //     'employee_id' => $request->employee_id,
        //     'warning_category_id' => $request->warning_category_id,
        //     'warning_date' => $request->warning_date,
        // ]);

        // cara insert 3 : Quick Mass Assignment, dengan syarat field table di database dan name input di view harus sama
        Warning::create($request->all()); 

        // cara insert 4 : gabungan Eloquent ORM dan Mass Assignment, tujuannya untuk memisahkan antara data2 yang sifatnya umum dengan Mass Assignment dan yang sifatnya khusus dengan Eloquent ORM. Data2 yang khusus biasanya di $guarded model, tidak bisa diinsert menggunakan Mass Assignment, sehingga diinput menggunakan Eloquent ORM
        // Warning::create([
        //     'employee_id' => $request->employee_id,
        //     'warning_category_id' => $request->warning_category_id,
        //     'warning_date' => $request->warning_date,
        // ]); -- data umum
        // $warning->warning_date = $request->warning_date; -- data khusus yang di $guarded di model

        return redirect('warnings')->with('status', 'Warning added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warning  $warning
     * @return \Illuminate\Http\Response
     */
    public function show(Warning $warning)
    {
        $title = 'Warnings (SP)';
        $subtitle = 'Warning Detail';
        $warning->makeHidden(['created_at', 'updated_at']);
        return view('warning.show', compact('title','subtitle','warning'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warning  $warning
     * @return \Illuminate\Http\Response
     */
    public function edit(Warning $warning)
    {
        $title = 'Warnings (SP)';
        $subtitle = 'Edit Warnings';
        $employees = Employee::with('project')->orderBy('nik','asc')->get();
        $warning_categories = WarningCategory::orderBy('warning_name','asc')->get();
        return view('warning.edit', compact('title','subtitle','warning', 'employees', 'warning_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warning  $warning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warning $warning)
    {
        $request->validate([
            'employee_id' => 'required',
            'warning_category_id' => 'required',
            'warning_date' => 'required',
        ], [
            'employee_id.required' => 'Employee is required',
            'warning_category_id.required' => 'Warning Category is required',
            'warning_date.required' => 'Warning Date is required'
        ]);
        // return $warning;
        // cara update 1 : Eloquent ORM
        // $warning = new Warning;
        // $warning->employee_id = $request->employee_id;
        // $warning->warning_category_id = $request->warning_category_id;
        // $warning->warning_date = $request->warning_date;
        // $warning->save();

        //cara update 2 : Mass Assignment
        Warning::where('id', $warning->id)->update([
            'employee_id' => $request->employee_id,
            'warning_category_id' => $request->warning_category_id,
            'warning_date' => $request->warning_date,
        ]);

        return redirect('warnings')->with('status', 'Warning updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warning  $warning
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warning $warning)
    {
        // cara 1
        $warning->delete();

        // cara 2
        // Warning::destroy($warning->id);

        // cara 3
        // Warning::where('id', $warning->id)->delete();

        return redirect('warnings')->with('status', 'Warning deleted successfully');
    }

    public function trash()
    {
        $title = 'Warnings (SP)';
        $subtitle = 'Warnings Data - Deleted';
        // cara menampilkan semua data termasuk yang soft deleted
        $warnings = Warning::onlyTrashed()->with('employee.project')->orderBy('id','desc')->get();

        // return $warnings;
        return view('warning.trash', compact('title','subtitle','warnings'));
    }

    public function restore($id = null)
    {
        if($id != null) {
            $warnings = Warning::onlyTrashed()->where('id', $id)->restore();
        } else {
            $warnings = Warning::onlyTrashed()->restore();
        }

        return redirect('warnings/trash')->with('status', 'Warning restored successfully');
    }
    
    public function delete($id = null)
    {
        if($id != null) {
            $warnings = Warning::onlyTrashed()->where('id', $id)->forceDelete();
        } else {
            $warnings = Warning::onlyTrashed()->forceDelete();
        }

        return redirect('warnings/trash')->with('status', 'Warning deleted permanently');
    }

    public function index_data()
    {
        $warnings = Warning::with('employee.project')->latest()->get();

        return datatables()->of($warnings)
            ->addIndexColumn()
            ->addColumn('nik', function($warnings){
                return $warnings->employee->nik;
            })
            ->addColumn('name', function($warnings){
                return $warnings->employee->name;
            })
            ->addColumn('code', function($warnings){
                return $warnings->employee->project->code;
            })
            ->addColumn('warning_name', function($warnings){
                return $warnings->warning_category->warning_name;
            })
            ->addColumn('warning_date', function($warnings){
                return date('d-M-Y', strtotime($warnings->warning_date));
            })
            ->addColumn('action', 'warning.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
