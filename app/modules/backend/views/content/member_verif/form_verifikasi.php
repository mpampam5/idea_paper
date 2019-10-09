<div class="row">
  <div class="col-sm-12">
    <form  action="<?=$action?>" id="form">

      <div class="form-group">
        <label>Pilihan keterangan</label>
        <div class="form-check">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="radio" id="pil-ket" value="data dan berkas telah memenuhi syarat dan ketentuan yang berlaku.">
            data dan berkas telah memenuhi syarat dan ketentuan yang berlaku.
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="radio" id="pil-ket2" value="" checked>
            Lain-lain
          <i class="input-helper"></i></label>
        </div>
      </div>

      <div class="form-group">
        <label id="keterangan">Keterangan/alasan memverifikasi akun member</label>
        <textarea class="form-control keterangan" name="keterangan" rows="4" cols="80" placeholder="Masukkan keterangan/alasan"></textarea>
      </div>

      <div class="form-group">
        <label id="password">Masukkan Password</label>
        <input type="password" name="password" class="form-control" placeholder="******">
      </div>



      <button type="submit" class="btn btn-success btn-sm mt-2" id="submit" name="button"><i class="ti-check-box"></i> Verifikasi Akun</button>
    </form>
  </div>
</div>



<script type="text/javascript">

$(".form-check-input").change(function(e){
  e.preventDefault();
  var val = $(this).attr("value");

  $(".keterangan").text(val);
})
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
              $('.form-group').removeClass('.has-error')
                              .removeClass('.has');
              $.toast({
                text: json.alert,
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'bottom-right',
                afterHidden: function () {
                    window.location.href="<?=site_url("/backend/member_verif")?>";
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Verifikasi Akun');
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
