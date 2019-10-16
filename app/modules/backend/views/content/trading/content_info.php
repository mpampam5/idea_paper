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
  <h5 class="card-title">Data Trading</h5>
  <hr>
    <table class="table-detail">
      <tr>
        <th>Title</th>
        <td>: <?=$row->title?></td>
      </tr>

      <tr>
        <th>Harga Per Paper</th>
        <td>: Rp.<?=format_rupiah($row->harga_paper)?></td>
      </tr>

      <tr>
        <th>Jumlah Paper</th>
        <td>: <?=$row->jumlah_paper?> paper</td>
      </tr>

      <tr>
        <th>Total Harga paper</th>
        <td>: Rp.<?=format_rupiah($row->harga_paper*$row->jumlah_paper)?></td>
      </tr>


      <tr>
        <th>Masa Kontrak</th>
        <td>: <?=$row->masa_kontrak?> Bulan</td>
      </tr>

      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>

      <tr>
        <th>Jumlah Paper Terbeli</th>
        <td>:
        <?php
              $total_paper_terpakai = $this->db->select("id_trans_person_trading,SUM(jumlah_paper) AS jumlah_paper")
                                               ->get("trans_person_trading")
                                               ->row();
          echo "$total_paper_terpakai->jumlah_paper Paper";
          ?>

        </td>
      </tr>

      <tr>
        <th>Total Investasi</th>
        <td>:
          Rp. <?=format_rupiah($total_paper_terpakai->jumlah_paper*$row->harga_paper)?>
        </td>
      </tr>

      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>

      <tr>
        <td colspan="2">
          <span style="font-weight:bold;">Keterangan :</span>
          <p style="font-size:12px"><?=$row->keterangan?></p>
        </td>
      </tr>
    </table>
  </div>

  <div class="col-sm-12 mt-3 pl-4">
    <a href="<?=site_url("backend/trading/get_form/info")?>" class="badge badge-warning text-white" id="edit_info_trading"> <i class="ti-pencil-alt"></i> Edit</a>
  </div>
</div>
