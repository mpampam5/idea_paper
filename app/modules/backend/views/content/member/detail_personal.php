<style media="screen">
  .personal-img-detail{
    width: 150px;
    height: 200px;
    /* border: 1px solid #939393; */
    box-shadow: 1px 1px 2px #939393;
    /* background-color: rgba(255, 199, 0, 1); */
    background-position: center;
    background-size: cover;
  }
</style>

<div class="row">
  <div class="col-sm-2">
    <div class="personal-img-detail" style="background-image:url('<?=base_url()?>_template/back/images/default/<?=$row->jenis_kelamin=='' ? 'pria':$row->jenis_kelamin?>.png')"></div>
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
        <td>:
          <?php if ($row->tanggal_lahir!=""): ?>
            <?=strtoupper($row->tempat_lahir) .", ".date('d-m-Y',strtotime($row->tanggal_lahir))?>
          <?php endif; ?>
        </td>
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
          <a class="badge badge-warning text-white badge-pill" href="<?=site_url("backend/member/form/personal/".enc_uri($row->id_person)."/$row->id_register")?>"><i class="ti-pencil-alt"></i> Edit Data Personal</a>
        </td>
      </tr>
    </table>
  </div>
</div>
