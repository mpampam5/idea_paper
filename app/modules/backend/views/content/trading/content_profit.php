<link rel="stylesheet" href="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<script src="<?=base_url()?>_template/back/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<div class="row">
  <div class="col-sm-12">
    <h5 class="card-title">Data Profit</h5>
    <hr>
    <button type="button" class="btn btn-primary ml-3 btn-sm mb-2" id="add"><i class="ti-stats-up"></i> Tambah</button>

    <div id="add_collapse" class="collapse col-12">
      <div class="stretch-card mb-2">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> Tambah Profit </h4>
            <hr>
              <form id="form" action="<?=$action?>" autocomplete="off">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Waktu Pembagian (thn/bln/tgl)</label>
                        <input type="text" class="form-control form-control-sm datepic" id="waktu" name="waktu">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Nominal (Rp)</label>
                        <input type="text" class="form-control form-control-sm rupiah" id="nominal" name="nominal">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Persentase (%)</label>
                        <input type="text" class="form-control form-control-sm persen" readonly id="persen" name="persen">
                      </div>
                    </div>

                  </div>

                  <button type="sumbit" id="submit" name="button" class="btn btn-sm btn-primary">Tambahkan</button>
                  <button type="button" id="hide_collapse" class="btn btn-danger btn-sm">Batal</button>


              </form>

          </div>
        </div>
      </div>
    </div>
  </div>
<?php
// $persentasi = 6/1000;
// echo $persentasi;
// //
// echo "<br>";
//
// // $contoh2 = 60000000*$persentasi;
// $contoh2 = 60000000/1000000000*100;
// echo $contoh2;

// $seluruh_jumlah_paper = get_info_trading("jumlah_paper");
// $persen_paper_member = 2/$seluruh_jumlah_paper;
// echo $persen_paper_member;
 ?>
  <div class="col-sm-12 pl-4 pr-4">
    <table id="table" class="table table-bordered">
      <thead class="bg-black text-yell">
        <tr>
          <th></th>
          <th>Waktu Pembagian</th>
          <th>Persentase (%)</th>
          <th>Nominal (Rp)</th>
          <th>Status</th>
          <th>#</th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(){

    $('.rupiah').mask('0.000.000.000', {reverse: true});

    // $('.persen').mask('000', {reverse: true});

    $("#add").click(function(){
      $("#add_collapse").collapse('toggle');
      $('#form')[0].reset();
      $('.form-group').find('.text-danger').remove();
    });

    $("#hide_collapse").click(function(){
      $("#add_collapse").collapse('hide');
      $('#form')[0].reset();
      $('.form-group').find('.text-danger').remove();
    });



    var currentDate = new Date();

    $(".datepic").datepicker({
      autoclose: true,
      format: 'yyyy/mm/dd'
    }).datepicker("setDate", currentDate);


    $("#nominal").on("keyup",function(){
      var nominal =  $(this).val();
          nom = nominal.split('.').join('');
          persen = (nom/1000000000)*100;

      $("#persen").val(persen);
    });



  });





  $(document).ready(function() {
      var t = $("#table").dataTable({
          oLanguage: {
              sProcessing: "Memuat Data..."
          },
          "searching": false,
          "bLengthChange": false,
          processing: true,
          serverSide: true,
          ajax: {"url": "<?=base_url()?>backend/trading/json_profit", "type": "POST"},
          columns: [
              {
                "data": "id_trading_profit",
                "orderable": false,
                "visible":false,
                searchable: false
              },
              {"data":"time_add"},
              {
                "data":"persentasi",
                render:function(data,type,row,meta)
                 {
                   if (data > 0) {
                     return '<span class="text-success">'+data+'%</span>';
                   }else {
                     return '<span class="text-danger">'+data+'%</span>';
                   }
                 },
               },
              {
                "data":"nominal",
                render:function(data,type,row,meta)
                 {
                   return '<span>Rp.'+data+'</span>';
                 },
              },
              {
                "data":"status_bagi",
                render:function(data,type,row,meta)
                 {
                   if (data=="belum") {
                     return '<span class="badge badge-danger">Belum dibagikan</span>';
                   }else {
                     return '<span class="badge badge-primary">Telah dibagikan</span>';
                   }
                 },
              },
              {
                "data":"status_bagi",
                "orderable": false,
                "className" : "text-center",
                render:function(data,type,row,meta)
                 {
                   if (data=="belum") {
                     return row.action2;
                   }else {
                     return row.action;
                   }
                 },
              },
              {
                "data" : "action",
                "orderable": false,
                "className" : "text-center",
                "visible":false,
              },
              {
                "data" : "action2",
                "orderable": false,
                "className" : "text-center",
                "visible":false,
              },
          ],
          order: [[0, 'desc']],
      });
  });


  $("#form").submit(function(e){
    e.preventDefault();
    var me = $(this);
    $("#submit").prop('disabled',true).html('<div class="spinner-border spinner-border-sm text-white"></div> Processing...');

    $.ajax({
          url             : me.attr('action'),
          type            : 'post',
          data            :  new FormData(this),
          contentType     : false,
          cache           : false,
          dataType        : 'JSON',
          processData     :false,
          success:function(json){
            if (json.success==true) {
              $('.form-group').find('.text-danger').remove();
                $("#add_collapse").collapse('hide');
                $('#form')[0].reset();
                $("#submit").prop('disabled',false)
                            .html('Tambahkan');

                $('#table').DataTable().ajax.reload();
                $.toast({
                  text: json.alert,
                  showHideTransition: 'slide',
                  icon: 'success',
                  loaderBg: '#f96868',
                  position: 'bottom-right'
                });


            }else {
              $("#submit").prop('disabled',false)
                          .html('Tambahkan');
              $.each(json.alert, function(key, value) {
                var element = $('#' + key);
                $(element)
                .closest('.form-group')
                .find('.text-danger').remove();
                $(element).after(value);
              });
            }
          }
    });
  });

  $(document).on("click","#bagikan_dividen",function(e){
    e.preventDefault();
    $('.modal-dialog').removeClass('modal-lg')
                      .removeClass('modal-sm')
                      .addClass('modal-md');
    $("#modalTitle").text('Autentikasi');
    $('#modalContent').load($(this).attr('href'));
    $("#modalGue").modal('show');
  });

  $(document).on("click","#hapus_profit",function(e){
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
              $('#table').DataTable().ajax.reload();
              $.toast({
                text: json.alert,
                showHideTransition: 'slide',
                icon: json.success,
                loaderBg: '#f96868',
                position: 'bottom-right'
              });


            }
          });
  });

</script>
