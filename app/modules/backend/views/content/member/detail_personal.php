<div class="row">
  <div class="col-sm-2">
    <img src="<?=base_url()?>_template/back/images/faces/face1.jpg" style="width:150px;height:150px;" alt="">
  </div>

  <div class="col-sm-10">
    <table class="table-detail">
      <tr>
        <th>No. Registrasi</th>
        <td>: <span class="text-info"><?=$row->id_register?></span></td>
      </tr>

      <tr>
        <th>No. Identitas Kependudukan</th>
        <td>: <?=$row->nik?></td>
      </tr>

      <tr>
        <th>Nama</th>
        <td>: <?=strtoupper($row->nama)?></td>
      </tr>

      <tr>
        <th>Telepon</th>
        <td>: <?=$row->telepon?></td>
      </tr>

      <tr>
        <th>Email</th>
        <td>: <?=$row->email?></td>
      </tr>

      <tr>
        <th>Pekerjaan</th>
        <td>: <?=$row->pekerjaan?></td>
      </tr>

      <tr>
        <th>Jenis Kelamin</th>
        <td>: <?=strtoupper($row->jenis_kelamin)?></td>
      </tr>

      <tr>
        <th>Tempat, Tanggal lahir</th>
        <td>: <?=strtoupper($row->tempat_lahir) .",".date('d-m-Y',strtotime($row->tanggal_lahir))?></td>
      </tr>


      <tr>
        <td></td><td></td>
      </tr><tr>
        <td></td><td></td>
      </tr>


      <tr>
        <td colspan="2">
          <b style="font-size:14px;">Keterangan Alamat </b>
          <p style="font-size:12px;margin-top:5px;">
              <?=strtoupper($row->alamat)?> <br>
              KELURAHAN/DESA : <?=ucfirst(wilayah_indonesia("wil_kelurahan",["id"=>$row->id_kelurahan]))?><br>
              KECAMATAN : <?=ucfirst(wilayah_indonesia("wil_kecamatan",["id"=>$row->id_kecamatan]))?><br>
              KABUPATEN/KOTA : <?=ucfirst(wilayah_indonesia("wil_kabupaten",["id"=>$row->id_kabupaten]))?><br>
              PROVINSI : <?=ucfirst(wilayah_indonesia("wil_provinsi",["id"=>$row->id_provinsi]))?>
            </p>
        </td>
      </tr>


      <tr>
        <td colspan="2"><td>
      </tr>
      <tr>
        <td colspan="2"><td>
      </tr>


      <tr>
        <td colspan="2">
          <a class="badge badge-warning text-white badge-pill" name="button"><i class="ti-pencil"></i> Edit Data Personal</a>
        </td>
      </tr>
    </table>
  </div>
</div>
