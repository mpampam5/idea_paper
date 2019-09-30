<div class="row">
  <div class="col-sm-12">
    <h5>From Reset Password</h5>
    <hr>
    <form class="" action="<?=$action?>" id="form">
      <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control"  readonly value="<?=$row->username?>">
      </div>

      <div class="form-group">
        <label id="password" >Password Baru</label>
        <input type="password" class="form-control"  name="password" placeholder="******">
      </div>

      <div class="form-group">
        <label id="konfirmasi_password" >Konfirmasi Password Baru</label>
        <input type="password" class="form-control"  name="konfirmasi_password" placeholder="******">
      </div>


      <a href="<?=site_url("backend/member/detail/account/".enc_uri($row->id_person)."/$row->id_register")?>" class="btn btn-sm btn-secondary text-white"><i class="ti-na"></i> Batal</a>
      <button type="submit" id="submit" class="btn btn-sm btn-primary" name="button"><i class="ti-check-box"></i> Reset Password</button>

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
                    window.location.href="<?=site_url("backend/member/detail/account/".enc_uri($row->id_person)."/$row->id_register")?>";
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Reset Password');
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
