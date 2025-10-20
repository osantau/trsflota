<?php

use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

$baseUrl = Url::base(true);
// Register DataTables assets from CDN
$this->registerCssFile($baseUrl.'/dataTables/1.13.6/css/jquery.dataTables.min.css');
$this->registerJsFile($baseUrl.'/dataTables/1.13.6/js/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css');
// $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCss(" 
    #vehicleTable tbody td:nth-child(2)  {
        cursor: pointer;
    }
         #vehicleTable tbody td:nth-child(3)  {
        cursor: pointer;
    }
           #vehicleTable tbody td:nth-child(4)  {
        cursor: pointer;
    }
           #vehicleTable tbody td:nth-child(5)  {
        cursor: pointer;
    }
              #vehicleTable tbody td:nth-child(6)  {
        cursor: pointer;
    }   
                #vehicleTable tbody td:nth-child(7)  {
        cursor: pointer;
    }  
                #vehicleTable tbody td:nth-child(8)  {
        cursor: pointer;
    }          #vehicleTable tbody td:nth-child(9)  {
        cursor: pointer;
    }  
        .right-border {
            border-right: 1px solid lightgray;    
        }
    .left-border {
            border-left: 1px solid lightgray;    
        }
            .right-border {
            border-right: 1px solid lightgray;    
        }  
        .top-border {
            border-top: 1px solid lightgray;    
        }    
        #vehicleTable_filter{
        margin-bottom:2px; 
        }
        .dataTable tbody tr {
        transition: filter 0.25s ease-in-out;
            }

        .dataTable tbody tr:hover {
            background-color: rgba(110, 50, 180, 0.35) !important; /* deep royal purple */
            filter: brightness(1.08) saturate(1.4);
            box-shadow: 0 0 10px rgba(150, 80, 255, 0.5);
            cursor: pointer;
            }
");
?>
<div class="site-index">
<h1>Camioane</h1>
<p>
        <?= Html::a('Adauga', ['create'], ['class' => 'btn btn-success']) ?>
    </p>   
<?php 
echo Html::input('hidden','baseUrl',Url::base(true),['id'=>'baseUrl']);

?>
    <table id="vehicleTable" class="display cell-border" style="width:100%">
    <thead>      
       <tr>
                <th colspan="6" class="left-border right-border top-border">Detalii Comanda</th>
                <th colspan="2" class="right-border top-border">Export</th>
                <th colspan="2" class="right-border top-border">Import</th>
                <th colspan="2" class="right-border top-border"></th>
            </tr>
        <tr>
            <th>Id</th>          
            <th class="right-border left-border">#</th>  
            <th>Nr. Inmatriculare</th> 
            <th>Comanda Transport</th>
            <th>Data Incarcare</th>           
            <th class="right-border">Data Descarcare</th>
            <th>Adresa Incarcare</th>
            <th class="right-border">Adresa Descarcare</th>
            <th>Adresa Incarcare</th>
            <th class="right-border">Adresa Descarcare</th>
            <th class="right-border">Actiuni</th>
            <th>Status</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
</table>

