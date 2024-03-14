<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstansiModel extends Model
{
  protected $table = 'data_unitkerja';
  public $timestamps = false;
  protected $fillable = ['kecamatan', 'kode_unitkerja', 'nama_unitkerja','status','jenis','alamat'];
}
