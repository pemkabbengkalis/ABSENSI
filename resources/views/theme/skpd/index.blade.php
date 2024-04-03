@extends('theme.Layouts.design')
@section('content')
<?php
use App\Cmenu;
use App\KordinatModel;
$class = new Cmenu();
$datamarker = KordinatModel::where('latitude','!=','')
              ->where('longitude','!=','')
              ->get();
 ?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> Data SKPD</h1>
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
    <div class="card-body">
    <a  data-toggle="modal" data-target="#addData" style="float:right;color:white;" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
    <div id="addData" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Data SKPD</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="{{url('addSkpd')}}" method="post">{{csrf_field()}}
          <div class="modal-body">
            <div class="form-group">
                <label for="">Kode Unit kerja</label>
                <input class="form-control" type="text" name="kode_unitkerja" required placeholder="Input Kode Unit Kerja">
            </div>
            <div class="form-group">
                <label for="">Nama Instansi</label>
                <input class="form-control" type="text" name="namaskpd" required placeholder="Input Nama Instansi">
            </div>
            <div class="form-group">
                <label for="">Kecamatan</label>
                <select name="kecamatan" class="form-control" required>
                  <option value="">--Pilih Kecamatan--</option>
                  @foreach($kecamatan as $i => $v)
                    <option value="{{$v->kecamatan}}">{{$v->kecamatan}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jenis</label>
                <select name="jenis" class="form-control" required>
                  <option value="">--Pilih Jenis--</option>
                    <option value="K">KANTOR</option>
                    <option value="S">SEKOLAH</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Status</label>
                <select name="status" class="form-control" required>
                  <option value="">--Pilih Status--</option>
                    <option value="A">AKTIF</option>
                    <option value="D">TIDAK AKTIF</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                 <textarea name="alamat" id="" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-warning" ><i class="fa fa-undo"></i> Reset</button>
            <button type="submit" class="btn btn-primary" ><i class="fa fa-save"></i> Simpan</button>
          </div>
          </form>
        </div>

      </div>
    </div>
    <h4 class="card-title">Data SKPD</h4>

      <h6 class="card-subtitle"></h6>

      <br>
      
      <div class="table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
          <thead>
            <tr>
            <th>No</th>
            <th>Intansi</th>
            <th>Kecamatan</th>
            <th>Alamat</th>
            <th width="10%"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($listintansi as $index =>$v)
          @if($v['nama_unitkerja'] != '')
          <tr>
          <td>{{$index+1}}</td>
          <td>{{$v['nama_unitkerja']}}</td>
          <td>{{$v['kecamatan']}}</td>
          <td>{{$v['alamat']}}</td>
          <td width="15%">
            <a data-toggle="modal" data-target="#editData{{$v->kode_unitkerja}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
            <div id="editData{{$v->kode_unitkerja}}" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Data SKPD</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="{{url('updateSkpd')}}" method="post">{{csrf_field()}}
          <div class="modal-body">
            <div class="form-group">
                <label for="">Kode Unit kerja</label>
                <input disabled class="form-control" type="text" value="{{$v->kode_unitkerja}}" name="kode_unitkerja" required placeholder="Input Kode Unit Kerja">
                <input class="form-control" type="hidden" value="{{$v->kode_unitkerja}}" name="kode_unitkerja" required placeholder="Input Kode Unit Kerja">
            </div>
            <div class="form-group">
                <label for="">Nama Instansi</label>
                <input class="form-control" type="text" name="namaskpd" value="{{$v->nama_unitkerja}}" required placeholder="Input Nama Instansi">
            </div>
            <div class="form-group">
                <label for="">Kecamatan</label>
                <select name="kecamatan" class="form-control" required>
                  <option value="">--Pilih Kecamatan--</option>
                  @foreach($kecamatan as $i => $k)
                    <option value="{{$v->kecamatan}}" @if($v->kecamatan == $k->kecamatan) selected @endif>{{$v->kecamatan}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jenis</label>
                <select name="jenis" class="form-control" required>
                  <option value="">--Pilih Jenis--</option>
                    <option value="K" @if($v->jenis == 'K') selected @endif>KANTOR</option>
                    <option value="S" @if($v->jenis == 'S') selected @endif>SEKOLAH</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Status</label>
                <select name="status" class="form-control" required>
                  <option value="">--Pilih Status--</option>
                    <option value="A" @if($v->status == 'A') selected @endif>AKTIF</option>
                    <option value="D" @if($v->status == 'D') selected @endif>TIDAK AKTIF</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                 <textarea name="alamat"class="form-control">{{$v->alamat}}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-warning" ><i class="fa fa-undo"></i> Reset</button>
            <button type="submit" class="btn btn-primary" ><i class="fa fa-save"></i> Simpan</button>
          </div>
          </form>
        </div>

      </div>
    </div>
            <a onclick="return confirm('Yakin untuk menghapus?')" href="{{url('hapusSkpd/'.base64_encode($v->kode_unitkerja))}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
            <a style="color:white" data-toggle="modal" data-target="#configData{{$v->kode_unitkerja}}" class="btn btn-sm btn-primary"><i class="fa fa-cog"></i></a>

            <div id="configData{{$v->kode_unitkerja}}" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                      <h4 class="modal-title">Pengaturan SKPD</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                      <div class="container">
  <h4>Perhatian</h4>
  <p>Fitur ini digunakan untuk pengaturan SKPD dimana didalamnya terdapat pengaturan berupa : Jam , Titik Kordinat</p>

  <ul class="nav nav-tabs">
    <!-- <li class="active tab-nav-conf"><a data-toggle="tab" href="#home{{$v->kode_unitkerja}}"><i class="fa fa-map-marker"></i> Titik Kordinat</a></li> -->
    <li class="tab-nav-conf"><a data-toggle="tab" href="#menu1{{$v->kode_unitkerja}}"><i class="fa fa-clock"></i> Jam Kantor</a></li>
    <li onclick="listpegawai({{$v->kode_unitkerja}})" class="tab-nav-conf"><a data-toggle="tab" href="#menu2{{$v->kode_unitkerja}}">Bypass Pegawai</a></li>
  </ul>
 <form action="{{url('skpdSettingCentral')}}" method="post">{{csrf_field()}}
  <input type="hidden" name="idinstansi" value="{{$v->kode_unitkerja}}" required>
  <div class="tab-content">
    <!-- <div id="home{{$v->kode_unitkerja}}" class="tab-pane fade in active">
      <h3 class="title-tab">Kordinat</h3>
        <label>Latitude</label>
          <input type="hidden" name="kode_unitkerja" value="{{$v->kode_unitkerja}}">
          <input type="hidden" name="id_kordinat" value="102">
          <input type="text" class="form-control" name="latitude" value="1.47311">
          <label>Longitude</label>
          <input type="text" class="form-control" name="longitude" value="102.12391">
          <label>Radius</label>
          <input type="number" class="form-control" name="radius" value="50">      
    </div> -->
            <div id="menu1{{$v->kode_unitkerja}}" class="tab-pane fade">
            <h3 class="title-tab">Jam Kantor</h3>
            <?php $hari = array('senin','selasa','rabu','kamis','jumat','sabtu'); ?>
           @foreach($hari as $vhari)
           <?php

           $jammasuk =  Helper::jam($vhari,'Jam Masuk',$v->kode_unitkerja);
           $jampulang = Helper::jam($vhari,'Jam Pulang',$v->kode_unitkerja);

            ?>
              <center><h3>{{ucfirst($vhari)}}</h3></center>
              <div style="
    display: flex;
    justify-content: space-around">
                <div>
                  <h5>Jam Masuk</h5>
                  <label>Jam Absen</label>
                    <div class="form-group">
                      <input type="time" name="jammasuk[]" value="{!!$jammasuk['jam']!!}" class="form-control"  placeholder="Jam Absen">
                    </div>

                    <label>Batas Absen</label>
                    <div class="form-group">
                      <input type="time" name="batasabsenmasuk[]" value="{!!$jammasuk['batas']!!}" class="form-control"  placeholder="Batas Absen">
                    </div>
                </div>
                <div>
                  <h5>Jam Pulang</h5>
                  <label>Jam Absen</label>
                    <div class="form-group">
                      <input type="time" name="jampulang[]" value="{!!$jampulang['jam']!!}" class="form-control"  placeholder="Jam Absen">
                    </div>

                    <label>Batas Absen</label>
                    <div class="form-group">
                      <input type="time" name="batasabsenpulang[]" value="{!!$jampulang['batas']!!}" class="form-control"  placeholder="Batas Absen">
                    </div>
                </div>
              </div>
              <hr>
             
            @endforeach
            </div>
            <div id="menu2{{$v->kode_unitkerja}}" class="tab-pane fade">
            <h3 class="title-tab">Bypass Pegawai</h3>
              <select id="selectByInstansi{{$v->kode_unitkerja}}" style="width:100%" class="form-control select2" multiple=""  name="bypass[]"></select>

            </div>
          </div>
        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                    </div>

                  </div>
            </div>

          </td>
        </tr>
        @endif
         @endforeach
        </tbody>
        </table>
      </div>


    </div>
  </div>
</form>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function listpegawai(unitkerja) {
        $.ajax({
            url: '/select-by-instansi/' + unitkerja,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#selectByInstansi' + unitkerja);
                select.empty();

                // Add default option if needed
                // select.append('<option value="">Select Pegawai</option>');

                // Populate select options
                $.each(data, function(index, pegawai) {
                    select.append('<option value="' + pegawai.id + '" '+pegawai.selected+'>' + pegawai.text + '</option>');
                });

                // Initialize select2 after options are added
                select.select2();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>

</main>
<!-- ============================================================== -->
<!-- End Container fluid  -->
@endsection
