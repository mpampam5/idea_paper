<link rel="stylesheet" href="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<script src="<?=base_url()?>_template/back/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?=base_url()?>_template/back/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<div class="row">
  <div class="col-sm-12">
    <h5 class="card-title">Data Investor</h5>
    <hr>

    <table id="table" class="table table-bordered">
      <thead class="bg-black text-yell">
        <tr>
          <th></th>
          <th>ID.REG</th>
          <th>Nama & Email</th>
          <th>Jumlah Paper</th>
          <th>Total Investasi</th>
          <th>#</th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
    var t = $("#table").dataTable({
        initComplete: function() {

          $(document).on('click', '#table-reload', function(){
              api.search('').draw();
              $('#table_filter input').val('');
            });

            var api = this.api();
            $('#table_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                            api.search(this.value).draw();
            });
        },
        oLanguage: {
            sProcessing: "Memuat Data..."
        },
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {"url": "<?=base_url()?>backend/trading/json_investor", "type": "POST"},
        columns: [
            {
              "data": "id_trans_person_trading",
              "orderable": false,
              "visible":false,
              searchable: false
            },
            {
              "data":"id_register",
              render:function(data,type,row,meta)
                {
                  return '<span class="text-primary" style="font-weight:bold"> '+data+'</span>';
                }
            },
            {
              "data":"nama",
              render:function(data,type,row,meta)
               {
                 return '<span style="font-size:12px;line-height:12px;font-weight:bold">'+data+'</span><br><span style="font-size:12px" class="text-danger">'+row.email+'</span>';
               }
            },
            {"data":"jumlah_paper","className":"text-center",searchable: false},
            {
              "data":"total_harga_paper",
              render:function(data,type,row,meta)
               {
                 return '<span>Rp.'+data+'</span>';
               },
              searchable: false
            },
            {
              "data" : "action",
              "orderable": false,
              "className" : "text-center"
            },
            {"data":"email","visible":false},
        ],
        order: [[0, 'desc']],
    });
});
</script>
