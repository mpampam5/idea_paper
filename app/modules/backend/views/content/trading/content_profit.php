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
                        <label for="">Persentasi (%)</label>
                        <input type="text" class="form-control form-control-sm persen" id="persen" name="persen">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Nominal (Rp)</label>
                        <input type="text" class="form-control form-control-sm rupiah" id="nominal" name="nominal">
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
// $persentasi = round(600000000/1000000000 * 100,2);
// echo $persentasi;
//
// echo "<br>";
//
// $contoh2 = 6/100*60000000;
// echo $contoh2;


 ?>
  <div class="col-sm-12 pl-4 pr-4">
    <table id="table" class="table table-bordered">
      <thead class="bg-black text-yell">
        <tr>
          <th></th>
          <th>Waktu Pembagian</th>
          <th>Persentasi (%)</th>
          <th>Nominal (Rp)</th>
          <th>#</th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(){

    $('.rupiah').mask('0.000.000.000', {reverse: true});

    $('.persen').mask('00', {reverse: true});

    $("#add").click(function(){
      $("#add_collapse").collapse('toggle');
      $('#form')[0].reset();
    });

    $("#hide_collapse").click(function(){
      $("#add_collapse").collapse('hide');
      $('#form')[0].reset();
    });



    var currentDate = new Date();

    $(".datepic").datepicker({
      autoclose: true,
      format: 'yyyy/mm/dd'
    }).datepicker("setDate", currentDate);


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
                   return '<span>'+data+'%</span>';
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
                "data" : "action",
                "orderable": false,
                "visible":false,
                "className" : "text-center"
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
</script>
