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
<div class="col-sm-6">
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
        <th>Status</th>
        <td>:
          <?php if ($row->is_active=="1"): ?>
            <span class="badge badge-success badge-pill"> Aktif</span>
            <?php else: ?>
              <?php
                $approved_status = json_decode($row->keterangan_active);
               ?>
              <a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?=$approved_status->desc?> && Approved By : <?=profile_where($approved_status->admin_approved,"nama")?> && Time : <?=date("d/m/y H:i",strtotime($approved_status->approved_time))?>">
                <span class="badge badge-danger badge-pill"> Nonaktif</span>
              </a>
          <?php endif; ?>
        </td>
      </tr>

    </table>
  </div>

  <div class="col-sm-6">
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

      <tr>
        <td colspan="2"><b>Di Referral Oleh</b>
          <p style="font-size:11px;">
            <?php
              $query = $this->db->get_where("trans_person_sponsor",["id_person"=>$row->id_person])->row();
             ?>

             <?php if ($rows = $query): ?>


               <b class="text-info"><?=profile_member($rows->id_person_sponsor,"id_register") ?></b>&nbsp;|&nbsp;
               <?=profile_member($rows->id_person_sponsor,"nama") ?>

               <?php else: ?>
                 Mendaftar melalui form registrasi (Tidak di tambahkan oleh siapa pun)
             <?php endif; ?>
          </p>
        </td>

      </tr>
    </table>
  </div>



  <div class="col-sm-12 mt-5"></div>

  <div class="col-sm-6">

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

  <?php if ($row->keterangan!=null): ?>

  <div class="col-sm-6">
    <?php
      $approved = json_decode($row->keterangan);
     ?>

     <table class="table-detail">
       <tr>
         <th>Admin Approved</th>
         <td>: <?=profile_where($approved->add->admin_approved,"nama")?></td>
       </tr>

       <tr>
         <th>Time Approved</th>
         <td>: <?=date("d/m/Y H:s",strtotime($approved->add->approved_time))?></td>
       </tr>

       <tr>
         <td colspan="2"><b>Description </b> <p style="font-style:italic;font-size:11px;"><?=$approved->add->desc?></p></td>
       </tr>
     </table>
  </div>
    <?php endif; ?>




<div class="col-sm-12 p-5 mt-2">
    <a class="badge badge-warning text-white badge-pill" href="<?=site_url("backend/member/form/personal/".enc_uri($row->id_person)."/$row->id_register")?>"><i class="ti-pencil-alt"></i> Edit Data Personal</a>
</div>
</div>
