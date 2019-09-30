<style media="screen">
  .table-detail{
    margin-left: 30px;
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


              <div class="col-md-2 bg-white profile-menu-bar border-right">
                <div class="menu-bar">
                  <ul class="menu-items">
                    <li ><a href="<?=site_url("backend/member/detail/personal/".enc_uri($id_person)."/$id_register")?>" <?=$this->uri->segment(4)=="personal" ? 'class="active"':''?>><i class="ti-user"></i> Data Personal</a></li>
                    <li><a href="<?=site_url("backend/member/detail/rekening/".enc_uri($id_person)."/$id_register")?>" <?=$this->uri->segment(4)=="rekening" ? 'class="active"':''?>><i class="ti-credit-card"></i> Data Rekening</a></li>
                    <li><a href="<?=site_url("backend/member/detail/account/".enc_uri($id_person)."/$id_register")?>" <?=$this->uri->segment(4)=="account" ? 'class="active"':''?>><i class="ti-lock"></i> Data Akun</a></li>
                    <li><a href="<?=site_url("backend/member/form/delete/".enc_uri($id_person)."/$id_register")?>" <?=$this->uri->segment(4)=="delete" ? 'class="active"':''?>><i class="ti-trash"></i> Hapus Akun</a></li>
                  </ul>
                </div>
              </div>






            <div class="col-md-10">
              <?=$content_view?>
            </div>



          </div>



      </div>
    </div>
  </div>
</div>
