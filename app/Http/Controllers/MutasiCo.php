<?php
      namespace App\Http\Controllers;
      use Illuminate\Http\Request;
      use App\InstansiModel;
      use DataTables;
      use Session;
      use App\UserModel;
      use App\PegawaiModel;
      use App\TblMutasi;
      use App\JobMutasi;
      use App\Cmenu;
      use App\Penempatans;
      use App\Bidang;
      use Intervention\Image\ImageManagerStatic as Image;
      class MutasiCo extends Controller
      {
        public function __construct()
      {

      }

      public function index(){
          $class       = new Cmenu();
          $opd         = (object) $class->listinstansi();
          $pegawai     = PegawaiModel::all();
          $data        = JobMutasi::select('tbl_pegawai.nip as nip','asal_instansi','job_mutasi.status as status','pindah_instansi','job_mutasi.created_at as created_at','job_mutasi.pegawai_id as pegawai_id','tbl_pegawai.nama as nama','tbl_pegawai.gd as gd','tbl_pegawai.gb as gb','job_mutasi.catatan as catatan')->join('tbl_pegawai','tbl_pegawai.id','job_mutasi.pegawai_id')->orderBy('job_mutasi.id','desc')->get();
          return view('theme.mutasi.index',compact('pegawai','opd','data'));
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

      public function jobPindahPegawai(Request $r){
        $catatan = 'SUCCESS';
        try {
          $data = JobMutasi::where('status','PENDING')->get();
          foreach ($data as $i => $v) {
            $rmvPenempatan = Bidang::join('tbl_penempatan','tbl_penempatan.id_bidang','tbl_bidang.id_bidang')->where('no',$v->pegawai_id)->delete();
            if(!$rmvPenempatan){
              $catatan = 'GAGAL MENGHAPUS PENEMPATAN';
            }
            /**
             * update Pegawai Kode Unitkerja
             */
            $datapg = [
              'kode_unitkerja'=>$v->pindah_instansi
            ];
            $pg  = PegawaiModel::where('id',$v->pegawai_id)->update($datapg);
            if(!$pg){
              $catatan = 'GAGAL MENGUPDATE DATA PEGAWAI KE INSTANSI BARU';
            }

            /**
             * UPDATE USER LOGIN
             */

             $user = UserModel::where('id_pegawai',$v->pegawai_id)->update($datapg);
             if(!$user){
              $catatan = 'GAGAL MENGUPDATE DATA LOGIN KE INSTANSI BARU';
            }

            $mutasi = [
              'status'=>'CLOSED',
            ];
            $act = TblMutasi::where('pegawai_id',$v->pegawai_id)->where('instansi_id',$v->asal_instansi)->update($mutasi);

            $mutasi = [
              'pegawai_id'=>$v->pegawai_id,
              'instansi_id'=>$v->pindah_instansi,
              'status'=>'OPEN',
              'tahun'=>date('Y'),
              'catatan'=>'Pegawai Aktif'
            ];
            $act = TblMutasi::insert($mutasi);

            /***
             * UPDATE STATUS
             */
            if($catatan == 'SUCCESS'){
              $data = [
                'status'=>'DONE',
                'catatan'=>'PINDAH PEGAWAI BERHASIL'
              ];
              $update = JobMutasi::where('id',$v->id)->update($data);
            }else{
              $data = [
                'status'=>'ERROR',
                'catatan'=>$catatan
              ];
              $update = JobMutasi::where('id',$v->id)->update($data);
            }
          }
        } catch (\Throwable $th) {
          $data = [
            'status'=>'ERROR',
            'catatan'=>$th->getMessage()
          ];
          $update = JobMutasi::where('id',$v->id)->update($data);
        }
      }
      public function save(Request $r){
        $mutasi = TblMutasi::where('status','OPEN')->where('pegawai_id',$r->pegawai_id)->first();
        $data =[
          'pegawai_id'=>$r->pegawai_id,
          'asal_instansi'=>$mutasi->instansi_id,
          'pindah_instansi'=>$r->pindah_instansi,
          'status'=>'PENDING'
        ];
        $act = JobMutasi::insert($data);
        if($act){
          return back()->with('success','Daftar Mutasi Berhasil ditambahkan System akan berjalan otomatis untuk melakukan pemindahan. pastikan anda memonitoring status di table daftar Mutasi.');
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