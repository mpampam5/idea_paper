<div class="row">
  <div class="col-sm-12">
    <p style="font-size:14px;" class="mb-3">Are you sure you want to delete?</p>
    <form action="<?=$action?>" id="form">
      <div class="form-group">
        <label for="">Keterangan</label>
        <textarea class="form-control" name="keterangan" rows="2" cols="80"></textarea>
      </div>

      <button type='button' class='btn btn-secondary btn-sm text-white' data-dismiss='modal'>Cancel</button>
      <button type="submit" class="btn btn-sm btn-danger" id="submit" name="button">Yes, Delete</button>
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
                position: 'bottom-right',
                afterHidden: function () {
                    $('#table').DataTable().ajax.reload();
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('Approved');
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
