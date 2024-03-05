<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InstansiModel;
use DataTables;
use App\Sp2hpModel;
use Session;
use App\Siswamodel;
use App\Gurumodel;
use App\Cmenu;
use App\PegawaiModel;
use App\UsulanModel;
use App\TblDinas;
use App\TblCuti;
use App\AbsenModel;
class DashboardCo extends Controller
{
  public function __construct()
{
  $this->main  = "theme.dashboard";
  $this->index = $this->main.".dashboard";


}
 public function index(){
  if(Session::get('level')=='user'){
    $pegawai = PegawaiModel::where('kode_unitkerja',Session::get('kode_unitkerja'))->count();
    $D = AbsenModel::where('kode_unitkerja',Session::get('kode_unitkerja'))->where('status','D')->count();
    $C = AbsenModel::where('kode_unitkerja',Session::get('kode_unitkerja'))->where('status','C')->count();
    $S = AbsenModel::where('kode_unitkerja',Session::get('kode_unitkerja'))->where('status','S')->count();
    return view($this->index,compact('pegawai','C','D','S'));
  }else{
    $pegawai = PegawaiModel::count();
    $D = AbsenModel::where('status','D')->count();
    $C = AbsenModel::where('status','C')->count();
    $S = AbsenModel::where('status','S')->count();
    return view($this->index,compact('pegawai','C','D','S'));
  }
   
 }

 public function testlogika(){
   return view('theme.testlogika.index');
 }



     }
