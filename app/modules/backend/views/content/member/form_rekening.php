<div class="row">
  <div class="col-sm-12">
    <h5>From Edit Data Rekening</h5>
    <hr>
    <form class="" action="<?=$action?>" id="form">
      <div class="form-group">
        <label id="nama_rekening">Nama Rekening</label>
        <input type="text" class="form-control"  name="nama_rekening" placeholder="" value="<?=$row->nama_rekening?>">
      </div>

      <div class="form-group">
        <label id="no_rekening" >Nomor Rekening</label>
        <input type="text" class="form-control"  name="no_rekening" placeholder="" value="<?=$row->no_rekening?>">
      </div>

      <div class="form-group">
        <label id="bank">BANK</label>
        <select class="form-control"  name="bank" style="color:#505050">
          <?=select_bank($row->ref_bank);?>
        </select>
      </div>

      <div class="form-group">
        <label id="kota_pembukuan">Kota Pembukaan Rekening</label>
        <input type="text" class="form-control"  name="kota_pembukuan" placeholder="" value="<?=$row->kota_pembukuan?>">
      </div>

      <a href="<?=site_url("backend/member/detail/rekening/".enc_uri($row->id_person)."/$row->id_register")?>" class="btn btn-sm btn-secondary text-white"><i class="ti-na"></i> Batal</a>
      <button type="submit" id="submit" class="btn btn-sm btn-primary" name="button"><i class="ti-check-box"></i> Simpan Perubahan</button>

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
                    window.location.href="<?=site_url("backend/member/detail/rekening/".enc_uri($row->id_person)."/$row->id_register")?>";
                }
              });


          }else {
            $("#submit").prop('disabled',false)
                        .html('<i class="ti-check-box"></i> Simpan Perubahan');
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
