<style media="screen">
  .table-detail{
    margin-left: 10px;
  }
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

<div class="row">
  <div class="col-sm-12">
    <h5 class="card-title border-bottom pb-1">Detail data Investor #<?=$row->id_register?></h5>
  </div>

  <div class="col-sm-12">
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
          <th>Status</th>
          <td>:
            <?php if ($row->is_active=="1"): ?>
              <span class="badge badge-success badge-pill"> Aktif</span>
              <?php else: ?>
              <span class="badge badge-danger badge-pill"> Nonaktif</span>
            <?php endif; ?>
          </td>
        </tr>


        <tr>
          <td colspan="2"></td>
        </tr>

        <tr>
          <td colspan="2">
            <a target="_blank" href="<?=site_url("backend/member/detail/personal/".enc_uri($row->id_person)."/$row->id_register")?>" class="badge badge-primary"><i class="ti-file"></i> lihat data lengkap</a>
          </td>
        </tr>
      </table>
    </div>


    <div class="col-sm-12 mt-5">
      <h5 class="card-title border-bottom pb-1"> History Pembelian Paper</h5>
    </div>

    <div class="col-sm-12">
      <?php
      $total = $this->db->select("id_trans_person_trading,kode_transaksi,id_person,SUM(jumlah_paper) AS jumlah_paper,SUM(total_harga_paper) AS total_harga_paper,created")
                                          ->from("trans_person_trading")
                                          ->where("id_person",$row->id_person)
                                          ->get()
                                          ->row();
        $no = 1;
        $history_pembelian_paper = $this->db->select("id_trans_person_trading,kode_transaksi,id_person,jumlah_paper,total_harga_paper,created")
                                            ->from("trans_person_trading")
                                            ->where("id_person",$row->id_person)
                                            ->get();
       ?>

       <table class="table table-striped">
         <thead class="bg-black text-yell">
           <tr>
             <th class="text-center">No</th>
             <th>Waktu</th>
             <th>Kode Transaksi</th>
             <th class="text-center">Jumlah</th>
             <th>Harga Paper (Rp)</th>
           </tr>
         </thead>

         <tbody>
           <?php foreach ($history_pembelian_paper->result() as $h_p): ?>
             <tr>
               <td class="text-center"><?=$no++?></td>
               <td><?=date("d/m/Y H:i",strtotime($h_p->created))?></td>
               <td><span class="text-primary"> <?=$h_p->kode_transaksi?></span></td>
               <td class="text-center"><?=$h_p->jumlah_paper?></td>
               <td>Rp.<?=format_rupiah($h_p->total_harga_paper)?></td>
             </tr>
           <?php endforeach; ?>

           <tr class="bg-black text-yell">
             <td colspan="3"></td>
             <td class="text-center"><p style="font-weight:bold">Total Paper</p><?=$total->jumlah_paper?></td>
             <td><p style="font-weight:bold">Total Investasi (Rp)</p>Rp.<?=format_rupiah($total->total_harga_paper)?></td>
           </tr>
         </tbody>
       </table>
    </div>

    <div class="col-sm-12 mt-5">
      <h5 class="card-title border-bottom pb-1"> History Dividen</h5>
    </div>

    <div class="col-sm-12">
      <table class="table table-bordered">
        <thead class="bg-black text-yell">
          <tr>
            <th>No</th>
            <th>Waktu</th>
            <th>Dividen</th>
            <th>Nominal (Rp)</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>1</td>
            <td>10/11/2019 12:59</td>
            <td><span class="text-danger">-5%</span></td>
            <td>Rp.0</td>
          </tr>

          <tr>
            <td>2</td>
            <td>10/10/2019 11:10</td>
            <td><span class="text-success">+5%</span></td>
            <td>Rp.500.000</td>
          </tr>
        </tbody>
      </table>
    </div>


</div>
