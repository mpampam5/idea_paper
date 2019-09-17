<div class="row">
  <div class="col-12">
    <form action="<?=$action?>" id="form">

      <div class="form-group">
        <label for="">Username</label>
        <input type="text" class="form-control" readonly value="<?=$username?>">
      </div>

      <div class="form-group">
        <label for="">Password Baru</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" >
      </div>

      <div class="form-group">
        <label for="">Konfirmasi Password</label>
        <input type="password" class="form-control" id="v_password" name="v_password" placeholder="Konfirmasi Password">
      </div>

      <button type='button' class='btn btn-light btn-sm' data-dismiss='modal'>Cancel</button>
      <button type="submit" name="button" class="btn btn-primary btn-sm" id="submit"> Reset</button>
    </form>
  </div>
</div>


<script type="text/javascript">



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
              $("#modalGue").modal('hide');
              $('.form-group').removeClass('.has-error')
                              .removeClass('.has');
              $.toast({
                text: json.alert,
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'bottom-right'
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('Reset');
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
