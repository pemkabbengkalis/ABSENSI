<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InstansiModel;
use DataTables;
use App\Pegawaimodel;
use Session;
use App\RouteModel;
use App\KordinatModel;
use App\Cmenu;
use App\KecamatanModel;
class SkpdCo extends Controller
{
  public function __construct()
{
  $this->primary = "id_kordinat";
  $this->main    = "theme.skpd";
  $this->index   = $this->main.".index";

}

 public function index(){
   $class       = new Cmenu();
   $data        = KordinatModel::all();
   $listintansi = (object) $class->listinstansi();
   $kecamatan   = KecamatanModel::orderby('kecamatan','asc')->get();
   return view($this->index,compact('data','listintansi','kecamatan'));
 }

 public function save(Request $r){
        $validatedData = $r->validate([
          'kecamatan' => 'required',
          'namaskpd' => 'required',
          'kode_unitkerja' => 'required|unique:data_unitkerja,kode_unitkerja',
          'jenis' => 'required',
          'status' => 'required',
        ]);
    try {
          InstansiModel::create([
            'kecamatan' => $validatedData['kecamatan'],
            'kode_unitkerja' => $validatedData['kode_unitkerja'],
            'nama_unitkerja' => $validatedData['namaskpd'],
            'alamat' => $r->alamat,
            'jenis' => $validatedData['jenis'],
            'status' => $validatedData['status'],
          ]);
          return back()->with('success','Data berhasil disimpan');
    } catch (\Throwable $th) {
       return back()->with('danger',$th->getmessage());
    }

 }

 public function update(Request $r){
  $validatedData = $r->validate([
    'kecamatan' => 'required',
    'namaskpd' => 'required',
    'jenis' => 'required',
    'status' => 'required',
  ]);
try {
  $data =[
    'kecamatan' => $validatedData['kecamatan'],
    'nama_unitkerja' => $validatedData['namaskpd'],
    'alamat' => $r->alamat,
    'jenis' => $validatedData['jenis'],
    'status' => $validatedData['status']
  ];
    InstansiModel::where('kode_unitkerja',$r->kode_unitkerja)->update($data);
    return back()->with('success','Data berhasil diupdate');
} catch (\Throwable $th) {
 return back()->with('danger',$th->getmessage());
}
 }
 public function hapus($id=null){
     try {
      InstansiModel::where('kode_unitkerja',base64_decode($id))->delete();
      return back()->with('success','Data berhasil dihapus');
     } catch (\Throwable $th) {
       return back()->with('danger',$th->getmessage());
     }
 }



     }
