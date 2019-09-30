<div class="row">
  <div class="col-md-12">
    <table class="table-detail">
      <tr>
        <th>Username</th>
        <td>: <?=$row->username?></td>
      </tr>

      <tr>
        <th>Password</th>
        <td>: ******</td>
      </tr>


      <tr>
        <th>Last Login</th>
        <td>: <i class="ti-calendar"></i> 10/11/2019 03:14</td>
      </tr>


      <tr>
        <td colspan="2"><td>
      </tr>
      <tr>
        <td colspan="2"><td>
      </tr>


      <tr>
        <td colspan="2">
          <a href="<?=site_url("backend/member/form/account/".enc_uri($row->id_person)."/$row->id_register")?>" class="badge badge-warning text-white badge-pill" name="button"><i class="ti-key"></i> Reset Password</a>
        </td>
      </tr>
    </table>
  </div>

</div>
