<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-black">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/home")?>">Dashboard</a></li>
    <li class="breadcrumb-item active"><?=ucfirst($title)?></li>
  </ol>
</nav>


<div class="row">
  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">
          <h4 class="card-title"><?=ucfirst($title)?></h4>
          <hr>

          <div class="row">


              <div class="col-md-2 bg-white profile-menu-bar border-right">
                <div class="menu-bar">
                  <ul class="menu-items">
                    <li><a href="<?=site_url("backend/trading/get/info")?>" <?=$this->uri->segment(4)=="info" ? 'class="active"':''?>><i class="ti-file"></i> Data Trading</a></li>
                    <li><a href="<?=site_url("backend/trading/get/profit")?>" <?=$this->uri->segment(4)=="profit" ? 'class="active"':''?>><i class="ti-bar-chart"></i> Data Profit</a></li>
                    <li><a href="<?=site_url("backend/trading/get/investor")?>" <?=$this->uri->segment(4)=="investor" ? 'class="active"':''?>><i class="ti-user"></i> Data Investor</a></li>
                    <!-- <li><a href="<?=site_url("backend/trading/get/info")?>" <?=$this->uri->segment(4)=="info" ? 'class="active"':''?>><i class="ti-receipt"></i> History Pembelian</a></li> -->
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
