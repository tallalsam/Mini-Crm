<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompanyDataValidation;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoEmail;
use DataTables;

class CompanyController extends Controller
{

    private $company;
    public function __construct(CompanyRepositoryInterface $company){
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
       return view('admin.company.index',['user'=>$user,'title'=> trans('sentence.companies')]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user=Auth::user();
        return view('admin.company.create',['user'=>$user,'title'=> trans('sentence.create')]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'website'   => '',
        ]);

        $company = Company::Create($data);
        if($request->file('logo')){
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('logo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('logo')->storeAs('public/logos', $fileNameToStore);

            // generate thumbnail of logo 100 x 100
            Image::configure(array('driver' => 'gd'));
            $img = Image::make($request->file('logo')->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public/logo_thumbnail/'.$fileNameToStore));

            $company->logo = $fileNameToStore;
            $company->save();
        }
        if($company->email){

            $mailData = [
                'title' => 'Company Information',
                'url' => 'https://www.minicrm.com'
            ];

            Mail::to($company->email)->send(new DemoEmail($mailData));
        }

        return redirect()->route('companies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $company=$this->company->show($id);
            return view('admin.company.edit',['user'=>$user,'title'=> trans('sentence.edit'),'company'=>$company]);

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
        $data = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'website'   => '',
        ]);
        $comCreated=$this->company->update($request->all(),$id);
        $company = Company::find($id);
        if ($request->file('logo')) {
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('logo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('logo')->storeAs('public/logos', $fileNameToStore);

            // generate thumbnail of logo 100 x 100
            Image::configure(array('driver' => 'gd'));
            $img = Image::make($request->file('logo')->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public/logo_thumbnail/' . $fileNameToStore));

            $company->logo = $fileNameToStore;
            $company->save();
        }
        return redirect()->route('companies.index');

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
            $comp = Company::find($id);
            if($comp->logo){
                $url_thumb = storage_path('app/public/logo_thumbnail/'. $comp->logo);
                unlink($url_thumb);
                $url =
                storage_path('app/public/logos/'. $comp->logo);
                unlink($url);
            }
            $comdeleted=$this->company->delete($id);
            return response()->json(['status'=>1,'message'=> trans('sentence.deletion_msg')]);
            }catch(Execption $e){
                return response()->json(['status'=>0,'message'=>$e->getMessage()]);
            }
    }

    public function companiesData()
    {
        $data = Company::latest()->get();
        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="'.route('companies.edit', ['company'=>$row->id]) .'" data-id="' . $row->id . '" class="edit btn btn-success btn-sm">'.trans('sentence.edit').'</a> <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm">' . trans('sentence.btn_del') . '</a>';
                return $actionBtn;
            })
            ->addColumn('company_image', function ($row) {
                if($row->logo){
                    $url = asset('storage/logo_thumbnail/' . $row->logo);
                    return '<img src="' . $url . '" border="0" width="100" height="100" class="img-rounded" align="center" />';
                }else{
                    return 'N/A';
                }

            })
            ->rawColumns(['action', 'company_image'])
            ->make(true);
    }
}
