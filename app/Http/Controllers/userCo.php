<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Cmenu;
use App\Loginmodel;
use App\PegawaiModel;
use App\AbsenModel;
use App\Bidang;
use App\Http\Requests\AkunRequest;
class userCo extends Controller
{
  public function __construct()
{
  $this->primary    = "id_user";
  $this->main       = "theme.users";
  $this->level      = Session::get('level');
  $this->userid     = Session::get('id_user');
  $this->msukses    = 'Data Berhasil disimpan';
  $this->msupdate   = 'Data Berhasil diupdate';
  $this->index      = $this->main.".index";
  $this->dataskpd   = $this->main.".dataskpd";

}


public function AkunPegawaiByInstansi(){
  $data = PegawaiModel::select('tbl_pegawai.id as id', 'tbl_pegawai.nip as nip', 'tbl_bidang.id_bidang as id_bidang',
    'tbl_bidang.bidang as bidang', 'tbl_pegawai.nama as nama', 'tbl_pegawai.gd as gd', 'tbl_pegawai.gb as gb')
    ->leftJoin('tbl_user', 'tbl_user.id_pegawai', '=', 'tbl_pegawai.id')
    ->join('tbl_penempatan', 'tbl_penempatan.no', '=', 'tbl_pegawai.id')
    ->join('tbl_bidang', 'tbl_bidang.id_bidang', '=', 'tbl_penempatan.id_bidang')
    ->where('tbl_bidang.kode_unitkerja', Session::get('kode_unitkerja'))
    ->groupBy('tbl_pegawai.id')
    ->get();

  $bidang = Bidang::where('kode_unitkerja',Session::get('kode_unitkerja'))->get();
  return view('theme.users.instansi',compact('data'));
}

public function SelectByInstansi($unitkerja){
          $data = PegawaiModel::select(
            'tbl_pegawai.id as id',
            'tbl_pegawai.nip as nip',
            'tbl_bidang.id_bidang as id_bidang',
            'tbl_bidang.bidang as bidang',
            'tbl_pegawai.nama as nama',
            'tbl_pegawai.gd as gd',
            'tbl_pegawai.gb as gb'
        )->join('tbl_penempatan', 'tbl_penempatan.no', 'tbl_pegawai.id')
        ->join('tbl_bidang', 'tbl_bidang.id_bidang', 'tbl_penempatan.id_bidang')
        ->where('tbl_bidang.kode_unitkerja', $unitkerja)
        ->get();

        $options = [];
        foreach ($data as $pegawai) {
        $options[] = [
        'id' => $pegawai->id,
        'text' => $pegawai->nama // You can customize this to display more information if needed
        ];
        }

        return response()->json($options);
}

public function index(){
  $class = new Cmenu();
  $listintansi = (object) $class->listinstansi();
  $data  = Loginmodel::where('level','!=','admin')->get();

  return view($this->index,compact('data','listintansi'));
}

public function dataskpd(){
  $class = new Cmenu();
  $listintansi = (object) $class->listinstansi();
  $data  = Loginmodel::where('level','user')->get();

  return view($this->dataskpd,compact('data','listintansi'));
}

public function save(AkunRequest $r){
 

  $bidang = ($r->has('bidang')) ? $r->bidang:null;
  $level  = ($r->has('level')) ? $r->level:'ASN';
  $data=[
    'nama'=>$r->nama,
    'username'=>$r->username,
    'kode_unitkerja'=>$r->unitkerja,
    'password'=>md5($r->pass),
    'id_bidang'=>$bidang,
    'id_pegawai'=>$r->idpg,
    'level'=>$level
  ];
  try {
    $act = Loginmodel::insert($data);
    return back()->with('success',$this->msukses);
  } catch (\Throwable $th) {
    return back()->with('danger',$th->getmessage());
  }
  
}
public function update(Request $r){
  $bidang = ($r->has('bidang'))? $r->bidang:null;
  $level  = ($r->has('level')) ? $r->level:'ASN';
  $data=[
    'nama'=>$r->nama,
    'username'=>$r->username,
    'alamat'=>$r->alamat,
    'email'=>$r->email,
    'kode_unitkerja'=>$r->unitkerja,
    'nohp'=>$r->nohp,
    'id_bidang'=>$bidang,
    'password'=>md5($r->pass),
    'level'=>$level
  ];
  $act = Loginmodel::where($this->primary,$r->id)->update($data);
  return back()->with('success',$this->msukses);
}
public function userblokir($id=null){
  $data=[
    'blokir'=>'Y'
  ];
  $act = Loginmodel::where($this->primary,base64_decode($id))->update($data);
  return back()->with('success','User Berhasil diblokir');
}

public function bukablokir($id=null){
  $data=[
    'blokir'=>'N'
  ];
  $act = Loginmodel::where($this->primary,base64_decode($id))->update($data);
  return back()->with('success','User Berhasil diaktifkan');
}

public function openblokir($id=null){
  $data=[
    'blokir'=>'N'
  ];
  $act = Loginmodel::where('id_pegawai',base64_decode($id))->update($data);
  return back()->with('success','User Berhasil diaktifkan');
}

public function hapus($id=null){
  $act = Loginmodel::where($this->primary,base64_decode($id))->delete();
  return back()->with('success','Data Berhasil di hapus');
}

public function hapusAkunPegawai($id=null){
  $users = Loginmodel::where('id_pegawai',base64_decode($id))->first();
  try {
    $check = AbsenModel::where('id_pegawai',$users->id_user)->count();
    if($check > 0){
      return back()->with('danger','Akun ini sudah melakukan absensi dan tidak dapat dihapus');
    }else{
      $act = Loginmodel::where('id_pegawai',base64_decode($id))->delete();
      return back()->with('success','Data Berhasil di hapus');
    }
  } catch (\Throwable $th) {
    return back()->with('danger',$th->getMessage());
  }
  
}
public function reset($id=null){
  $data=[
    'password'=>md5('12345')
  ];
  $act = Loginmodel::where($this->primary,base64_decode($id))->update($data);
  return back()->with('success','Password Berhasil di reset');
}


public function resetakun($id=null){
  try {
    $pegawai = PegawaiModel::find(base64_decode($id));
    $data = [
      'password'=>md5($pegawai->nip),
      'username'=>$pegawai->nip
    ];
    $act = Loginmodel::where('id_pegawai',base64_decode($id))->update($data);
    return back()->with('success','Password Berhasil di reset');

  } catch (\Throwable $th) {
    return back()->with('danger',$th->getmessage());
  }
 
}

public function blokirakun($id=null){
  try {
    $pegawai = PegawaiModel::find(base64_decode($id));
    $data=[
      'blokir'=>'Y'
    ];
    $act = Loginmodel::where('id_pegawai',$pegawai->id)->update($data);
    return back()->with('success','User Berhasil diblokir');
  } catch (\Throwable $th) {
    return back()->with('danger',$th->getmessage());
  }
  
 }




     }
