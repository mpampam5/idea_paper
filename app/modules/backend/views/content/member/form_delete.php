<div class="row">
  <div class="col-sm-12">
    <form  action="<?=$action?>" id="form">
      <div class="form-group">
        <label id="keterangan">Keterangan/alasan menghapus akun member</label>
        <textarea class="form-control" name="keterangan" rows="4" cols="80" placeholder="Masukkan keterangan/alasan penghapusan"></textarea>
      </div>

      <div class="form-group">
        <label id="password">Masukkan Password</label>
        <input type="password" name="password" class="form-control" placeholder="******">
      </div>



      <button type="submit" class="btn btn-danger btn-sm mt-2" id="submit" name="button"><i class="ti-trash"></i> Hapus akun</button>
    </form>
  </div>
</div>



<script type="text/javascript">
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
                    window.location.href="<?=site_url("/backend/member")?>";
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-trash"></i> Hapus akun');
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
