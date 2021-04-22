jQuery(function($) {

    let $codeFilter       = $('#bookly-filter-code'),
        $serviceFilter    = $('#bookly-filter-service'),
        $staffFilter      = $('#bookly-filter-staff'),
        $customerFilter   = $('#bookly-filter-customer'),
        $onlyActiveFilter = $('#bookly-filter-active'),
        $couponsList      = $('#bookly-coupons-list'),
        $checkAllButton   = $('#bookly-check-all'),
        $couponModal      = $('#bookly-coupon-modal'),
        $seriesNewTitle   = $('#bookly-new-coupon-series-title'),
        $couponNewTitle   = $('#bookly-new-coupon-title'),
        $couponEditTitle  = $('#bookly-edit-coupon-title'),
        $couponCode       = $('#bookly-coupon-code'),
        $generateCode     = $('#bookly-generate-code'),
        $seriesMask       = $('#bookly-coupon-series-mask'),
        $seriesAmount     = $('#bookly-coupon-series-amount'),
        $couponDiscount   = $('#bookly-coupon-discount'),
        $couponDeduction  = $('#bookly-coupon-deduction'),
        $couponUsageLimit = $('#bookly-coupon-usage-limit'),
        $couponOncePerCst = $('#once_per_customer'),
        $couponDateStart  = $('#bookly-coupon-date-limit-start'),
        $clearDateStart   = $('#bookly-clear-date-limit-start'),
        $couponDateEnd    = $('#bookly-coupon-date-limit-end'),
        $clearDateEnd     = $('#bookly-clear-date-limit-end'),
        $couponMinApps    = $('#bookly-coupon-min-appointments'),
        $couponMaxApps    = $('#bookly-coupon-max-appointments'),
        $couponCustomers  = $('#bookly-coupon-customers'),
        $customersList    = $('#bookly-customers-list'),
        $couponServices   = $('#bookly-js-coupon-services'),
        $couponProviders  = $('#bookly-js-coupon-providers'),
        $saveButton       = $('#bookly-coupon-save', $couponModal),
        $addButton        = $('#bookly-add'),
        $addSeriesButton  = $('#bookly-add-series'),
        $deleteButton     = $('#bookly-delete'),
        $createAnother    = $('#bookly-create-another-coupon'),
        columns           = [],
        order             = [],
        edit_and_duplicate =
            $('<div class="d-inline-flex">').append(
                $('<button type="button" class="btn btn-default mr-1" data-action="edit"></button>').append($('<i class="far fa-fw fa-edit mr-1" />'), BooklyCouponL10n.edit + '…'),
                $('<button type="button" class="btn btn-default" data-action="edit" data-mode="duplicate"></button>').append($('<i class="far fa-fw fa-clone mr-1" />'), BooklyCouponL10n.duplicate + '…')
            ).get(0).outerHTML,
        row,
        series,
        duplicate
    ;

    $('.bookly-js-select').val(null);
    $.each(BooklyCouponL10n.datatables.coupons.settings.filter, function (field, value) {
        if (value != '') {
            let $elem = $('#bookly-filter-' + field);
            if ($elem.is(':checkbox')) {
                $elem.prop('checked', value == '1');
            } else {
                $elem.val(value);
            }
        }
        // check if select has correct values
        if ($('#bookly-filter-' + field).prop('type') == 'select-one') {
            if ($('#bookly-filter-' + field + ' option[value="' + value + '"]').length == 0) {
                $('#bookly-filter-' + field).val(null);
            }
        }
    });
    /**
     * Init filters.
     */

    $('.bookly-js-select').on('change', function () { dt.ajax.reload(); })
        .select2({
            width: '100%',
            theme: 'bootstrap4',
            dropdownParent: '#bookly-tbs',
            allowClear: true,
            placeholder: '',
            language  : {
                noResults: function() { return BooklyCouponL10n.noResultFound; }
            },
            matcher: function (params, data) {
                const term = $.trim(params.term).toLowerCase();
                if (term === '' || data.text.toLowerCase().indexOf(term) !== -1) {
                    return data;
                }

                let result = null;
                const search = $(data.element).data('search');
                search &&
                search.find((text) => {
                    if (result === null && text.toLowerCase().indexOf(term) !== -1) {
                        result = data;
                    }
                });

                return result;
            }
        });

    $('.bookly-js-select-ajax')
        .val(null)
        .on('change', function () { dt.ajax.reload(); })
        .select2({
            width: '100%',
            theme: 'bootstrap4',
            dropdownParent: '#bookly-tbs',
            allowClear: true,
            placeholder: '',
            language  : {
                noResults: function() { return BooklyCouponL10n.noResultFound; },
                searching: function () { return BooklyCouponL10n.searching; }
            },
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    params.page = params.page || 1;
                    return {
                        action: this.action === undefined ? $(this).data('ajax--action') : this.action,
                        filter: params.term,
                        page: params.page,
                        csrf_token : BooklyCouponL10n.csrfToken
                    };
                }
            },
        });
    $onlyActiveFilter.on('change', function () { dt.ajax.reload(); });
    $codeFilter.on('keyup', function () { dt.ajax.reload(); });

    /**
     * Init Columns.
     */
    $.each(BooklyCouponL10n.datatables.coupons.settings.columns, function (column, show) {
        if (show) {
            switch (column) {
                case 'services':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            if (data == 0) {
                                return BooklyCouponL10n.services.nothingSelected;
                            } else if (data == 1) {
                                return BooklyCouponL10n.services.collection[row.service_ids[0]].title;
                            } else {
                                if (data == Object.keys(BooklyCouponL10n.services.collection).length) {
                                    return BooklyCouponL10n.services.allSelected;
                                } else {
                                    return data + '/' + Object.keys(BooklyCouponL10n.services.collection).length;
                                }
                            }
                        }
                    });
                    break;
                case 'staff':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            if (data == 0) {
                                return BooklyCouponL10n.staff.nothingSelected;
                            } else if (data == 1) {
                                if (typeof BooklyCouponL10n.staff.collection[row.staff_ids[0]] === 'undefined') {
                                    return BooklyCouponL10n.staff.nothingSelected;
                                } else {
                                    return BooklyCouponL10n.staff.collection[row.staff_ids[0]].title;
                                }
                            } else {
                                if (data == Object.keys(BooklyCouponL10n.staff.collection).length) {
                                    return BooklyCouponL10n.staff.allSelected;
                                } else {
                                    return data + '/' + Object.keys(BooklyCouponL10n.staff.collection).length;
                                }
                            }
                        }
                    });
                    break;
                case 'customers':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            if (data == 0) {
                                return BooklyCouponL10n.customers.nothingSelected;
                            } else if (data == 1) {
                                let
                                    customer     = BooklyCouponL10n.customers.collection[row.customer_ids[0]],
                                    display_name = customer.full_name
                                ;
                                if (customer.email != '' || customer.phone != '') {
                                    display_name += ' (' + [customer.email,customer.phone].filter(e=> e !== undefined ? e.toString().trim() : false).join(', ') +  ')';
                                }
                                return $.fn.dataTable.render.text().display(display_name);
                            } else {
                                if (data == Object.keys(BooklyCouponL10n.customers.collection).length) {
                                    return BooklyCouponL10n.customers.allSelected;
                                } else {
                                    return data + '/' + Object.keys(BooklyCouponL10n.customers.collection).length;
                                }
                            }
                        }
                    });
                    break;
                case 'date_limit_end':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            return row.date_limit_end_formatted;
                        }
                    });
                    break;
                case 'date_limit_start':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            return row.date_limit_start_formatted;
                        }
                    });
                    break;
                default:
                    columns.push({data: column});
                    break;
            }
        }
    });

    columns.push({
        responsivePriority: 1,
        orderable: false,
        width: 180,
        render: function (data, type, row, meta) {
            return edit_and_duplicate;
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

    $.each(BooklyCouponL10n.datatables.coupons.settings.order, function (_, value) {
        const index = columns.findIndex(c => c.data === value.column);
        if (index !== -1) {
            order.push([index, value.order]);
        }
    });

    /**
     * Init DataTables.
     */
    var dt = $couponsList.DataTable({
        order       : order,
        info        : false,
        searching   : false,
        lengthChange: false,
        pageLength  : 25,
        pagingType  : 'numbers',
        processing  : true,
        responsive  : true,
        serverSide  : true,
        ajax        : {
            url : ajaxurl,
            type: 'POST',
            data: function (d) {
                return $.extend({action: 'bookly_coupons_get_coupons', csrf_token : BooklyCouponL10n.csrfToken}, {
                    filter: {
                        code       : $codeFilter.val(),
                        service    : $serviceFilter.val(),
                        staff      : $staffFilter.val(),
                        customer   : $customerFilter.val(),
                        active     : $onlyActiveFilter.prop('checked') ? 1 : 0
                    }
                }, d);
            }
        },
        columns: columns,
        dom    : "<'row'<'col-sm-12'tr>><'row float-left mt-3'<'col-sm-12'p>>",
        language: {
            zeroRecords: BooklyCouponL10n.zeroRecords,
            processing:  BooklyCouponL10n.processing
        }
    });

    /**
     * Select all coupons.
     */
    $checkAllButton.on('change', function () {
        $couponsList.find('tbody input:checkbox').prop('checked', this.checked);
    });

    $couponsList
        // On coupon select.
        .on('change', 'tbody input:checkbox', function () {
            $checkAllButton.prop('checked', $couponsList.find('tbody input:not(:checked)').length == 0);
        })
        // Edit coupon
        .on('click', '[data-action=edit]', function () {
            row = dt.row($(this).closest('td'));
            series = false;
            duplicate = $(this).data('mode') === 'duplicate';
            $couponModal.booklyModal('show');
        });

    /**
     * New coupon.
     */
    $addButton.on('click', function () {
        series    = false;
        duplicate = false;
        $couponModal.booklyModal('show');
    });

    /**
     * New coupon series.
     */
    $addSeriesButton.on('click', function () {
        series    = true;
        duplicate = false;
        $couponModal.booklyModal('show');
    });

    /**
     * On show modal.
     */
    $couponModal
        .on('show.bs.modal', function () {
            var data = {};
            if (row) {
                data = row.data();
                $couponCode.val(data.code);
                $couponDiscount.val(data.discount);
                $couponDeduction.val(data.deduction);
                $couponUsageLimit.val(data.usage_limit);
                $couponOncePerCst.val(data.once_per_customer);
                $couponDateStart.val(data.date_limit_start !== null ? moment(data.date_limit_start, 'YYYY-MM-DD').format(BooklyCouponL10n.datePicker.format) : '');
                $couponDateStart.next('input:hidden').val(data.date_limit_start);
                $couponDateEnd.val(data.date_limit_end !== null ? moment(data.date_limit_end, 'YYYY-MM-DD').format(BooklyCouponL10n.datePicker.format) : '');
                $couponDateEnd.next('input:hidden').val(data.date_limit_end);
                $couponMinApps.val(data.min_appointments);
                $couponMaxApps.val(data.max_appointments);
                $couponCustomers.val(data.customer_ids).trigger('change');
                $seriesNewTitle.hide();
                if (duplicate) {
                    $couponEditTitle.hide();
                    $couponNewTitle.show();
                } else {
                    $couponEditTitle.show();
                    $couponNewTitle.hide();
                }
                $couponServices.booklyDropdown('setSelected', data.service_ids);
                $couponProviders.booklyDropdown('setSelected', data.staff_ids);
            } else {
                $couponCode.val('');
                $seriesMask.val(BooklyCouponL10n.defaultCodeMask);
                $seriesAmount.val(1);
                $couponDiscount.val('0');
                $couponDeduction.val('0');
                $couponUsageLimit.val('1');
                $couponOncePerCst.val('0');
                $couponDateStart.val('');
                $couponDateEnd.val('');
                $couponMinApps.val('1');
                $couponMaxApps.val('');
                $couponCustomers.val(null).trigger('change');
                $couponEditTitle.hide();
                if (series) {
                    $couponNewTitle.hide();
                    $seriesNewTitle.show();
                } else {
                    $couponNewTitle.show();
                    $seriesNewTitle.hide();
                }
                $couponServices.booklyDropdown('selectAll');
                $couponProviders.booklyDropdown('selectAll');
            }
            $('.bookly-js-series-field').toggle(series);
            $('.bookly-js-coupon-field').toggle(!series);
            $couponCode.trigger('change');
            $createAnother.prop('checked', false);
        })
        .on('hidden.bs.modal', () => row = null);

    /**
     * Code.
     */
    $couponCode.on('keyup change', function () {
        $generateCode.prop('disabled', $couponCode.val().length && $couponCode.val().indexOf('*') === -1);
    });

    /**
     * Generate code.
     */
    $generateCode.on('click', function () {
        var ladda = Ladda.create(this);
        ladda.start();
        $.ajax({
            url  : ajaxurl,
            type : 'POST',
            data : {
                action : 'bookly_coupons_generate_code',
                csrf_token : BooklyCouponL10n.csrfToken,
                mask : $couponCode.val()
            },
            dataType : 'json',
            success  : function(response) {
                ladda.stop();
                if (response.success) {
                    $couponCode.val(response.data.code);
                    $generateCode.prop('disabled', true);
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    /**
     * Date limit start.
     */
    $couponDateStart.daterangepicker({
        parentEl        : '#bookly-coupon-modal',
        singleDatePicker: true,
        showDropdowns   : true,
        autoUpdateInput : true,
        locale          : BooklyCouponL10n.datePicker
    }, function (start, end) {
        $couponDateStart.val(start.format(BooklyCouponL10n.datePicker.format)).trigger('change');
        $couponDateStart.next('input:hidden').val(start.format('YYYY-MM-DD'))
    });
    $clearDateStart.on('click', function () {
        $couponDateStart.val('');
        $couponDateStart.next('input:hidden').val(null);
    });

    /**
     * Date limit end.
     */
    $couponDateEnd.daterangepicker({
        parentEl        : '#bookly-coupon-modal',
        singleDatePicker: true,
        showDropdowns   : true,
        autoUpdateInput : false,
        locale          : BooklyCouponL10n.datePicker
    }, function (start) {
        $couponDateEnd.val(start.format(BooklyCouponL10n.datePicker.format)).trigger('change');
        $couponDateEnd.next('input:hidden').val(start.format('YYYY-MM-DD'))
    });
    $clearDateEnd.on('click', function () {
        $couponDateEnd.val('');
        $couponDateEnd.next('input:hidden').val(null);
    });

    /**
     * Customers list.
     */
    if (BooklyCouponL10n.customers.remote) {
        $couponCustomers.select2({
            width: '100%',
            theme: 'bootstrap4',
            dropdownParent: '#bookly-tbs',
            allowClear: true,
            placeholder: '',
            language: {
                noResults: function () {
                    return BooklyCouponL10n.noResultFound;
                },
                searching: function () {
                    return BooklyCouponL10n.searching;
                }
            },
            ajax      : {
                url           : ajaxurl,
                dataType      : 'json',
                delay         : 250,
                data          : function (params) {
                    params.page = params.page || 1;
                    return {
                        action    : 'bookly_get_customers_list',
                        filter    : params.term,
                        page      : params.page,
                        csrf_token: BooklyCouponL10n.csrfToken
                    };
                },
                processResults: function (data, params) {
                    var customers = [];
                    params.page = params.page || 1;
                    data.results.forEach(function (customer) {
                        BooklyCouponL10n.customers.collection[customer.id] = customer;
                        customers.push({
                            id  : customer.id,
                            text: customer.name
                        });
                    });
                    return {
                        results   : customers,
                        pagination: data.pagination
                    };
                }
            },
        });
    } else {
        $couponCustomers.select2({
            width: '100%',
            theme: 'bootstrap4',
            dropdownParent: '#bookly-tbs',
            allowClear: false,
            placeholder: '',
            language: {
                noResults: function () {
                    return BooklyCouponL10n.noResultFound;
                }
            }
        });
    }

    $couponCustomers.on('change', function () {
        $customersList.empty();
        $couponCustomers.find('option:selected').each(function () {
            var $option = $(this),
                $li     = $('<li class="form-row align-items-center"/>'),
                $span   = $('<span class="col-11 text-truncate"/>'),
                $a      = $('<a class="far fa-fw fa-trash-alt text-danger" href="#"/>')
            ;
            $span.text($option.text());
            $a.on('click', function (e) {
                e.preventDefault();
                var newValues = [];
                $.each($couponCustomers.val(), function (i, id) {
                    if (id !== $option.val()) {
                        newValues.push(id);
                    }
                });
                $couponCustomers.val(newValues);
                $couponCustomers.trigger('change');
            });
            $a.attr('title', BooklyCouponL10n.removeCustomer);
            $li.append($span).append($a);
            $customersList.append($li);
        });
    });

    /**
     * Services.
     */
    $couponServices.booklyDropdown();

    /**
     * Providers (staff).
     */
    $couponProviders.booklyDropdown();

    /**
     * Save coupon.
     */
    $saveButton.on('click', function (e) {
        e.preventDefault();
        var $form = $(this).parents('form');
        var data = $form.serializeArray();
        data.push({name: 'action', value: 'bookly_coupons_save_coupon'});
        if (row && !duplicate) {
            data.push({name: 'id', value: row.data().id});
        }
        if (series) {
            data.push({name: 'create_series', value: '1'});
        }
        var ladda = Ladda.create(this);
        ladda.start();
        $.ajax({
            url  : ajaxurl,
            type : 'POST',
            data : data,
            dataType : 'json',
            success  : function(response) {
                if (response.success) {
                    dt.ajax.reload();
                    if (!series && $createAnother.prop('checked')) {
                        row = null;
                        $couponNewTitle.show();
                        $couponEditTitle.hide();
                        $couponCode.val('');
                        $createAnother.prop('checked', false);
                    } else {
                        $couponModal.booklyModal('hide');
                    }
                } else {
                    alert(response.data.message);
                }
                ladda.stop();
            }
        });
    });

    /**
     * Delete coupons.
     */
    $deleteButton.on('click', function () {
        if (confirm(BooklyCouponL10n.areYouSure)) {
            var ladda = Ladda.create(this);
            ladda.start();

            var data = [];
            var $checkboxes = $couponsList.find('tbody input:checked');
            $checkboxes.each(function () {
                data.push(this.value);
            });

            $.ajax({
                url  : ajaxurl,
                type : 'POST',
                data : {
                    action : 'bookly_coupons_delete_coupons',
                    csrf_token : BooklyCouponL10n.csrfToken,
                    data : data
                },
                dataType : 'json',
                success  : function(response) {
                    if (response.success) {
                        dt.ajax.reload();
                    } else {
                        alert(response.data.message);
                    }
                    ladda.stop();
                }
            });
        }
    });

});