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
    <li class="breadcrumb-item" aria-current="page"><?=$title_slug?></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>


<div class="row">
  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">
          <h4 class="card-title">Detail <?=ucfirst($title)?> #<?=$rows->kode_transaksi?></h4>
          <hr>

          <div class="row">
            <div class="col-sm-8">
              <table class="table-detail">

                <tr>
                  <th>KODE TRANSAKSI</th>
                  <td>: <b class="text-info"><?=$rows->kode_transaksi?></b></td>
                </tr>

                <tr>
                  <th>WAKTU TRANSAKSI</th>
                  <td>: <?=date('d/m/Y H:i',strtotime($rows->created))?></td>
                </tr>

                <tr>
                  <th>JUMLAH</th>
                  <td>: Rp.<?=format_rupiah($rows->nominal)?></td>
                </tr>

                <tr>
                  <th>STATUS</th>
                  <td>:
                    <?php if ($rows->status=="proses"): ?>
                      <span class="text-danger">MENUNGGU VERIFIKASI</span>
                    <?php elseif($rows->status=="success"): ?>
                      <span class="text-success">APPROVED</span>
                    <?php endif; ?>
                  </td>
                </tr>

                <tr>
                  <th colspan="2"></th>
                </tr>
                <tr>
                  <th colspan="2"></th>
                </tr>
                <tr>
                  <th colspan="2"></th>
                </tr>




                <tr>
                  <th>ID.REG</th>
                  <td>: <span class="text-info"><?=$rows->id_register?></span></td>
                </tr>

                <tr>
                  <th>NAMA MEMBER</th>
                  <td>: <?=strtoupper($rows->nama)?></td>
                </tr>

                <tr>
                  <th>PEMBAYARAN KE</th>
                  <td>: BANK <?=$rows->inisial_bank?> </td>
                </tr>

                <tr>
                  <th>NAMA REKENING</th>
                  <td>: <?=strtoupper($rows->nama_rekening)?></td>
                </tr>

                <tr>
                  <th>NO REKENING</th>
                  <td>: <?=$rows->no_rekening?></td>
                </tr>
              </table>


            </div>


            <?php if ($rows->status=="success"): ?>
              <div class="col-sm-4">
                <table style="position:absolute;top:0;right:20px;font-size:12px;color:#494949">
                  <tr>
                    <td>APPROVED BY</td>
                    <td>: <?=strtoupper($rows->nama_admin)?></td>
                  </tr>

                  <tr>
                    <td>APPROVED TIME</td>
                    <td>: <?=date("d/m/Y h:i",strtotime($rows->time_approved))?></td>
                  </tr>
                </table>
              </div>
            <?php endif; ?>

            <div class="col-sm-12 mt-5">
              <?php if ($rows->status=="success"): ?>
                <a href="<?=site_url("backend/topup/approved")?>" class="btn btn-sm btn-secondary text-white"> <i class="fa fa-arrow-left"></i> Back</a>
              <?php elseif($rows->status=="proses"): ?>
                <a href="<?=site_url("backend/topup/pending")?>" class="btn btn-sm btn-secondary text-white"> <i class="fa fa-arrow-left"></i> Back</a>
              <?php endif; ?>
              <?php if ($rows->status=="proses"): ?>
              <a href="#" class="btn btn-sm btn-success"> <i class="fa fa-check"></i> Approved</a>
              <?php endif; ?>
            </div>
          </div>



      </div>
    </div>
  </div>
</div>
