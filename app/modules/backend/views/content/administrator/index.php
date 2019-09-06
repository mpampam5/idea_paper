<link rel="stylesheet" href="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<script src="<?=base_url()?>_template/back/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/index")?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
    <li class="breadcrumb-item active" aria-current="page"><?=ucfirst($title)?></li>
  </ol>
</nav>


<div class="row">
  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">
          <h4 class="card-title"> <?=ucfirst($title)?> </h4>
          <hr>

          <table id="table" class="table table-bordered">
            <thead class="bg-black text-yell">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Status</th>
                <th class="text-center">#</th>
              </tr>
            </thead>

            <tbody>
              <td>1</td>
              <td>muhammad irfan ibnu</td>
              <td>irfan595</td>
              <td>active</td>
              <td class="text-center">
                <a href="#"><i class="fa fa-file"></i></a>&nbsp;
                <a href="#"><i class="fa fa-pencil"></i></a>&nbsp;
                <a href="#"><i class="fa fa-trash"></i></a>
              </td>
            </tbody>
          </table>

      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $("#table").dataTable();
</script>
