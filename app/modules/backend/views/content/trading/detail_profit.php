<style media="screen">
  .table-profit tr th,td{
    font-size: 12px;
    padding: 6px;
  }

  .table-dividen{
    width:100%;
  }

  .table-dividen tr th,td{
    font-size: 12px;
    padding: 6px;
  }
</style>

<table class="table-profit">
  <tr>
    <th>Waktu Pembagian</th>
    <td>: <?=date("d/m/Y",strtotime($row->time_add))?></td>
  </tr>
  <tr>
    <th>Persentase (%)</th>
    <td>: <?=$row->persentasi?> %</td>
  </tr>
  <tr>
    <th>Nominal (Rp)</th>
    <td>: Rp.<?=format_rupiah($row->nominal)?></td>
  </tr>
</table>

<hr>

<table class="table-dividen table-bordered" id="table-dividen">
    <thead class="bg-black text-yell">
      <tr>
        <th>ID.REG & NAMA</th>
        <th class="text-center">PAPER TERHITUNG</th>
        <th>DIVIDEN</th>
      </tr>
    </thead>

    <?php
      $dividen = $this->db->select("trading_dividen.id_trading_dividen,
                                  trading_dividen.id_trading_profit,
                                  trading_dividen.id_person,
                                  trading_dividen.jumlah_paper,
                                  trading_dividen.persentase,
                                  trading_dividen.dividen,
                                  tb_person.id_register,
                                  tb_person.nama")
                        ->from("trading_dividen")
                        ->join("tb_person","tb_person.id_person = trading_dividen.id_person")
                        ->where("id_trading_profit",$row->id_trading_profit)
                        ->order_by("id_register","ASC")
                        ->get();

      $dividen_row = $this->db->select("trading_dividen.id_trading_dividen,
                                        trading_dividen.id_trading_profit,
                                        trading_dividen.id_person,
                                        SUM(trading_dividen.jumlah_paper) AS jumlah_paper,
                                        SUM(trading_dividen.dividen) AS dividen")
                              ->from("trading_dividen")
                              ->where("id_trading_profit",$row->id_trading_profit)
                              ->get()
                              ->row();

     ?>

    <tbody>
      <?php foreach ($dividen->result() as $dividen): ?>
      <tr>
        <td class="text-info">
          <?=$dividen->id_register;?><br>
          <?=$dividen->nama;?>
        </td>
        <td class="text-center"><?=$dividen->jumlah_paper;?></td>
        <td>
          Rp.<?=format_rupiah($dividen->dividen);?> &nbsp; (<?=$dividen->persentase;?>%)
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>

    <tfoot class="bg-black text-yell">
        <tr>
          <th class="text-center">Total</th>
          <th class="text-center"><?=$dividen_row->jumlah_paper?></th>
          <th>Rp.<?=format_rupiah($dividen_row->dividen)?></th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
  $("#table-dividen").dataTable({
    "ordering": false,
    "info": false,
    "bLengthChange": false,
    "searching": false
  });
</script>
