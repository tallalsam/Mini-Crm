<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use  App\Http\Requests\EmployeeDataValidation;
use DataTables;
class EmployeeController extends Controller
{

    private $employee;
    private $company;
    public function __construct(EmployeeRepositoryInterface $employee,
    CompanyRepositoryInterface $company){
        $this->employee=$employee;
        $this->company=$company;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user();
       return view('admin.employee.index',['user'=>$user,'title'=> trans('sentence.employee')]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user=Auth::user();
        $companies=$this->company->all();
        return view('admin.employee.create',['user'=>$user,'title'=> trans('sentence.create'),'companies'=>  $companies]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeDataValidation $request)
    {
        try{
            $empCreated=$this->employee->create($request->all());
            return response()->json(['status'=>1,'message'=> trans('sentence.creation_msg')]);
            }catch(Execption $e){
                return response()->json(['status'=>0,'message'=>$e->getMessage()]);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=Auth::user();
        $companies=$this->company->all();

        $employee=$this->employee->show($id);
            return view('admin.employee.edit',['user'=>$user,'title'=> trans('sentence.edit'),'employee'=>$employee,'companies'=>$companies]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $empupdated=$this->employee->update($request->all(),$id);
            return response()->json(['status'=>1,'message'=> trans('sentence.updation_msg')]);
            }catch(Execption $e){
                return response()->json(['status'=>0,'message'=>$e->getMessage()]);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $empdleted=$this->employee->delete($id);
            return response()->json(['status'=>1,'message'=> trans('sentence.deletion_msg')]);
            }catch(Execption $e){
                return response()->json(['status'=>0,'message'=>$e->getMessage()]);
            }
    }


    public function employeesData()
    {
        $data = Employee::latest()->get();
        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="'.route('employees.edit', ['employee'=>$row->id]) .'" data-id="' . $row->id . '" class="edit btn btn-success btn-sm">' . trans('sentence.edit') . '</a> <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm">' . trans('sentence.btn_del') . '</a>';
                return $actionBtn;
            })
            ->addColumn('company', function ($row) {
                return @$row->company->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
