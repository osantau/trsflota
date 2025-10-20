<?php

use app\models\Invoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\InvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Facturi ' . strtoupper($moneda);
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
$this->registerJsFile('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCss("
 .dataTable tbody tr {
        transition: filter 0.25s ease-in-out;
            }

        .dataTable tbody tr:hover {
            background-color: rgba(110, 50, 180, 0.35) !important; /* deep royal purple */
            filter: brightness(1.08) saturate(1.4);
            box-shadow: 0 0 10px rgba(150, 80, 255, 0.5);
            cursor: pointer;
            }
             .status-paid {
    background-color: rgba(60, 179, 113, 0.25) !important; /* green */
}

.status-pending {
    background-color: rgba(255, 215, 0, 0.25) !important; /* yellow */
}
            "
);
$this->title = 'Facturi ' . strtoupper($moneda);
$baseUrl = Url::base(true);
?>
<div class="invoice-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Adauga Factura', ['create?moneda=' . $moneda], ['class' => 'btn btn-success']) ?>
        <button id="refreshAll" class="btn btn-primary">
            <i class="fa fa-sync"></i> Reîncarcă
        </button>
    </p>

    <?= Html::hiddenInput('baseUrl', Url::base(true), ['id' => 'baseUrl']) ?>
    <?= Html::hiddenInput('moneda', $moneda, ['id' => 'moneda']) ?>

    <table id="invoiceTable" class="display" style="width:100%">
        <?php if ($moneda === 'ron') { ?>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data Factura</th>
                    <th>Data Scadenta</th>
                    <th>Numar Factura</th>
                    <th>Partener</th>
                    <th>Valoare</th>
                    <th>Suma Achitata</th>
                    <th>Sold</th>
                    <th>Data Incasare</th>
                    <th>Incasat</th>
                    <th>Mentiuni</th>                    
                    <th>Actiuni</th>
                </tr>
            </thead>
        <?php } else if ($moneda === 'eur') { ?>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data Factura</th>
                        <th>Data Scadenta</th>
                        <th>Numar Factura</th>
                        <th>Partener</th>
                        <th>Valoare</th>
                        <th>Suma Achitata</th>
                        <th>Sold</th>
                        <th>Data Incasare</th>
                        <th>Incasat</th>
                        <th>Diferenta</th>
                        <th>Credit Note</th>                        
                        <th>Actiuni</th>
                    </tr>
                </thead>
        <?php } ?>
    </table>
</div>
<div class="modal fade" id="inlineEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editează Valori EUR/RON</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="inlineEditForm">
          <!-- Valoare -->
          <div class="mb-2">
            <label>Valoare EUR</label>
            <input type="number"  min="0" class="form-control" id="editValoareEUR">
            <label class="mt-1">Valoare RON</label>
            <input type="number" min="0" class="form-control" id="editValoareRON">
          </div>
          <!-- Suma Achitata -->
          <div class="mb-2">
            <label>Suma Achitata EUR</label>
            <input type="number"  min="0" class="form-control" id="editSumaAchitataEUR">
            <label class="mt-1">Suma Achitata RON</label>
            <input type="number" min="0" class="form-control" id="editSumaAchitataRON">
          </div>
          <!-- Sold -->
          <div class="mb-2">
            <label>Sold EUR</label>
            <input type="number" min="0" class="form-control" id="editSoldEUR">
            <label class="mt-1">Sold RON</label>
            <input type="number" min="0" class="form-control" id="editSoldRON">
          </div>
          <input type="hidden" id="editRowId">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
        <button type="button" class="btn btn-primary" id="saveInlineEdit">Salvează</button>
      </div>
    </div>
  </div>
