<form class="" action="<?=$action?>" id="form_bagikan">
  <p style="font-size:12px;">Peringatan! Setalah melakukan pembagian dividen, data tidak dapat di hapus. pastikan data yang anda masukkan sudah valid.</p>
  <ul style="font-size:12px;">
    <li>Waktu <?=$tgl_bagi?></li>
    <li>Persentase <?=$persentasi?>%</li>
    <li>Nominal Rp.<?=format_rupiah($nominal)?></li>
  </ul>

  <p style="font-size:12px;">Masukkan password untuk memastikan bahwa anda telah setuju membagikan dividen kepada investor.</p>

  <div class="form-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" autocomplete="off">
  </div>


  <button type='button' class='btn btn-secondary text-white btn-sm' data-dismiss='modal'><i class="ti-na"></i> Cancel</button>
  <button type="submit" id="submit_bagikan" class="btn btn-primary btn-sm" name="button"><i class="ti-check-box"></i> Bagikan Dividen</button>

</form>


<script type="text/javascript">
$("#form_bagikan").submit(function(e){
  e.preventDefault();
  var me = $(this);
  $("#submit_bagikan").prop('disabled',true).html('<div class="spinner-border spinner-border-sm text-white"></div> Memproses...');

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
              $('#table').DataTable().ajax.reload();

              $.toast({
                text: json.alert,
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'bottom-right'
              });


          }else {
            $("#submit_bagikan").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Bagikan Dividen');
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
