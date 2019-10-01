<style media="screen">
  .datepicker table{
    width: 100%;
  }
</style>

<div class="row">
  <div class="col-sm-12">
    <h5>From Edit Data Personal</h5>
    <hr>
    <form class="" action="<?=$action?>" id="form">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label id="nik">No. Identitas Kependudukan</label>
            <input type="text" class="form-control" name="nik" value="<?=$row->nik?>">
          </div>
        </div>

        <input type="hidden" class="form-control" name="nik_lama" placeholder="nik" value="<?=$row->nik?>">

        <div class="col-sm-6">
          <div class="form-group">
            <label id="nama">Nama Lengkap</label>
            <input type="text" class="form-control"  name="nama" value="<?=$row->nama?>">
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="telepon">No.Telepon</label>
            <input type="text" class="form-control"  name="telepon" value="<?=$row->telepon?>">
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="email">Email</label>
            <input type="text" class="form-control"  name="email" value="<?=$row->email?>">
          </div>
        </div>

        <input type="hidden" class="form-control"  name="email_lama" value="<?=$row->email?>">

        <div class="col-sm-6">
          <div class="form-group">
            <label id="pekerjaan">Pekerjaan</label>
            <select class="form-control" style="color:#495057"  name="pekerjaan">
                <?php foreach ($pekerjaan->result() as $kerja): ?>
                  <option <?=($row->pekerjaan==$kerja->pekerjaan?"selected":"")?> value="<?=$kerja->pekerjaan?>"><?=$kerja->pekerjaan?></option>
                <?php endforeach; ?>
              </select>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" name="jenis_kelamin"  style="color:#535353">
              <option <?=$row->jenis_kelamin == "pria" ? "selected":""?> value="pria">PRIA</option>
              <option <?=$row->jenis_kelamin == "wanita" ? "selected":""?> value="wanita">WANITA</option>
            </select>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="tempat_lahir">Tempat Lahir</label>
            <input type="text" class="form-control"  name="tempat_lahir" value="<?=$row->tempat_lahir?>">
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="tanggal_lahir">Tanggal Lahir</label>
            <input type="text" class="form-control" id="datepicker"  name="tanggal_lahir" data-provide="datepicker" value="<?=date("d/m/Y",strtotime($row->tanggal_lahir))?>">
          </div>
        </div>

        <div class="col-sm-12 mt-4">&nbsp;</div>


        <div class="col-sm-6">
          <div class="form-group">
            <label id="provinsi">Provinsi</label>
            <select class="form-control" style="color:#495057" id="get_provinsi" name="provinsi" onchange="loadKabupaten()">
                <?php foreach ($provinsi->result() as $prov): ?>
                  <option <?=($row->id_provinsi==$prov->id?"selected":"")?> value="<?=$prov->id?>"><?=$prov->name?></option>
                <?php endforeach; ?>
              </select>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="kabupaten">Kabupaten/Kota</label>
            <select class="form-control kabupaten" style="color:#495057" id="get_kabupaten" name="kabupaten" onChange='loadKecamatan()'>
              <?=tampilkan_wilayah("wil_kabupaten",["province_id"=>$row->id_provinsi],$row->id_kabupaten)?>
            </select>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="kecamatan">Kecamatan</label>
            <select class="form-control kecamatan" style="color:#495057" id="get_kecamatan" name="kecamatan" onChange='loadKelurahan()'>
                <?=tampilkan_wilayah("wil_kecamatan",["regency_id"=>$row->id_kabupaten],$row->id_kecamatan)?>
              </select>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label id="kelurahan">Kelurahan</label>
            <select class="form-control kelurahan" style="color:#495057" id="get_kelurahan" name="kelurahan">
                <?=tampilkan_wilayah("wil_kelurahan",["district_id"=>$row->id_kecamatan],$row->id_kelurahan)?>
              </select>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label id="alamat">Keterangan Alamat</label>
            <textarea class="form-control" name="alamat" rows="3" cols="80"><?=$row->alamat?></textarea>
          </div>
        </div>

      </div>




      <a href="<?=site_url("backend/member/detail/personal/".enc_uri($row->id_person)."/$row->id_register")?>" class="btn btn-sm btn-secondary text-white"><i class="ti-na"></i> Batal</a>
      <button type="submit" id="submit" class="btn btn-sm btn-primary" name="button"><i class="ti-check-box"></i> Simpan Perubahan</button>

    </form>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#datepicker').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
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
                    window.location.href="<?=site_url("backend/member/detail/personal/".enc_uri($row->id_person)."/$row->id_register")?>";
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


function loadKabupaten()
          {
              var provinsi = $("#get_provinsi").val();
              if (provinsi!="") {
                $.ajax({
                    type:'GET',
                    url:"<?php echo base_url(); ?>backend/member/kabupaten",
                    data:"id=" + provinsi,
                    success: function(html)
                    {
                       $("#get_kabupaten").html(html);
                       $("#get_kecamatan").html('<option value="">-- Pilih Kecamatan --</option>');
                       $("#get_kelurahan").html('<option value="">-- Pilih Kelurahan/desa --</option>');
                    }
                });
              }else {
                $("#get_kabupaten").html('<option value="">-- Pilih Kabupaten/Kota --</option>');
                $("#get_kecamatan").html('<option value="">-- Pilih Kecamatan --</option>');
                $("#get_kelurahan").html('<option value="">-- Pilih Kelurahan/desa --</option>');
              }
          }

          function loadKecamatan()
            {
                var kabupaten = $("#get_kabupaten").val();
                if (kabupaten!="") {
                  $.ajax({
                      type:'GET',
                      url:"<?php echo base_url(); ?>backend/member/kecamatan",
                      data:"id=" + kabupaten,
                      success: function(html)
                      {
                          $("#get_kecamatan").html(html);
                          $("#get_kelurahan").html('<option value="">-- Pilih Kelurahan/desa --</option>');
                      }
                  });
                }else {
                  $("#get_kecamatan").html('<option value="">-- Pilih Kecamatan --</option>');
                  $("#get_kelurahan").html('<option value="">-- Pilih Kelurahan/desa --</option>');
                }

            }

            function loadKelurahan()
            {
                var kecamatan = $("#get_kecamatan").val();
                if (kecamatan!="") {
                  $.ajax({
                      type:'GET',
                      url:"<?php echo base_url(); ?>backend/member/kelurahan",
                      data:"id=" + kecamatan,
                      success: function(html)
                      {
                          $("#get_kelurahan").html(html);
                      }
                  });
                }else {
                  $("#get_kelurahan").html('<option value="">-- Pilih Kelurahan/Desa --</option>');
                }
            }
</script>
