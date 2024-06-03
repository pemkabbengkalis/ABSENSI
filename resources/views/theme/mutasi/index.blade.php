@extends('theme.Layouts.design')
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i>FITUR MUTASI</h1>
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
          <a data-toggle="modal" href="#tambah" data-target="#tambah" style="color:white;"
            class="btn waves-effect waves-light btn-primary pull-right"> <i class="fa fa-plus"></i> Tambah Data</a>
          <!--<a style="float: right;" class="btn btn-warning"><i class="fa fa-file-excel-o"></i> Import Data </a>
          --><h4 class="card-title">Daftar dan status Mutasi</h4>
        </div>
        <div class="card-body">


          <h6 class="card-subtitle"></h6>

          <br>
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Asal Instansi</th>
                  <th>Pindah ke Instansi</th>
                  <th>Waktu Permintaan</th>
                  <th>status</th>
                  <th>Catatan</th>
              </thead>
              <tbody>
                @foreach($data as $i => $v)
                @php
                    $gd = ($v->gd !='-') ? $v->gd : '';
                    $gb = ($v->gb !='-') ? $v->gb : '';
                    $color = ($v->status =='PENDING') ? 'btn-primary':'btn-success'; 
                    $color = ($color =='btn-success' AND $v->status =='DONE') ? 'btn-success':$color; 
                    $color = ($color =='btn-success' AND $v->status =='ERROR') ? 'btn-danger':$color; 
                @endphp
                  <tr>
                    <td>{{$i+1}}</td>
                    <td>{{$gd.' '.$v->nama.' '.$gb}}</td>
                    <td>{{Helper::nameSkpd($v->asal_instansi)->nama_unitkerja}}</td>
                    <td>{{Helper::nameSkpd($v->pindah_instansi)->nama_unitkerja}}</td>
                    <td>{{$v->created_at}}</td>
                    <td><button class="btn btn-sm {{$color}}">{{$v->status}}</button></td>
                    <td>{{$v->catatan}}</td>
                  </tr>
                @endforeach
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
          <h4 class="modal-title">Tambah Daftar Mutasi</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>

        <div class="modal-body">
          <form action="{{url('mutasi/save')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Nama Pegawai</label>
              <select style="width:100%" name="pegawai_id" class="form-control select2" id="" required>
              @foreach($pegawai as $i => $v)
                <option value="{{$v->id}}">{{$v->nip}} - {{$v->nama}}</option>
              @endforeach 
              </select>
            </div>
            <div class="form-group">
              <label>Pindah KE ?</label>
              <select style="width:100%" name="pindah_instansi" class="form-control select2" id="" required>
              @foreach($opd as $i => $v)
                <option value="{{$v->kode_unitkerja}}">{{$v->kode_unitkerja}} - {{$v->nama_unitkerja}}</option>
              @endforeach 
              </select>
            </div>

            <div class="form-group">
              <label>Catatan</label>
              <input type="text" class="form-control" name="catatan" value="">
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