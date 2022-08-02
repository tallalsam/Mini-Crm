<?php

namespace Tests\Unit;

use App\Company;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompanyDataValidation;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoEmail;
use DataTables;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Admin\CompanyController;

class CompanyAddTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function request()
    {
        return new Request([
            'name' => "Tallal Company",
            'email' => "tallal@gmail.com",
        ]);
    }


    public function testAddCompany()
    {
        $request = $this->request();
        $company = new CompanyRepositoryInterface;
        $company_controller = new CompanyController($company);
        $res = $company_controller->store( $request);
        if($res){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);

        }
    }
}
