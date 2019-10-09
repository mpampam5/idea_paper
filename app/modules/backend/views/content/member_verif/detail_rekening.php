<div class="row">
  <div class="col-md-12">
    <table class="table-detail">

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


      <tr>
        <td colspan="2"><td>
      </tr>
      <tr>
        <td colspan="2"><td>
      </tr>


      <tr>
        <td colspan="2">
          <a href="<?=site_url("backend/member/form/rekening/".enc_uri($row->id_person)."/$row->id_register")?>" class="badge badge-warning text-white badge-pill" name="button"><i class="ti-pencil"></i> Edit Data Rekening</a>
        </td>
      </tr>

    </table>
  </div>

</div>
