<style media="screen">

  .container-content
  {
    margin: 1px;
    /* border: 1px solid #adadad; */
  }
  .personal-img-detail{
    width: 150px;
    height: 200px;
    /* border: 1px solid #939393; */
    box-shadow: 1px 1px 2px #939393;
    /* background-color: rgba(255, 199, 0, 1); */
    background-position: center;
    background-size: cover;
  }

  .title-detail{
    display: block;
    padding: 10px 0 10px 30px;
    color: #3c3c3c;
    font-size: 14px;
    /* border-bottom: 1px solid #ababab; */
  }
</style>


<h5 class="title-detail border-bottom">Data Personal</h5>
<div class="row container-content border-bottom pb-5">


  <div class="col-sm-7">
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



    </table>
  </div>

  <div class="col-sm-5">
    <table class="table-detail">
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
    </table>
  </div>

  <div class="col-sm-12 mt-5">

    <table class="table-detail" id="lightgallery">
      <tr>
        <td colspan="2"><b style="font-size:14px;">File Berkas :</b>  </td>
      </tr>
      <tr>
        <td>
          <a data-fancybox="gallery" href="http://localhost/idea_paper_dashboard/_template/files/<?=enc_uri($row->id_register)?>/<?=$row->foto?>"><i class="ti-zip"></i> File Foto</a>
        </td>
      </tr>

      <tr>
        <td>
          <a data-fancybox="gallery" href="http://localhost/idea_paper_dashboard/_template/files/<?=enc_uri($row->id_register)?>/<?=$row->file_ktp?>"><i class="ti-zip"></i> File KTP</a>
        </td>
      </tr>

      <tr>
        <td>
          <a data-fancybox="gallery" href="http://localhost/idea_paper_dashboard/_template/files/<?=enc_uri($row->id_register)?>/<?=$row->file_kk?>"><i class="ti-zip"></i> File KK</a>
        </td>
      </tr>
    </table>
  </div>
</div>


<h5 class="title-detail border-bottom">Data Rekening</h5>
<div class="row container-content border-bottom pb-5">
  <div class="col-sm-12">
    <table class="table-detail">
      <tr>
        <th>Nama Rekening</th>
        <td>: <?=strtoupper($row->nama_rekening)?></td>
      </tr>

      <tr>
        <th>No.Rekening</th>
        <td>: <?=$row->no_rekening?></td>
      </tr>

      <tr>
        <th>BANK</th>
        <td>: <?=strtoupper($row->inisial_bank)?></td>
      </tr>

      <tr>
        <th>Kota/Kabupaten Pembukaan Rekening</th>
        <td>: <?=$row->kota_pembukuan?></td>
      </tr>




    </table>
  </div>

</div>


<h5 class="title-detail border-bottom">Data Akun</h5>
<div class="row container-content border-bottom pb-5">
  <div class="col-sm-12">
    <table class="table-detail">
      <tr>
        <th>Username</th>
        <td>: <?=$row->username?></td>
      </tr>

      <tr>
        <th>Tanggal Mendaftar</th>
        <td>: <?=date("d/m/Y H:i",strtotime($row->created))?></td>
      </tr>

      <tr>
        <th>Di Referral oleh</th>
        <td>:
          <?php
            $query = $this->db->get_where("trans_person_sponsor",["id_person"=>$row->id_person])->row();
           ?>

           <?php if ($rows = $query): ?>


             <b class="text-info"><?=profile_member($rows->id_person_sponsor,"id_register") ?></b>&nbsp;|&nbsp;
             <?=profile_member($rows->id_person_sponsor,"nama") ?>

             <?php else: ?>
               Mendaftar melalui form registrasi (Tidak di tambahkan oleh siapa pun)
           <?php endif; ?>
        </td>
      </tr>






    </table>
  </div>

</div>
