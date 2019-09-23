<style media="screen">
  .bankss tr td {
    font-size: 14px;
    font-weight: bold;
    padding: 5px;
  }
</style>
      <table class="bankss">
        <tr>
          <td>BANK</td>
          <td>: <?=$row->inisial_bank?></td>
        </tr>

        <tr>
          <td>NO. REKENING</td>
          <td>: <?=$row->no_rekening?></td>
        </tr>

        <tr>
          <td>NAMA REKENING</td>
          <td>: <?=strtoupper($row->nama_rekening)?></td>
        </tr>


      </table>
