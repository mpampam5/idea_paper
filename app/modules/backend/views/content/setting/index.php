<style media="screen">
  .table-detail{
    border-collapse: collapse;
    width: 100%;
  }
  .table-detail tr th{
    padding: 15px;
    color: #5d5d5d;
    font-size: 14px;
    border: 1px solid #ddd;
    width: 20%;
  }

  .table-detail tr td{
    border: 1px solid #ddd;
    padding: 15px;
    color: #5d5d5d;
    font-size: 14px;
  }

  .table-detail tr td span{
    visibility: hidden;
  }

    .table-detail tr td:hover  span.text-primary{
    visibility: visible;
  }
</style>


<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-black">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/home")?>">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><?=ucfirst($title)?></li>
    <li class="breadcrumb-item active" aria-current="page">Umum</li>
  </ol>
</nav>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Umum </h4>

        <table class="table-detail">
          <tr>
            <th><?=config_all(["config"=>"title_system"],"initial");?></th>
            <td><?=config_all(["config"=>"title_system"],"value");?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>
          
          <tr>
            <th><?=config_all(["config"=>"email"],"initial");?></th>
            <td><?=config_all(["config"=>"email"],"value");?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>

          <tr>
            <th><?=config_all(["config"=>"min-topup"],"initial");?></th>
            <td>Rp.<?=format_rupiah(config_all(["config"=>"min-topup"],"value"));?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>

          <tr>
            <th><?=config_all(["config"=>"max-topup"],"initial");?></th>
            <td>Rp.<?=format_rupiah(config_all(["config"=>"max-topup"],"value"));?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>

          <tr>
            <th><?=config_all(["config"=>"min-withdraw"],"initial");?></th>
            <td>Rp.<?=format_rupiah(config_all(["config"=>"min-withdraw"],"value"));?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>

          <tr>
            <th><?=config_all(["config"=>"max-withdraw"],"initial");?></th>
            <td>Rp.<?=format_rupiah(config_all(["config"=>"max-withdraw"],"value"));?>
              &nbsp;&nbsp;<a href="#" class="act"><span class="text-primary"><i class="ti-pencil-alt"></i></span></a>
            </td>
          </tr>

        </table>
      </div>
    </div>
  </div>
</div>
