<?php
      namespace App\Http\Controllers;
      use Illuminate\Http\Request;
      use App\InstansiModel;
      use DataTables;
      use Session;
      use App\UserModel;
      use App\PegawaiModel;
      use App\TblMutasi;
      use Intervention\Image\ImageManagerStatic as Image;
      class MutasiCo extends Controller
      {
        public function __construct()
      {

      }

      public function index(){
          print("success");
      }

      public function pindahPegawai(){
        $pegawai = PegawaiModel::all();
        $berhasil = 0;
        $gagal    = 0;
        $tersedia = 0;
        foreach($pegawai as $i => $v){
          
          try {
            $user = UserModel::where('id_pegawai',$v->id)->first();
            if($user != null){
              $check = TblMutasi::where('pegawai_id',$user['id_pegawai'])->where('instansi_id',$user['kode_unitkerja'])->count();
              if($check > 0){
                $tersedia = $i+1;
              }else{
                $data = [
                  'pegawai_id'=>$user['id_pegawai'],
                  'instansi_id'=>$user['kode_unitkerja'],
                  'status'=>'OPEN',
                  'tahun'=>date('Y'),
                  'catatan'=>'Pegawai Aktif'
                ];
                $act = TblMutasi::insert($data);
                if($act){
                  $berhasil = $i+1;
                }else{
                  $gagal  = $i+1;
                }
            }
            
            }
           
          } catch (\Throwable $th) {
            print $th->getMessage();
          }
        }

        print 'BERHASIL : '.$berhasil.'<br>'.'GAGAL : '.$gagal.'<br> SUDAH ADA :'.$tersedia;

        
      }
      public function save(Request $r){
        $data =[

        ];

        $act = Jabatan::insert($data);
        if($act){
          return back()->with();
        }

      }
      public function update(Request $r){
        // $data =[

        // ];

        // $act = Jabatan::where(,$r->id)->update($data);
        // if($act){
        //   return back()->with(,);
        // }

      }
      public function hapus($id){
        // $act = Jabatan::where(,base64_decode($id))->delete();
        // if($act){
        //   return back()->with(,);
        // }

      }
    }