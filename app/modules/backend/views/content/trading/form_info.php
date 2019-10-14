

<div class="row">
  <div class="col-sm-12">
    <h5 class="card-title">Form Update Data Trading</h5>
    <hr>
    <form class="" action="<?=$action?>"id="form" autocomplete="off">
      <div class="form-group">
        <label id="title">Title</label>
        <input type="text" class="form-control" name="title" placeholder="title" value="<?=$row->title?>">
      </div>

      <div class="form-group">
        <label id="harga_paper">Harga Per Paper (Rp)</label>
        <input type="text" class="form-control" name="harga_paper" id="harga" placeholder="Harga Paper" value="<?=$row->harga_paper?>">
      </div>

      <div class="form-group">
        <label id="jumlah_paper">Jumlah Paper</label>
        <input type="text" class="form-control" name="jumlah_paper" id="jumlah" placeholder="Jumlah Paper" value="<?=$row->jumlah_paper?>">
      </div>

      <div class="form-group">
        <label>Total Harga Paper (Rp)</label>
        <input type="text" class="form-control" id="total" readonly placeholder="Total Harga Paper" value="Rp.<?=format_rupiah($row->harga_paper*$row->jumlah_paper)?>">
      </div>

      <a href="<?=site_url("backend/trading/get/info")?>" class="btn btn-secondary btn-sm text-white"><i class="ti-na"></i> Batal</a>
      <button type="submit" id="submit" class="btn btn-primary btn-sm" name="button"><i class="ti-check-box"></i> Update</button>
    </form>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
      $("#jumlah , #harga").keyup(function(){
        var harga  = parseInt($("#harga").val());
        var jumlah  = parseInt($("#jumlah").val());
        var total = (harga*jumlah);
        $("#total").val("Rp. "+parseInt(total).toLocaleString());
      });


  });

$("#form").submit(function(e){
  e.preventDefault();
  var me = $(this);
  $("#submit").prop('disabled',true).html('<div class="spinner-border spinner-border-sm text-white"></div> Memproses...');

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
              $("#modalGue").modal('hide');
              $('.form-group').removeClass('.has-error')
                              .removeClass('.has');
              $.toast({
                text: json.alert,
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'bottom-right',
                afterHidden: function () {
                    window.location.href="<?=site_url("backend/trading/get/info")?>";
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Update');
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