<?php
$this->registerJs(<<<JS
  const baseUrl = $('#baseUrl').val();  
  function warnStatus() {
    alert('Selectati o Comanda !');
  }
var table=  $('#vehicleTable').DataTable({        
        processing: true,
        serverSide: true,
        ordering: true,
        autoWidth: true,
        responsive: true,
        scrollX:true,
        pageLength:25,
        language: {
            url: baseUrl+'dataTables/1.13.6/i18n/ro.json',
             /*search: "<span class='me-2'>🔍 Cautare:</span>",
             lengthMenu: "Afisare <strong>_MENU_</strong> inregistrari",
             info: "Afisare _START_ din _END_ din _TOTAL_ inregistrari",
              paginate: {
                    previous: "<span class='me-1'>&laquo;</span> Prec",
                    next: "Urm <span class='ms-1'>&raquo;</span>"
    } */
        },
        order:[[10,'asc']],
        ajax: baseUrl+'/vehicle/data',            
     columns: [  
        {data: 0, visible: false,orderable: false },   //ID      
        {data: null, orderable: false // Contor
        ,defaultContent: '', // Set default content to empty      
      render: function (data, type, row, meta) {
        // 'meta.row' is the zero-based index of the row
        return meta.row + 1;
      }},    
        {data: 1}, // Nr. inmatriculare
        {data: 2,orderable: false}, // comanda transport
        {data: 3,orderable: false}, // Data incarcare
        {data: 4,orderable: true}, //Data descarcare 
        {data: 5,orderable: false}, // Adresa incarcare Exp
        {data: 6,orderable: false}, // Adresa Descarcare Exp
        {data: 7,orderable: false}, // Adresa incarcare Imp
        {data: 8,orderable: false}, // Adresa descarcare Imp
        {data: 9,orderable: false}, // actions
        {data: 10, visible:false}, //status
        {data: 11, visible:false,orderable: false},
        {data: 12, visible:false,orderable: false},
        {data: 13, visible:false,orderable: false},
        {data: 14, visible:false,orderable: false}

  ]  ,    
  "rowCallback": function(row, data, index){
    // $(row).css('font-weight','bold');
        var cond = data[10];
        switch (cond) {
           
          case 1:
                   $(row).css('background-color', '#cefad0');
                break;
          case 2:
                   $(row).css('background-color', '#FFCCCB');
                break;
         case 3:{
            $('td:eq(5)', row).css('background-color', '#f9fabaff');
            $('td:eq(6)', row).css('background-color', '#f9fabaff');
            $('td:eq(7)', row).css('background-color', '#f9fabaff');
            $('td:eq(8)', row).css('background-color', '#f9fabaff');
         } break;

            default:
                   
                break;
        }
    }
  ,
      drawCallback: function(settings) {

         $('#vehicleTable tbody td:nth-child(10)').each(function() { 
            var cell = $(this);
            var rowData = table.row(cell.closest('tr')).data();
            var hoverTimeout;
            cell.attr('data-bs-toggle', 'tooltip')
                .attr('title', ''); // temporary placeholder

            // Remove any existing tooltip
            cell.tooltip('dispose');
            // On mouse enter, fetch tooltip content
         cell.off('mouseenter').on('mouseenter', function() {  
                    
                hoverTimeout = setTimeout(function() {
                    $.ajax({
                        url: baseUrl+'/vehicle/summary',
                        data: { id: rowData[0] },
                        success: function(response) {
                            cell.attr('title', response.content)
                                .tooltip('dispose')
                                .tooltip({ html: true })
                                .tooltip('show');
                        }
                    });
                }, 300); // delay in milliseconds
            });

            // On mouse leave, hide tooltip
            cell.off('mouseleave').on('mouseleave', function() {
                clearTimeout(hoverTimeout);     
                cell.tooltip('hide');
            });   
         });
        $('#vehicleTable tbody td:nth-child(2)').each(function() { // Nr. inmatriculare column
            var cell = $(this);
            var rowData = table.row(cell.closest('tr')).data();
            var hoverTimeout;
            /*cell.attr('data-bs-toggle', 'tooltip')
                .attr('title', ''); // temporary placeholder

            // Remove any existing tooltip
            cell.tooltip('dispose');
            // On mouse enter, fetch tooltip content
         cell.off('mouseenter').on('mouseenter', function() {  
                    
                hoverTimeout = setTimeout(function() {
                    $.ajax({
                        url: baseUrl+'/vehicle/summary',
                        data: { id: rowData[0] },
                        success: function(response) {
                            cell.attr('title', response.content)
                                .tooltip('dispose')
                                .tooltip({ html: true })
                                .tooltip('show');
                        }
                    });
                }, 300); // delay in milliseconds
            });

            // On mouse leave, hide tooltip
            cell.off('mouseleave').on('mouseleave', function() {
                clearTimeout(hoverTimeout);     
                cell.tooltip('hide');
            });   */    
      
            
        cell.off('click').on('click', function() {            
        // Load form via Ajax
        $.ajax({
            url: baseUrl+'/vehicle/info',
            data: { id: rowData[0] },
            success: function(html) {
                $('#editInfoModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('editInfoModal'));
                modal.show();
            }
        });
    }); 
        });

    // editare info
    $('#editInfoModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/vehicle/info-ajax',
        method: 'POST',
        data: $('#editInfoForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editInfoModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});

 $('#vehicleTable tbody td:nth-child(3)').each(function() { // Comanda Transport
            var cell = $(this);            
            var rowData = table.row(cell.closest('tr')).data();
            var rowNode = cell.closest('tr');  
            var span = rowNode.find('td').eq(2).find('span');
            var orderId=span.attr('data-id');
            var hoverTimeout;                                    
            cell.attr('data-bs-toggle', 'tooltip')
                .attr('title', ''); // temporary placeholder

            // Remove any existing tooltip
            cell.tooltip('dispose');
            if(orderId.length>0) {
            // On mouse enter, fetch tooltip content
            cell.off('mouseenter').on('mouseenter', function() {  
                                                              
                hoverTimeout = setTimeout(function() {
                    $.ajax({
                        url: baseUrl+'/transport-order/summary',
                        data: { id: rowData[0], cId: orderId },
                        success: function(response) {
                            cell.attr('title', response.content)
                                .tooltip('dispose')
                                .tooltip({ html: true })
                                .tooltip('show');
                        }
                    });
                }, 300); // delay in milliseconds
            });
             
        }
            // On mouse leave, hide tooltip
            cell.off('mouseleave').on('mouseleave', function() {
                clearTimeout(hoverTimeout);     
                cell.tooltip('hide');
            });       
              
        cell.off('click').on('click', function() {
        // Load form via Ajax
        $.ajax({
            url: baseUrl+'/transport-order/info',
            data: { id: rowData[0],cId:(orderId.length>0?orderId:'0') },
            success: function(html) {
                $('#editComandaModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('editComandaModal'));
                modal.show();
            }
        });
    }); 
 

        });

        // salveaza comanda
    $('#editComandaModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/transport-order/info-ajax',
        method: 'POST',
        data: $('#editComandaForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editComandaModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});
   $('#editComandaModal').on('click','#btn-delete-order', function(e) {
    e.preventDefault();    
    $('#remove_cmd').val(1);    
   $.ajax({
        url: baseUrl+'/transport-order/info-ajax',
        method: 'POST',
        data: $('#editComandaForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editComandaModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});
//Editare campuri date
$('#vehicleTable tbody td:nth-child(4), #vehicleTable tbody td:nth-child(5)').each(function() { // Data Incarcare / Descarcare
    
            var cell = $(this);            
            var rowData = table.row(cell.closest('tr')).data();        
            var cellIndex = table.cell(cell).index().column;
            var pTip = cellIndex==4?'di':'de';        
            cell.off('click').on('click', function() {
            
        // Load form via Ajax
        $.ajax({
            url: baseUrl+'/vehicle/dates',
            data: { id: rowData[0] , tip:pTip},
            success: function(html) {
                $('#editDatesModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('editDatesModal'));
                modal.show();
            }
        });
    }); 
 

        });
     // salveaza data incarcare si descarcare
  $('#editDatesModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/vehicle/dates-ajax',
        method: 'POST',
        data: $('#editDatesForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editDatesModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});
//end of
//adrese
$('#vehicleTable tbody td:nth-child(6), #vehicleTable tbody td:nth-child(7),#vehicleTable tbody td:nth-child(8), #vehicleTable tbody td:nth-child(9)').each(function() { 
            var cell = $(this);            
            var rowData = table.row(cell.closest('tr')).data();                                           
            var cellIndex = table.cell(cell).index().column;   
            var pTip ='';
            var adrId=0;  
            
            switch (cellIndex) {
                    case 6:{
                        pTip='exp_ai';
                        adrId=rowData[11];
                    }break;
                  case 7:{
                        pTip='exp_ad';
                        adrId=rowData[12];
                    }break;
                     case 8:{
                        pTip='imp_ai';
                        adrId=rowData[13];
                    }break;
                    case 9:{
                        pTip='imp_ad';
                        adrId=rowData[14];
                    }break;
                    default:
                      adrId=0;
                  }
            cell.off('click').on('click', function() {                                                                                      
                // Load form via Ajax
        $.ajax({
            url: baseUrl +'/location/adrese',
            data: { vid: rowData[0] , tip:pTip, aid:adrId},
            success: function(html) {
                $('#editAdreseModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('editAdreseModal'));
                modal.show();
            }
        }); 
    }); 
 

        });
 


//end of
    }      
    });

    $(document).on('click','.btnEditStatus', function(e){        
        let vehicleId = $(this).data('id');    
         $.ajax({
            url: baseUrl +'/vehicle/edit-status',
            data: { id:vehicleId },
            success: function(html) {
                $('#statusModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('statusModal'));
                modal.show();
            }
        });

});

    // salveaza adrese
  $('#editAdreseModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/location/adrese-ajax',
        method: 'POST',
        data: $('#editAdreseForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editAdreseModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});

 $('#editAdreseModal').on('reset', function(e) {
    e.preventDefault();
    $('#aid').val('0');
    $('#company').val('');
    $('#country').val('');
    $('#region').val('');
    $('#address').val('');
    $('#city').val('');
});

$('#editAdreseModal').on('click', '#btnDelAdr', function () {
    let vehicleId = $(this).data('id');   
    let pTip = $('#editAdreseModal #tip').val(  );
    
     $.ajax({
        url: baseUrl+'/location/delete-ajax',        
        data:{id:vehicleId, tip:pTip},
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('editAdreseModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});

  $('#statusModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/vehicle/status-ajax',
        method: 'POST',
        data: $('#statusForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('statusModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});
    $(document).on('click','.btnEditDriver', function(e){        
        let vehicleId = $(this).data('id');    
         $.ajax({
            url: baseUrl +'/vehicle/edit-driver',
            data: { id:vehicleId },
            success: function(html) {
                $('#driverModal .modal-body').html(html);                
                var modal = new bootstrap.Modal(document.getElementById('driverModal'));
                modal.show();
            }
        });        
});
  $('#driverModal').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl+'/vehicle/driver-ajax',
        method: 'POST',
        data: $('#driverForm').serialize(),
        success: function(response) {
            if (response.success) {
                // Close modal
                var modalEl = document.getElementById('driverModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                // Reload DataTable
                table.ajax.reload(null, false);
            } else {
                alert(response.message);
            }
        }
    });
});
     /*$('#editInfoModal, #editComandaModal, #editDatesModal, #editAdreseModal').on('shown.bs.modal', function () {
        table.columns.adjust().draw();
    }); */
    /*$('#editAdreseModal').on('shown.bs.modal', function () {
    $(this).find('.select2').each(function() {
        $(this).select2({
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '/location/address-list',
                dataType: 'json',
                data: function (params) { return {q: params.term}; },
                processResults: function (data) { return data; }
            }
        });
    });
}); */
JS);
?>
</div>
<!-- Edit Info Modal -->
<div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editInfoForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editInfoModalLabel">Editare Info vehicul</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
          <button type="submit" class="btn btn-primary">Salveaza</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit Transport Order Modal -->
<div class="modal fade" id="editComandaModal" tabindex="-1" aria-labelledby="editComandaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editComandaForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editInfoModalLabel">Editare Comanda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
          <button type="submit" class="btn btn-primary">Salveaza</button>
          <button type="button" class="btn btn-danger" id="btn-delete-order">Sterge</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit Dates Modal -->
<div class="modal fade" id="editDatesModal" tabindex="-1" aria-labelledby="editDatesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editDatesForm">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="editDatesModalLabel">Editare Comanda</h5> -->
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
          <button type="submit" class="btn btn-primary">Salveaza</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Adauga / Editeaza Adresa -->
<div class="modal fade" id="editAdreseModal" tabindex="-1" aria-labelledby="editAdreseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
        <div class="modal-header">          
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>        
      </div>
  </div>
</div>
<!-- Editare Stare vehicul-->
 <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">      
        <div class="modal-header">          
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>        
      </div>
  </div>
</div>
<!-- Editare Soferi vehicul-->
 <div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="driverModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">      
        <div class="modal-header">          
        </div>
        <div class="modal-body">
          <!-- Form fields will be loaded via Ajax -->
        </div>        
      </div>
  </div>
</div>
