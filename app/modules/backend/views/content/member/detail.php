<style media="screen">
  .table-detail tr th{
    padding: 5px;
    color: #5d5d5d;
    font-size: 14px;
  }

  .table-detail tr td{
    padding: 5px;
    color: #5d5d5d;
    font-size: 14px;
  }
</style>




<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-black">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/home")?>">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><?=ucfirst($title)?></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>


<div class="row">
  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">
          <h4 class="card-title">Detail <?=ucfirst($title)?> #<?=$row->id_register?></h4>
          <hr>

          <div class="row">
            <div class="col-md-2">
              <img src="<?=base_url()?>_template/back/images/faces/face1.jpg" style="width:150px;height:150px;" alt="">
            </div>

            <div class="col-md-5">
              <table class="table-detail">
                <tr>
                  <th>No. Registrasi</th>
                  <td>: <?=$row->id_register?></td>
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
                  <td>: <?=$row->tempat_lahir .",".date('d-m-Y',strtotime($row->tanggal_lahir))?></td>
                </tr>

                <tr>
                  <td colspan="2">
                    <b>Keterangan Alamat :</b>
                    <p style="font-size:12px;"><?=strtoupper($row->alamat)?> <br>
                        KELURAHAN/DESA : <?=ucfirst(wilayah_indonesia("wil_kelurahan",["id"=>$row->id_kelurahan]))?><br>
                        KECAMATAN : <?=ucfirst(wilayah_indonesia("wil_kecamatan",["id"=>$row->id_kecamatan]))?><br>
                        KABUPATEN/KOTA : <?=ucfirst(wilayah_indonesia("wil_kabupaten",["id"=>$row->id_kabupaten]))?><br>
                        PROVINSI : <?=ucfirst(wilayah_indonesia("wil_provinsi",["id"=>$row->id_provinsi]))?>
                      </p>
                  </td>
                </tr>
              </table>
            </div>

            <div class="col-md-5">
              <table class="table-detail">
                <tr>
                  <th>Username</th>
                  <td>: <?=$row->username?></td>
                </tr>

                <tr>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <th>Nama Rekening</th>
                  <td>: <?=$row->nama_rekening?></td>
                </tr>

                <tr>
                  <th>No. Rekening</th>
                  <td>: <?=$row->no_rekening?></td>
                </tr>

                <tr>
                  <th>Bank</th>
                  <td>: <?=$row->inisial_bank?></td>
                </tr>

                <tr>
                  <th>Kota Pembukaan Rekening</th>
                  <td>: <?=$row->kota_pembukuan?></td>
                </tr>
              </table>
            </div>

          </div>



      </div>
    </div>
  </div>
</div>
