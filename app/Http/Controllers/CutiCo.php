<?php
      namespace App\Http\Controllers;
      use Illuminate\Http\Request;
      use App\InstansiModel;
      use DataTables;
      use Session;
      use App\TblCuti;
      use App\Cmenu;
      use App\AbsenModel;
      use App\UserModel;
      use Intervention\Image\ImageManagerStatic as Image;
      class CutiCo extends Controller
      {
        public function __construct()
      {

      }

      public function updateaksicuti(Request $r){
        $class = new Cmenu();
        try {
          if($r->filled('aksi')){
            $data = [
              'status'=>$r->aksi
            ];
            if($r->aksi=='Y'){
              $cuti          = TblCuti::where('id',$r->id)->first();
              $user          = UserModel::where('id_pegawai',$cuti->id_pegawai)->first();
              $date_parts    = explode(" s/d ", $cuti->rentang_absen);
              $tanggal_awal  = $date_parts[0];
              $tanggal_akhir = $date_parts[1];
              // Convert tanggal_awal and tanggal_akhir to timestamps
              $tanggal_awal_timestamp = strtotime($tanggal_awal);
              $tanggal_akhir_timestamp = strtotime($tanggal_akhir);
              for ($timestamp = $tanggal_awal_timestamp; $timestamp <= $tanggal_akhir_timestamp; $timestamp += 86400) {
                $tanggal = date('Y-m-d', $timestamp);
                $absen=[
                  'id_absen'=>uniqid(),
                  'id_pegawai'=>$user->id_user,
                  'status'=>'C',
                  'keterangan'=>$cuti->alasan,
                  'jenis'=>'M',
                  'kode_unitkerja'=>$cuti->id_instansi,
                  'no_surat'=>null,
                  'latitude'=>'-',
                  'longitude'=>'-',
                  'swafoto'=>'cuti.png',
                  'ip'=>'',
                  'tglabsen'=>$tanggal,
                  'file'=>$cuti->file,
                  'masaizin'=>null,
                ];
                AbsenModel::insert($absen);
                $absen=[
                  'id_absen'=>uniqid(),
                  'id_pegawai'=>$user->id_user,
                  'status'=>'C',
                  'keterangan'=>$cuti->alasan,
                  'jenis'=>'P',
                  'kode_unitkerja'=>$cuti->id_instansi,
                  'no_surat'=>null,
                  'latitude'=>'-',
                  'longitude'=>'-',
                  'swafoto'=>'cuti.png',
                  'ip'=>'',
                  'tglabsen'=>$tanggal,
                  'file'=>$cuti->file,
                  'masaizin'=>null,
                ];
                AbsenModel::insert($absen);
              }
              TblCuti::where('id',$r->id)->update($data);
              $title = $user->nama;
              $body = "Pengajuan Cuti anda sudah dikonfirmasi oleh BKPP";
              $class->sendNotification($user->token_firebase, $title, $body);
              return back();
            }
            else{
              $data = [
                'status'=>'N'
              ];
              $cuti          = TblCuti::where('id',$r->id)->first();
              $user          = UserModel::where('id_pegawai',$cuti->id_pegawai)->first();
              $date_parts    = explode(" s/d ", $cuti->rentang_absen);
              $tanggal_awal  = $date_parts[0];
              $tanggal_akhir = $date_parts[1];
              // Convert tanggal_awal and tanggal_akhir to timestamps
              $tanggal_awal_timestamp = strtotime($tanggal_awal);
              $tanggal_akhir_timestamp = strtotime($tanggal_akhir);
              for ($timestamp = $tanggal_awal_timestamp; $timestamp <= $tanggal_akhir_timestamp; $timestamp += 86400) {
                $tanggal = date('Y-m-d', $timestamp);
                AbsenModel::where('id_pegawai',$user->id_user)->where('tglabsen',$tanggal)->delete();
              }
              TblCuti::where('id',$r->id)->update($data);
              $title = $user->nama;
              $body = "Pengajuan Cuti anda sudah ditolak oleh BKPP";
              $class->sendNotification($user->token_firebase, $title, $body);
              return back();
            }
            
          }else{
            $data = [
              'status'=>'N'
            ];
            $cuti          = TblCuti::where('id',$r->id)->first();
            $user          = UserModel::where('id_pegawai',$cuti->id_pegawai)->first();
            $date_parts    = explode(" s/d ", $cuti->rentang_absen);
            $tanggal_awal  = $date_parts[0];
            $tanggal_akhir = $date_parts[1];
            // Convert tanggal_awal and tanggal_akhir to timestamps
            $tanggal_awal_timestamp = strtotime($tanggal_awal);
            $tanggal_akhir_timestamp = strtotime($tanggal_akhir);
            for ($timestamp = $tanggal_awal_timestamp; $timestamp <= $tanggal_akhir_timestamp; $timestamp += 86400) {
              $tanggal = date('Y-m-d', $timestamp);
              AbsenModel::where('id_pegawai',$user->id_user)->where('tglabsen',$tanggal)->delete();
            }
            TblCuti::where('id',$r->id)->update($data);
            $title = $user->nama;
            $body = "Pengajuan Cuti anda sudah ditolak oleh BKPP";
            $class->sendNotification($user->token_firebase, $title, $body);
            return back();
          }
          
        } catch (\Throwable $th) {
          //throw $th;
        }
      }

      public function apiusulancuti(Request $r){
        $class       = new Cmenu();
        $array       = array();
        $datacuti    = TblCuti::where('id_instansi',$r->skpd)->wheredate('created_at',$r->tanggal)->get();
        foreach ($datacuti as $i => $v){
       
            $pegawai = $class->getpegawaifromiduser($v->id_pegawai);
            $diterima = $v->status == 'Y' ? 'Disetujui' : 'Di batalkan';
            $status = $v->status == 'A' ? 'Menunggu Konfirmasi..' : $diterima;
            if ($pegawai != null) {
              $data = [
                  'no' => $i + 1,
                  'nama_pegawai' => ((empty($pegawai->gd) || $pegawai->gd == '-') ? '' : $pegawai->gd) . ' ' . $pegawai->nama . ' ' . $pegawai->gb,
                  'jenis_cuti' => $v->jenis_cuti,
                  'lama_cuti' => $v->rentang_absen,
                  'tgl_pengajuan' => date('Y-m-d H:i:s', strtotime($v->created_at)),
                  'status' => $status,
                  'file' => '<a href="' . asset('uploads/' . $v->file) . '" target="popup" 
                              onclick="window.open(\'' . asset('uploads/' . $v->file) . '\',\'popup\',\'width=600,height=600\'); return false;">
                              <span style="color:white" class="me-1 badge bg-primary">' . $v->file . '</span></a>',
                  'aksi'=>'<a data-toggle="modal"
                  data-target="#view'.$v->id.'"class="btn btn-primary btn-sm white"><i
                      class="fa fa-file"></i></a><div id="view'.$v->id.'" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title">Ringkasan Pengajuan Cuti</h4>
                                  <button type="button" class="close"
                                      data-dismiss="modal">&times;</button>
                              </div>
                              <form action="' . url('updateaksicuti') . '" method="post">
                              <input type="hidden" name="id" value="'.$v->id.'">
                              <div class="modal-body">
                                  <div style="display: flex;flex-direction:column">
                                      <p>Nama : '.$pegawai->nama.'</p>
                                      <p>NIP : '.$pegawai->nip.'</p>
                                      <p>Jenis Cuti : '.$v->jenis_cuti.'</p>
                                      <p>Alasan : '.$v->alasan.'</p>
                                      <p>Rentang Cuti : '.$v->rentang_absen.'</p>
                                      <p>Tanggal Pengajuan : '.$v->created_at.'</p>
                                      <p>Status Pengajuan : '.$status.'</p>
                                  </div>
                                  
                                  ' . ($status == 'Menunggu Konfirmasi..' ? '
                                 
                                  <select class="form-control" name="aksi">
                                    <option>--PILIH AKSI--</option>
                                    <option value="Y">TERIMA</option>
                                    <option value="N">TOLAK</option>
                                  </select>
                                 ' : '') . '
                              </div>
                              <div class="modal-footer">
                                  <button type="submit" class="btn btn-primary">'.($status == 'Menunggu Konfirmasi..' ? 'Update':'Batalkan').'</button>
                              </div>
                              </form>
                          </div>

                      </div>
                  </div>'
              ];
              array_push($array, $data);
          }
          
      }
      $datajson = [
        "draw"=> 1,
        "recordsTotal"=> count($datacuti),
        "recordsFiltered"=> count($datacuti), 
        "data"=>$array
        ];
        print json_encode($datajson);
     }

      public function pengajuan(Request $r){
        $class       = new Cmenu();
        $listintansi = (object) $class->listinstansi();
        $skpd        = $listintansi;
        $datacuti    = TblCuti::all();
        // @foreach ($datacuti as $i => $v)
        // @php
        //     $pegawai = $class->getpegawaifromiduser($v->id_pegawai);
        //     $diterima = $v->status == 'Y' ? 'Disetujui' : 'Di batalkan';
        //     $status = $v->status == 'A' ? 'Menunggu Konfirmasi..' : $diterima;
        // @endphp
        // @if ($pegawai != null)
          return view('theme.cuti.index',compact('skpd','datacuti'));
      }
      public function save(Request $r){
        $data =[

        ];

        // $act = Jabatan::insert($data);
        // if($act){
        //   return back()->with();
        // }

      }
      public function update(Request $r){
        $data =[

        ];

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