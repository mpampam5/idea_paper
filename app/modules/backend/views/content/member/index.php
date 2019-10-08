<link rel="stylesheet" href="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<script src="<?=base_url()?>_template/back/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<style media="screen">
  div.dataTables_wrapper div.dataTables_filter{
    visibility: hidden;
  }
</style>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-black">
    <li class="breadcrumb-item"><a href="<?=site_url("backend/home")?>">Dashboard</a></li>
    <li class="breadcrumb-item"><?=ucfirst($title)?></li>
    <li class="breadcrumb-item active" aria-current="page">Approved</li>
  </ol>
</nav>

  <div class="row">
    <div class="col-12 mb-2">
      <!-- <a href="<?=site_url('backend/administrator/add')?>" class="btn btn-sm btn-success" id="add"><i class="fa fa-file"></i> Add</a> -->
      <button type="button" class="btn btn-primary btn-sm" id="search"><i class="fa fa-search"></i> Search</button>
      <button type="button" class="btn btn-warning btn-sm text-white" id="reload_table"><i class="fa fa-refresh"></i> Reload</button>
    </div>


    <div id="search_collapse" class="collapse col-12">
      <div class="stretch-card mb-2">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> Search Filter </h4>
            <hr>
              <form id="form-filter" autocomplete="off">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="">ID.REG</label>
                        <input type="text" class="form-control form-control-sm" id="id_register">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control form-control-sm" id="nama">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="">telepon</label>
                        <input type="text" class="form-control form-control-sm" id="telepon">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control form-control-sm" id="email">
                      </div>
                    </div>



                  </div>

                  <button type="button" id="btn-filter" name="button" class="btn btn-sm btn-primary">Filter Search</button>
                  <button type="button" id="hide_collapse" class="btn btn-danger btn-sm">Cancel</button>


              </form>

          </div>
        </div>
      </div>
    </div>



  </div>



<div class="row">
  <div class="col-12 stretch-card">

    <div class="card">
      <div class="card-body">
          <h4 class="card-title"> <?=ucfirst($title)?> </h4>
          <hr>

          <table id="table" class="table table-bordered">
            <thead class="bg-black text-yell">
              <tr>
                <th>NO</th>
                <th>ID.REG</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                <th>TELEPON</th>
                <th>STATUS</th>
                <th class="text-center">#</th>
              </tr>
            </thead>

          </table>

      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
  var table;
  //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('backend/member/json')?>",
            "type": "POST",
            "data": function ( data ) {
                data.id_register = $('#id_register').val();
                data.nama = $('#nama').val();
                data.email = $('#email').val();
                data.telepon = $('#telepon').val();
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "className": "text-center",
            "targets": 0, //first column / numbering column
            "orderable": false,
        },
        {
            "className": "text-center",
            "targets": 4,
            "orderable": false,
        },

        {
            "className": "text-center",
            "targets": 5
        },

        {
            "className": "text-center",
            "orderable": false,
            "targets": 6
        }
        ],
      });

    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });

    $("#reload_table").click(function(){
      $('#form-filter')[0].reset();
      $("#search_collapse").collapse('hide');
        table.ajax.reload();
    });

    $("#search").click(function(){
      $("#search_collapse").collapse('toggle');
      $('#form-filter')[0].reset();
    });

    $("#hide_collapse").click(function(){
      $("#search_collapse").collapse('hide');
      $('#form-filter')[0].reset();
    });


});

$(document).on("click","#rst_pwd",function(e){
  e.preventDefault();
  $('.modal-dialog').removeClass('modal-lg')
                    .removeClass('modal-sm')
                    .addClass('modal-md');
  $("#modalTitle").text('Form Reset Password');
  $('#modalContent').load($(this).attr('href'));
  $("#modalGue").modal('show');
});

$(document).on("click","#delete",function(e){
  e.preventDefault();
  $('.modal-dialog').removeClass('modal-lg')
                    .removeClass('modal-md')
                    .addClass('modal-sm');
  $("#modalTitle").text('Please Confirm');
  $('#modalContent').html(`<p>Are you sure you want to delete?</p>`);
  $('#modalFooter').addClass('modal-footer').html(`<button type='button' class='btn btn-light btn-sm' data-dismiss='modal'>Cancel</button>
                          <button type='button' class='btn btn-primary btn-sm' id='ya-hapus' data-id=`+$(this).attr('alt')+`  data-url=`+$(this).attr('href')+`>Yes, i'm sure</button>
                        `);
  $("#modalGue").modal('show');
});

$(document).on('click','#ya-hapus',function(e){
  $(this).prop('disabled',true)
          .text('Processing...');
  $.ajax({
          url:$(this).data('url'),
          type:'post',
          cache:false,
          dataType:'json',
          success:function(json){
            $('#modalGue').modal('hide');
            $.toast({
              text: json.alert,
              showHideTransition: 'slide',
              icon: json.success,
              loaderBg: '#f96868',
              position: 'bottom-right',
              afterHidden: function () {
                  $('#table').DataTable().ajax.reload();
              }
            });


          }
        });
});

</script>
