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
          <td width="10%">
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
          </td>
        </tr>
        @endif
         @endforeach
        </tbody>
        </table>
      </div>


    </div>
  </div>
</div>
</div>



</main>
<!-- ============================================================== -->
<!-- End Container fluid  -->
@endsection
