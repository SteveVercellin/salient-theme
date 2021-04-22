jQuery(function($) {

    let
        $taxes_list       = $('#bookly-tax'),
        $check_all_button = $('#bookly-check-all'),
        $modal            = $('#bookly-tax-modal'),
        $modal_title      = $('#bookly-tax-modal-title'),
        $tax_title        = $('#bookly-tax-title'),
        $tax_rate         = $('#bookly-tax-rate'),
        $save_button      = $('#bookly-tax-save'),
        $delete_button    = $('#bookly-delete'),
        columns           = [],
        order             = [],
        row
        ;

    /**
     * Init table columns.
     */
    $.each(BooklyTaxesL10n.datatables.taxes.settings.columns, function (column, show) {
        if (show) {
            columns.push({data: column});
        }
    });
    columns.push({
        responsivePriority: 1,
        orderable: false,
        className: "text-right",
        render: function (data, type, row, meta) {
            return '<button type="button" class="btn btn-default"><i class="far fa-fw fa-edit mr-1"></i>' + BooklyTaxesL10n.edit + '…</button>';
        }
    });
    columns.push({
        responsivePriority: 1,
        orderable: false,
        render: function (data, type, row, meta) {
            return '<div class="custom-control custom-checkbox">' +
                '<input value="' + row.id + '" id="bookly-dt-' + row.id + '" type="checkbox" class="custom-control-input">' +
                '<label for="bookly-dt-' + row.id + '" class="custom-control-label"></label>' +
                '</div>';
        }
    });

    $.each(BooklyTaxesL10n.datatables.taxes.settings.order, function (_, value) {
        const index = columns.findIndex(function (c) { return c.data === value.column; });
        if (index !== -1) {
            order.push([index, value.order]);
        }
    });

    /**
     * Init DataTables.
     */
    var dt = $taxes_list.DataTable({
        paging: false,
        info: false,
        searching: false,
        processing: true,
        responsive: true,
        ajax: {
            url: ajaxurl,
            data: { action: 'bookly_taxes_get_taxes', csrf_token : BooklyTaxesL10n.csrfToken }
        },
        order: order,
        columns: columns,
        language: {
            zeroRecords: BooklyTaxesL10n.zeroRecords,
            processing:  BooklyTaxesL10n.processing
        }
    });

    /**
     * Save order.
     */
    dt.on( 'order',  function () {
        let order = [];
        dt.order().forEach(function(data) {
            order.push({
                column: columns[data[0]].data,
                order: data[1]
            });
        });
        $.ajax({
            url  : ajaxurl,
            type : 'POST',
            data : {
                action : 'bookly_update_table_order',
                table: 'taxes',
                csrf_token : BooklyTaxesL10n.csrfToken,
                order : order
            },
            dataType : 'json'
        });
    });

    /**
     * Select all taxes.
     */
    $check_all_button.on('change', function () {
        $taxes_list.find('tbody input:checkbox').prop('checked', this.checked);
    });

    /**
     * On tax select.
     */
    $taxes_list.on('change', 'tbody input:checkbox', function () {
        $check_all_button.prop('checked', $taxes_list.find('tbody input:not(:checked)').length == 0);
    });

    /**
     * Edit tax.
     */
    $taxes_list.on('click', 'button', function () {
        row = dt.row($(this).closest('td'));
        $modal.booklyModal('show');
    });

    /**
     * On show modal.
     */
    $modal
        .on('show.bs.modal', function (e) {
            var data;
            if (row) {
                data = row.data();
                $modal_title.html(BooklyTaxesL10n.title.edit);
            } else {
                data = {title: '', rate: ''};
                $modal_title.html(BooklyTaxesL10n.title.new);
                $modal_title.show();
            }
            $tax_title.val(data.title);
            $tax_rate.val(data.rate);
        })
        .on('hidden.bs.modal', function () { row = null; });

    /**
     * Save tax.
     */
    $save_button.on('click', function (e) {
        e.preventDefault();
        var $form = $(this).parents('form'),
            data  = $form.serializeArray(),
            ladda = Ladda.create(this, {timeout: 2000}),
            abort = false;

        $('input[required]', $form).each(function () {
            if ($(this).val() == '') {
                $(this).addClass('is-invalid');
                abort = true;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (abort) {
            return false;
        }

        data.push({name: 'action', value: 'bookly_taxes_save_tax'});
        if (row){
            data.push({name: 'id', value: row.data().id});
        }
        ladda.start();
        $.ajax({
            url  : ajaxurl,
            type : 'POST',
            data : data,
            dataType : 'json',
            success  : function(response) {
                if (response.success) {
                    if (row) {
                        row.data(response.data).draw();
                    } else {
                        dt.row.add(response.data).draw();
                    }
                    $modal.booklyModal('hide');
                } else {
                    alert(response.data.message);
                }
                ladda.stop();
            }
        });
    });

    /**
     * Delete taxes.
     */
    $delete_button.on('click', function () {
        if (confirm(BooklyTaxesL10n.are_you_sure)) {
            var ladda = Ladda.create(this),
                data  = [],
                $checkboxes = $('tbody input:checked',$taxes_list);
            ladda.start();

            $checkboxes.each(function () {
                data.push(this.value);
            });

            $.ajax({
                url  : ajaxurl,
                type : 'POST',
                data : {
                    action     : 'bookly_taxes_delete_taxes',
                    csrf_token : BooklyTaxesL10n.csrfToken,
                    taxes      : data
                },
                dataType : 'json',
                success  : function(response) {
                    ladda.stop();
                    if (response.success) {
                        dt.rows($checkboxes.closest('td')).remove().draw();
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        }
    });
});