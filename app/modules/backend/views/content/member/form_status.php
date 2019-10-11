<div class="row">
  <div class="col-sm-12">
    <form  action="<?=$action?>" id="form">
      <?php if ($is_active=="1"): ?>
        <div class="form-group">
          <label id="keterangan">Keterangan/alasan Menonaktifkan akun member</label>
          <textarea class="form-control" name="keterangan" rows="4" cols="80" placeholder="Masukkan keterangan/alasan"></textarea>
        </div>
        <?php else: ?>
          <p style="font-size:12px;">Pastikan Member telah memenuhi syarat dan ketentuan yang berlaku sebelum mengaktifkan kembali akun.</p>
          <input type="hidden" name="keterangan" value="value null">
      <?php endif; ?>


      <div class="form-group">
        <label id="password">Masukkan Password</label>
        <input type="password" name="password" class="form-control" placeholder="******">
      </div>

      <input type="hidden" name="status" value="<?=$is_active == "1" ? "0":"1"?>">

      <?php if ($is_active=="1"): ?>
          <button type="submit" class="btn btn-danger btn-sm mt-2" id="submit" name="button"><i class="fa fa-close"></i> Nonaktifkan</button>
        <?php else: ?>
          <button type="submit" class="btn btn-success btn-sm mt-2" id="submit" name="button"><i class="ti-check-box"></i> Aktifkan</button>
      <?php endif; ?>
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
                    window.location.href="<?=site_url("backend/member/detail/personal/".enc_uri($row->id_person)."/".$row->id_register)?>";
                }
              });


          }else {
            <?php if($is_active == "1"):?>
            $("#submit").prop('disabled',false)
                        .html('<i class="fa fa-close"></i> Nonaktifkan');
            <?php else: ?>
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Aktifkan');
            <?php endif; ?>
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
