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
use App\UserModel;
use App\JamModel;
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

 public function skpdSettingCentral(Request $r) {
  
  if ($r->has('bypass')) {
    $data = [
      'bypass' => 'N'
    ];
    UserModel::where('kode_unitkerja',$r->idinstansi)->update($data);
      foreach ($r->bypass as $v) {
          try {
              $check = UserModel::where('id_pegawai', $v)->count();
              if ($check > 0) {
                  $data = [
                      'bypass' => 'Y'
                  ];
                  UserModel::where('id_pegawai', $v)->update($data);
              }
          } catch (\Throwable $th) {
              throw $th;
          }
      }
  }
  
  /***
   * JAM 
   */

   $hari = array('senin','selasa','rabu','kamis','jumat','sabtu');
   foreach ($hari as $i => $v) {
       $jamMasuk  = isset($r->jammasuk[$i]) ? $r->jammasuk[$i] : null;
       $jamPulang = isset($r->jampulang[$i]) ? $r->jampulang[$i] : null;
       $batasAbsenMasuk = isset($r->batasabsenmasuk[$i]) ? $r->batasabsenmasuk[$i] : null;
       $batasAbsenPulang = isset($r->batasabsenpulang[$i]) ? $r->batasabsenpulang[$i] : null;
       
       if($jamMasuk != null && $jamPulang != null && $batasAbsenMasuk != null && $batasAbsenPulang != null){
        $checkJamMasuk = JamModel::where('hari',$v)->where('jenis','Jam Masuk')
          ->where('kode_unitkerja',$r->idinstansi)
          ->count();
        if($checkJamMasuk > 0){
          $data=[
            'kode_unitkerja'=> $r->idinstansi,
            'jenis'=> 'Jam Masuk',
            'hari'=> $v,
            'jam'=> $jamMasuk,
            'batas'=> $batasAbsenMasuk,
          ];
          JamModel::where('hari',$v)
                 ->where('jenis','Jam Masuk')
                 ->where('kode_unitkerja',$r->idinstansi)
                 ->update($data);
        }else{
          $data=[
            'kode_unitkerja'=> $r->idinstansi,
            'jenis'=> 'Jam Masuk',
            'hari'=> $v,
            'jam'=> $jamMasuk,
            'batas'=> $batasAbsenMasuk,
          ];
          JamModel::insert($data);
        }

        //Jam Pulang

        $checkJamPulang = JamModel::where('hari',$v)->where('jenis','Jam Pulang')
        ->where('kode_unitkerja',$r->idinstansi)
        ->count();
      if($checkJamPulang > 0){
        $data=[
          'kode_unitkerja'=> $r->idinstansi,
          'jenis'=> 'Jam Pulang',
          'hari'=> $v,
          'jam'=> $jamPulang,
          'batas'=> $batasAbsenPulang,
        ];
        JamModel::where('hari',$v)
               ->where('jenis','Jam Pulang')
               ->where('kode_unitkerja',$r->idinstansi)
               ->update($data);
      }else{
        $data=[
          'kode_unitkerja'=> $r->idinstansi,
          'jenis'=> 'Jam Pulang',
          'hari'=> $v,
          'jam'=> $jamPulang,
          'batas'=> $batasAbsenPulang,
        ];
        JamModel::insert($data);
      }

       }
       
   }

  return back();
}




     }