</div>
<?php
$this->registerJs(<<<JS
$.ajaxSetup({
  headers: { 'X-CSRF-Token': yii.getCsrfToken() }
});
$(document).ready(function() {
    const moneda = $('#moneda').val();  
    const baseUrl = $('#baseUrl').val();
 $('#invoiceTable thead').append('<tr class="filter-row"></tr>');
    $('#invoiceTable thead tr:eq(0) th').each(function(i) {
        const colName = $(this).text().trim();
        let input = '';
        if (colName === 'Data Factura' || colName === 'Data Scadenta') {
            input = '<div style="display:flex; flex-direction:column;">'
            +'<input type="date" class="form-control form-control-sm column-filter" placeholder="'+colName+' From" />'
            +'<input type="date" class="form-control form-control-sm column-filter" placeholder="'+colName+' To" />'
            +'</div>';
        } else if (colName === 'Partener') {
            input = '<input type="text" class="form-control form-control-sm column-filter" placeholder="Caută partener..." />';
        } else {
            input = '';
        }
        $('#invoiceTable thead tr.filter-row').append('<th>' + input + '</th>');
    });
    let columns = [
        { data: 'id', visible: false },
        { data: 'dateinvoiced' },
        { data: 'duedate' },
        { data: 'nr_factura' },
        { data: 'partener' }
    ];

    if (moneda === 'eur') {
         columns.push({
        data: null,
        render: function(row) {
            return 'EUR: '+row.valoare_eur+'<br>RON: '+row . valoare_ron+'';
        }
    });
    columns.push({
        data: null,
        render: function(row) {
            return 'EUR: '+row . suma_achitata_eur+'<br>RON: '+row . suma_achitata_ron+'';
        }
    });
    columns.push({
        data: null,
        render: function(row) {
            return 'EUR: '+row . sold_eur+'<br>RON: '+row . sold_ron+'';
        }
    });
    } else {
        columns.push(
            { data: 'valoare_ron' },
            { data: 'suma_achitata_ron' },
            { data: 'sold_ron' }
        );
    }

    columns.push({ data: 'paymentdate' });
    columns.push({ data: 'status' });

    if (moneda === 'eur') {
        columns.push({ data: 'diferenta', orderable: false });
        columns.push({ data: 'credit_note', orderable: false });
    }

    if (moneda === 'ron') {
        columns.push({ data: 'mentiuni', orderable: false });
    }

    columns.push({
        data: null,
        orderable: false,
        render: function(data, type, row) {
            const editUrl = baseUrl + '/invoice/update?id=' + row.id;
            return( 
               ' <div class="btn-group" role="group"> '
                 + '<a href="'+editUrl+'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" title="Editeaza"></i></a></div>'
                 + '<button class="btn btn-sm btn-primary duplicate-btn" data-id="'+row.id+'" title="Duplicheaza"><i class="fa fa-copy"></i></button> '
                 + '<button class="btn btn-sm btn-danger delete-btn" data-id="'+row.id+'" title="Sterge"><i class="fa fa-trash"></i></button></div>');
        }
    }); 

    const table = $('#invoiceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{ url: baseUrl + '/invoice/data?moneda=' + moneda,
             data: function(d){
             d.dateinvoiced_from = $('input.column-filter[placeholder="Data Factura From"]').val();
             d.dateinvoiced_to = $('input.column-filter[placeholder="Data Factura To"]').val();
             d.duedate_from = $('input.column-filter[placeholder="Data Scadenta From"]').val();
             d.duedate_to = $('input.column-filter[placeholder="Data Scadenta To"]').val();
             d.partener = $('input.column-filter[placeholder="Caută partener..."]').val();
          }
        },
        ordering: true,
        autoWidth: false,
        responsive: true,        
        columns: columns,
        pageLength: 25,
        order: [[1, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ro.json'
        },
           rowCallback: function(row, data) {
        $(row).removeClass('status-paid status-pending status-overdue');
        if (data.status) {
            const status = data.status.toLowerCase();
            if (status === 'total') {
                $(row).addClass('status-paid');
            } else if (status === 'partial') {
                $(row).addClass('status-pending');
            } /*else if (status === 'neplatit' || status === 'overdue' || status === 'unpaid') {
                $(row).addClass('status-overdue');
            }*/
        }
    }

    });

    // Refresh button
    $('#refreshAll').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Reîncarcă...');
        table.ajax.reload(() => {
            btn.prop('disabled', false).html('<i class="fa fa-sync"></i> Reîncarcă');
        }, false);
    });
      // Debounce helper
    function debounce(fn, delay) {
        let timeout;
        return function() {
            clearTimeout(timeout);
            timeout = setTimeout(fn.bind(this), delay);
        }
    }
  $(document).on('change keyup', '.column-filter', debounce(function() {
        table.ajax.reload();
    },400));
    // Inline editing (same as before)
    const editableColumns = {        
        'dateinvoiced': 'date',
        'duedate': 'date',
        'nr_factura': 'text',        
        'paymentdate': 'date',
        'mentiuni': 'text',
        'credit_note': 'text',
    };

    $(document).on('dblclick', '#invoiceTable td', function() {
        const moneda = $('#moneda').val();  
        const cell = $(this);
        const cellIndex = table.cell(this).index();
        const rowData = table.row(this).data();
        const colName = table.settings().init().columns[cellIndex.column].data;
        if (!editableColumns[colName] && !moneda) return;

            // Check if cell contains EUR+RON
        const combinedCell = $(cell).html().includes('EUR');        
   if (combinedCell) {
      const id = $('#editRowId').val(rowData.id);
        // Fill modal inputs with current values
        $('#editValoareEUR').val(rowData.valoare_eur);
        $('#editValoareRON').val(rowData.valoare_ron);

        $('#editSumaAchitataEUR').val(rowData.suma_achitata_eur);
        $('#editSumaAchitataRON').val(rowData.suma_achitata_ron);

        $('#editSoldEUR').val(rowData.sold_eur);
        $('#editSoldRON').val(rowData.sold_ron);
        

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('inlineEditModal'));
        modal.show();
     
        // Save handler
        $('#saveInlineEdit').off('click').on('click', function() {                       
     
            const payload = {
                valoare_eur: $('#editValoareEUR').val()===''?'0':$('#editValoareEUR').val(),
                valoare_ron: $('#editValoareRON').val()===''?'0':$('#editValoareRON').val(),
                suma_achitata_eur: $('#editSumaAchitataEUR').val()===''?'0':$('#editSumaAchitataEUR').val(),
                suma_achitata_ron: $('#editSumaAchitataRON').val()===''?'0':$('#editSumaAchitataRON').val(),
                sold_eur: $('#editSoldEUR').val()===''?'0':$('#editSoldEUR').val(),
                sold_ron: $('#editSoldRON').val()===''?'0':$('#editSoldRON').val()
            };
            const id = $('#editRowId').val();

            $.ajax({
                url: baseUrl + '/invoice/update-inline',
                type: 'POST',
                data: { id: id, field: 'combined_all', value: payload },
                success: function(res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        modal.hide();
                    } else {
                        alert('Eroare: ' + res.message);
                    }
                },
                error: function() {
                    alert('Eroare la actualizare.');
                }
            });
        });

        return; // stop regular inline editing
    }

        const type = editableColumns[colName];
        const oldValue = cell.text().trim();
        let input = $('<input type="' + (type === 'text' ? 'text' : type) + '" class="form-control form-control-sm">').val(oldValue);
        cell.html(input);
        input.focus();

        input.on('blur keydown', function(e) {
            if (e.key === 'Escape') {
                cell.text(oldValue);
                return;
            }
            if (e.type === 'blur' || e.key === 'Enter') {
                const newValue = input.val();
                if (newValue === oldValue) {
                    cell.text(oldValue);
                    return;
                }
                $.ajax({
                    url: baseUrl + '/invoice/update-inline',
                    type: 'POST',
                    data: {
                        id: rowData.id,
                        field: colName,
                        value: newValue
                    },
                    success: function(res) {
                        if (res.success) {
                            cell.text(newValue).css('background-color', '#d4edda');
                            setTimeout(() => cell.css('background-color', ''), 600);
                            table.ajax.reload(null, false);
                        } else {
                            alert('Eroare: ' + res.message);
                            cell.text(oldValue);
                        }
                    },
                    error: function() {
                        alert('Eroare la actualizare.');
                        cell.text(oldValue);
                    }
                });
            }
        });
    });

    // Duplicate button
    $(document).on('click', '.duplicate-btn', function() {
        const id = $(this).data('id');
        if (!confirm('Sigur doriți să duplicați această factură?')) return;
        $.ajax({
            url: baseUrl + '/invoice/duplicate',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    alert('Factura a fost duplicată cu succes.');
                    table.ajax.reload(null, false);
                } else {
                    alert('Eroare: ' + response.message);
                }
            },
            error: function() {
                alert('A apărut o eroare la duplicare.');
            }
        });
    });

    // Delete button
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (!confirm('Sigur doriți să ștergeți această factură? Aceasta acțiune este ireversibilă.')) return;
        $.ajax({
            url: baseUrl + '/invoice/delete',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    alert('Factura a fost ștearsă cu succes.');
                    table.ajax.reload(null, false);
                } else {
                    alert('Eroare: ' + response.message);
                }
            },
            error: function() {
                alert('A apărut o eroare la ștergere.');
            }
        });
    });
});

JS); ?>