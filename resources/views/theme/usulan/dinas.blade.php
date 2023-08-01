@extends('theme.Layouts.design')
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-list"></i> Daftar Usulan Dinas</h1>
      <p></p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    </ul>
  </div>
  @include('theme.Layouts.alert')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
         <h4 class="card-title">Daftar Usulan Dinas</h4>
        </div>
        <div class="card-body">


          <h6 class="card-subtitle"></h6>

          <br>
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Pegawai</th>
                  <th>Mulai Dinas</th>
                  <th>Akhir Dinas</th>
                  <th>Tgl Pengajuan</th>
                  <th>Status</th>
                  <th>File</th>
                  <th width="10%"></th>
              </thead>
              <tbody>
               
              </tbody>
            </table>
          </div>
        </div>
      </div>


    </div>
  </div>
  </div>
  <div class="modal fade" id="tambah" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Pegawai</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>

        <div class="modal-body">
          <form action="{{ url('addpegawai') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Foto</label>
              <br>
              <center>
                <image id="preview" src="{{ asset('noimage.png') }}"
                  style="width:200px;height:220px;">
              </center>
              <br>
              <br>
              <input type="hidden" name="logold" value="">
              <input type="file" name="file" id="file" onchange="tampilkanPreview(this,'preview')">
              <script>
                function tampilkanPreview(gambar, idpreview) {
                  //membuat objek gambar
                  var gb = gambar.files;
                  //loop untuk merender gambar
                  for (var i = 0; i < gb.length; i++) {
                    //bikin variabel
                    var gbPreview = gb[i];
                    var imageType = /image.*/;
                    var preview = document.getElementById(idpreview);
                    var reader = new FileReader();
                    if (gbPreview.type.match(imageType)) {
                      //jika tipe data sesuai
                      preview.file = gbPreview;
                      reader.onload = (function (element) {
                        return function (e) {
                          element.src = e.target.result;
                        };
                      })(preview);
                      //membaca data URL gambar
                      reader.readAsDataURL(gbPreview);
                    } else {
                      //jika tipe data tidak sesuai
                      alert("Type file tidak sesuai. Khusus image.");
                      document.getElementById("file").value = "";
                    }
                  }
                }
              </script>
              <br>
            </div>

            <div class="form-group">
              <label>NIP</label>
              <input type="text" class="form-control" name="nip" value="">
            </div>
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama" value="">
            </div>

            <div class="form-group">
              <label>Gelar Depan</label>
              <input type="text" class="form-control" name="gd" value="">
            </div>
            <div class="form-group">
              <label>Gelar Belakang</label>
              <input type="text" class="form-control" name="gb" value="">
            </div>
            <div class="form-group">
              <label>NO HP</label>
              <input type="text" class="form-control" name="nohp" value="">
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="text" class="form-control" name="email" value="">
            </div>

            <ul class="list-group">
              <button class="btn btn-primary" type="submit">Simpan</button>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </div>



</main>
<!-- ============================================================== -->
<!-- End Container fluid  -->
@endsection