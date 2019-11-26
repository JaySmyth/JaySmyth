var path = window.location.pathname;

if (path.indexOf("/login") != -1) {
    var resolution = window.screen.width + 'x' + window.screen.height;
    $('#screen_resolution').val(resolution);
}

if ($("#hide_nav").length) {
    $('.navbar').hide();
}

// Disable submit button on its first click.
$("input[type=submit]").click(function () {
    $("input[type=submit]").attr('disabled', 'disabled');
});

// Determine if the search box should be displayed in the nav bar
if ($("#advanced_search").length) {
    $('#nav-search').show();
    $('.advanced-search').show();
    $('#filter').focus();
    $('#filter').select();
}

// When a modal is displayed, set focus on the first text element
$('.modal').on('shown.bs.modal', function () {
    $('.modal-body').find('input:text').first().select();
});

// Focus on the tracking number field
$('#tracking_modal').on('shown.bs.modal', function () {
    $('#tracking_number').focus().select();
});

// Check all check boxes
$(".check-all").click(function () {
    var tableRows = $(this).closest("table").children('tbody').children("tr");
    tableRows.find('input:checkbox').prop('checked', this.checked);
    tableRows.toggleClass("warning", this.checked);
});

// Highlight row - toggle class
$(":checkbox").not('.check-all').change(function () {
    $(this).closest("tr").toggleClass("warning", this.checked);
});

// File upload button - display spinner
$(document).on('click', '.upload-file', function () {
    swal({
        title: 'Uploading file...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        html: '<div class="fa fa-spinner fa-pulse fa-4x fa-fw text-primary mt-3 mb-1"></div>'
    });
});

// Enable popovers
$(function () {
    $('[data-toggle="popover"]').popover();
})

// Image popover
$('[data-toggle="img-popover"]').popover({
    html: true,
    trigger: 'hover',
    content: function () {
        return '<img src="' + $(this).data('img') + '" class="' + $(this).data('style') + '" />';
    }
});


// Refresh address book modal after keystroke
$("#address_book_filter").keyup(function () {

    var filter = $(this).val().toUpperCase();
    var table = document.getElementById("address_book_body");
    var tr = table.getElementsByTagName("tr");
    var name;
    var companyName;
    var postcode;

    for (i = 0; i < tr.length; i++) {
        name = tr[i].getElementsByTagName("td")[0];
        companyName = tr[i].getElementsByTagName("td")[1];
        postcode = tr[i].getElementsByTagName("td")[3];

        if (name.innerHTML.toUpperCase().indexOf(filter) > -1 || companyName.innerHTML.toUpperCase().indexOf(filter) > -1 || postcode.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }

});


/**
 * Generic ajax record delete (see quotations for useage)
 */

$(document).on('click', '.delete', function () {
    var title = $(this).attr("title");
    var ajaxURL = $(this).attr("href");
    var row = $(this).closest('tr');
    var recordName = 'record';
    var progressIndicator = false;
    var parent = null;
    var parentId = null;

    if ($(this).data('record-name')) {
        recordName = $(this).data("record-name");
    }

    if ($(this).data('progress-indicator')) {
        progressIndicator = true;
    }

    if ($(this).data('parent')) {
        parent = $(this).data("parent");
    }

    if ($(this).data('parent-id')) {
        parentId = $(this).data("parent-id");
    }

    swal({
        title: title + '?',
        text: "This " + recordName + " will be permanently deleted.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#CC0000",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }).then(function (isConfirm) {

        if (isConfirm) {

            if (progressIndicator) {
                swal({
                    title: 'Deleting ' + recordName + '...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    html: '<div class="fa fa-spinner fa-pulse fa-2x fa-fw text-primary mt-3 mb-1"></div>'
                });
            }

            $.ajax({
                method: 'DELETE',
                url: ajaxURL,
                dataType: "json",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    parent: parent,
                    parentId: parentId,
                },
                success: function () {
                    row.remove();
                    swal("Deleted!", "The record has been deleted.", "success");
                },
                error: function (data) {
                    var response = $.parseJSON(data.responseText);
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                },
            });
        }
    });

    return false;

});


// Address book item clicked (collection request screen)
$(document).on('click', '.select-transport-address', function () {
    var itemId = $(this).attr('id').match(/\d+/);
    getTransportAddress(itemId);
    $('#address_book').modal('hide');
});


// Populate the fields with an address (collection request screen)
function getTransportAddress(id) {
    $.get('/transport-addresses/' + id,
        function (response) {
            // Set the values of the fields
            $.each(response, function (key, value) {
                $('#from_' + key).val(value);
                $('#from_' + key).parent("div").removeClass("has-error");
            });

            $("#address_id").val(response['id']);

        });
}

// Load the address boof and display the results
function loadTransportAddressBook() {

    $('#address_book_body').empty();

    // Show the page loading indicator
    $('.modal-loading').show();

    $.get('/transport-addresses', function (response) {

        $.each(response, function (i, item) {

            var tr = $('<tr id="address-' + item.id + '" class="select-transport-address">').append(
                $('<td>').html('<span class="address-name">' + item.name + '</span>'),
                $('<td>').html('<span class="address-company">' + item.company_name + '</span>'),
                $('<td>').html('<span class="address-address">' + item.address1 + ', ' + item.city + '</span>'),
                $('<td>').html('<span class="address-address">' + item.postcode + '</span>'),
                $('<td>').text(item.country_code),
                $('<td>').html('<a href="/transport-addresses/' + item.id + '" title="Delete ' + item.name + '" class="delete" data-record-name="address"><i class="fas fa-times" aria-hidden="true"></i></a>'));

            tr.appendTo('#address_book_body');
        });

        // Hide the page loading indicator
        $('.modal-loading').hide();

        $("#address_book_filter").focus();
    });
}

// Save address button (collection request screen)
$("#save-transport-address").click(function () {

    var id = $('#address_id').val();
    var url = '/transport-addresses';
    var method = 'POST';

    if (id) {
        url = '/transport-addresses/' + id;
        method = 'PATCH';
    }

    $.ajax({
        url: url,
        type: 'POST',
        method: method,
        data: {
            _token: $("input[name=_token]").val(),
            name: $('#from_name').val(),
            company_name: $('#from_company_name').val(),
            address1: $('#from_address1').val(),
            address2: $('#from_address2').val(),
            city: $('#from_city').val(),
            state: $('#from_state').val(),
            postcode: $('#from_postcode').val(),
            country_code: $('#from_country_code').val(),
            telephone: $('#from_telephone').val(),
            email: $('#from_email').val(),
            type: $('#from_type').val(),
        },
        success: function (data) {

            $('#address_id').val(data);

            if (id) {
                swal("Updated!", 'Address updated successfully.', "success");
            } else {
                swal("Saved!", 'Address saved to address book.', "success");
            }
        },
        error: function (data) {
            // Error...
            var errors = $.parseJSON(data.responseText);

            console.log(errors);

            $.each(errors, function (index, value) {
                $('#' + index).parent("div").addClass("has-error");
            });

        }
    });
});


// ********************************************************************** //
// ******************************* Scroll to Top ************************ //

$('#to_top').hide();
$(window).scroll(function () {
    if ($(window).scrollTop() >= 600) {
        $('#to_top').fadeIn(500);
    } else {
        $('#to_top').fadeOut(500);
    }
});

// Set focus to top of the page
$("#to_top").click(function () {
    $('html, body').animate({
        scrollTop: 0
    }, 0);
});

// ********************************************************************** //

// Bind the jquery ui datepicker to inputs with datepicker class
$('.datepicker').datepicker();

$('[data-toggle="tooltip"]').tooltip();

// Set the defaults for all datepickers
$.datepicker.setDefaults({
    dateFormat: "dd-mm-yy",
    beforeShow: function () {
        setTimeout(function () {
            $('.ui-datepicker').css('z-index', 99999999999999);
        }, 0);
    }
});

// Restrict to numeric only input
$(document).on('keypress', '.numeric-only,.numeric-only-required', function (e) {
    //if the letter is not digit then don't type anything
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$(document).on('keypress', '.decimal-only,.decimal-only-required', function (e) {
    //if the letter is not digit or decimal don't type anything
    if (e.which != 8 && e.which != 0 && e.which != 46 && e.which != 190 && e.which != 110 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

// Prevent focus on readonly inputs
$(document).on('focus', 'input[type=text][readonly]', function () {
    $(this).blur();
});


// back button
$('a.back').click(function () {
    parent.history.back();
    return false;
});


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// --------------------------------------------------------- *** shipments  *** ----------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //


$(".cancel-shipment").click(function () {

    var cancelLink = $(this);
    var ajaxURL = cancelLink.attr("href");
    var tr = cancelLink.closest('tr');
    var consignmentNumber = tr.find('.consignment-number').text();
    var status = tr.find('.status');
    var title = 'Cancel Shipment?';
    var message = "Are you sure you want to cancel " + consignmentNumber + "?";
    var button = 'Yes, cancel the shipment!';

    if (status.text() == 'Saved') {
        title = 'Delete Saved Shipment?';
        message = 'Are you sure want to delete saved shipment ' + consignmentNumber + '?';
        button = 'Yes, delete shipment!'
    }

    swal({
        title: title,
        text: message,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#CC0000',
        confirmButtonText: button,
        closeOnConfirm: false

    }).then(function (isConfirm) {

        if (isConfirm) {

            $.ajax({
                url: ajaxURL,
                success: function () {

                    if (status.text() == 'Saved') {
                        tr.remove();
                    } else {
                        var supportingDocs = tr.find('.supporting-docs');
                        var commercialInvoice = tr.find('.commercial-invoice');
                        var print = tr.find('.print');

                        status.replaceWith('<span class="status cancelled" data-placement="bottom" data-toggle="tooltip" data-original-title="Shipment cancelled">Cancelled</span>');
                        cancelLink.replaceWith('<span class="glyphicon glyphicon-remove margin-left-5 done" aria-hidden="true"></span>');
                        supportingDocs.replaceWith('<span class="glyphicon glyphicon-file margin-left-5 done" aria-hidden="true"></span>');
                        commercialInvoice.replaceWith('<span class="glyphicon glyphicon-list-alt margin-left-5 done" aria-hidden="true"></span>');
                        print.replaceWith('<span class="glyphicon glyphicon-print margin-left-5 done" aria-hidden="true"></span>');
                    }
                }
            });
        }
    })

    return false;
});


/*
 * Web scan shipment.
 */
$(".set-received").click(function () {

    var link = $(this);
    var ajaxURL = link.attr("href");
    var tr = link.closest('tr');
    var consignmentNumber = tr.find('.consignment-number').text();

    swal({
        title: 'Web Scan',
        text: "Set " + consignmentNumber + " to received? This action cannot be reversed.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#66CC00',
        confirmButtonText: 'Yes, set to received!',
        closeOnConfirm: false
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: ajaxURL,
                success: function () {
                    tr.remove();
                    swal('Receipt Status Updated!', 'Shipment ' + consignmentNumber + ' has been marked as received.', 'success');
                }
            });
        }
    })

    return false;
});

/**
 * Update DIMS
 */
$(document).on('click', '.update-dims', function () {
    var id = $(this).attr('id').match(/\d+/);
    var tr = $(this).closest('tr');
    var consignmentNumber = tr.find('.consignment-number').text();
    var packages = tr.find('input[name^="packages"]').serialize();

    swal({
        title: "Update DIMS?",
        text: "Are you sure you want to update the weights and dimesions for shipment " + consignmentNumber + "?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#CC0000",
        confirmButtonText: "Yes, update!",
        closeOnConfirm: false
    }).then(function (isConfirm) {

        if (isConfirm) {

            swal({
                title: 'Updating DIMS...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                html: '<div class="fa fa-spinner fa-pulse fa-2x fa-fw text-primary mt-3 mb-1"></div>'
            });

            $.ajax({
                url: '/shipments/' + id + '/update-dims',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token: $("input[name=_token]").val(),
                    packages: packages
                },
                success: function (data) {
                    if (data == 'success') {
                        tr.remove();
                        swal("Updated!", "The weights and dimensions have been updated.", "success");
                    } else {
                        swal("Error!", "You must supply values for all weights and dimensions.", "warn");
                    }
                }
            });
        }

    });

    return false;

});


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ------------------------------------------------------- *** shipments/{id}  *** -------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

// Hide the additional info panel on page load
$('#panel-show-additional-information').hide();

$(".button-summary").click(function () {
    $('#panel-show-additional-information').hide();
    $('#panel-show-summary').show();
});

$(".button-additional-information").click(function () {
    $('#panel-show-summary').hide();
    $('#panel-show-additional-information').show();
});

// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ------------------------------------------------------- *** sea freight *** -------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

$(".cancel-sea-freight-shipment").click(function () {

    var cancelLink = $(this);
    var ajaxURL = cancelLink.attr("href");
    var tr = cancelLink.closest('tr');
    var consignmentNumber = tr.find('.consignment-number').text();

    swal({
        title: 'Cancel Shipment?',
        text: "Are you sure you want to cancel " + consignmentNumber + "? This action cannot be reversed.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#CC0000',
        confirmButtonText: 'Yes, cancel the shipment!',
        closeOnConfirm: false

    }).then(function (isConfirm) {

        if (isConfirm) {

            $.ajax({
                url: ajaxURL,
                success: function () {
                    var status = tr.find('.status');
                    var supportingDocs = tr.find('.supporting-docs');
                    var addContainer = tr.find('.add-container');
                    var editShipment = tr.find('.edit-shipment');

                    status.replaceWith('<span class="status cancelled" data-placement="bottom" data-toggle="tooltip" data-original-title="Shipment cancelled">Cancelled</span>');
                    cancelLink.replaceWith('<span class="glyphicon glyphicon-remove margin-left-5 done" aria-hidden="true"></span>');
                    supportingDocs.replaceWith('<span class="glyphicon glyphicon-file margin-left-5 done" aria-hidden="true"></span>');
                    addContainer.replaceWith('<span class="glyphicon glyphicon-plus-sign margin-left-5 done" aria-hidden="true"></span>');
                    editShipment.replaceWith('<span class="glyphicon glyphicon-edit margin-left-5 done" aria-hidden="true"></span>');
                }
            });
        }
    });

    return false;
});


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------- ***  CREATE COMPANY FORM *** ------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

$("#company_name").keyup(function () {
    var companyName = $(this).val();
    var city = $('#city').val();
    $('#site_name').val(companyName + ' - ' + city);
});

$("#city").keyup(function () {
    var city = $(this).val();
    var companyName = $('#company_name').val();
    $('#site_name').val(companyName + ' - ' + city);
});


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------- ***  CREATE/EDIT USER FORM *** ------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------------------------------- //


if (path.indexOf("/users/") != -1) {


    $("#email").focusout(function () {
        $.ajax({
            url: '/getroles',
            data: {
                email: $('#email').val(),
                primary_role: $('#primary_role').val(),
            },
            success: function (data) {
                $('#role_id').empty();

                if (data != 'error') {
                    $("#role_id").prop("disabled", false);
                    $.each(data, function (key, value) {
                        $('#role_id').append('<option value=' + key + '>' + value + '</option>');
                    });
                } else {
                    $('#role_id').append('<option value="">Enter a valid email address</option>');
                    $("#role_id").prop("disabled", true);
                }
            }
        });
    });

}

if (path.indexOf("/reports/fedex-customs") != -1) {
    $('#manifest_id').change(function () {
        $('#manifest').submit();
    });
}

if (path.indexOf("manifest-profiles/") != -1) {
    $('#id').change(function () {
        $('#shipments').hide();
        $('#loading').show();
        $('#manifest_profile').submit();
    });
}


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// --------------------------------------------------------- DRIVER MANIFESTS ------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

if (path.indexOf("/driver-manifests/") != -1) {


    $(".set-collected, .set-completed").click(function () {

        var status = 'Collected';
        var link = $(this);
        var ajaxURL = link.attr("href");
        var tr = link.closest('tr');
        var unmanifest = tr.find('.unmanifest-job');
        var jobReference = tr.find('.job-reference').text();
        var type = tr.find('.job-type').text();

        if (link.attr('class') == 'set-completed') {
            status = 'Completed';
        }

        swal({
            title: 'Set to ' + status.toLowerCase() + '?',
            text: "Are you sure you want to mark " + jobReference + " as " + status.toLowerCase() + "? This action cannot be reversed.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#66CC00',
            confirmButtonText: 'Yes, set to ' + status.toLowerCase() + '!',
            closeOnConfirm: false

        }).then(function (isConfirm) {

            if (isConfirm) {

                $.ajax({
                    url: ajaxURL,
                    success: function () {
                        link.remove();
                        unmanifest.remove();
                        var statusSpan = tr.find('.status');
                        statusSpan.replaceWith('<span class="status ' + status.toLowerCase() + '" data-placement="bottom" data-toggle="tooltip" data-original-title="' + status + '">' + status + '</span>');
                    }
                });
            }
        })

        return false;
    });

}


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ------------------------------------------------------------ TRANSPORT JOBS ------------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

if (path.indexOf("/transport-jobs") != -1) {

    // Open the address book and the load results
    $("#transport-address-book").click(function () {
        loadTransportAddressBook();
    });


    /**
     * Cancel transport job.
     */
    $(".cancel-transport-job").click(function () {

        var cancelLink = $(this);
        var ajaxURL = cancelLink.attr("href");
        var tr = cancelLink.closest('tr');
        var jobNumber = tr.find('.job-number').text();

        swal({
            title: 'Cancel Job?',
            text: "Are you sure you want to cancel " + jobNumber + "?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#CC0000',
            confirmButtonText: 'Yes, cancel this job!',
            closeOnConfirm: false

        }).then(function (isConfirm) {

            if (isConfirm) {

                $.ajax({
                    url: ajaxURL,
                    success: function () {
                        var status = tr.find('.status');
                        var edit = tr.find('.edit-transport-job');
                        status.replaceWith('<span class="status cancelled" data-placement="bottom" data-toggle="tooltip" data-original-title="Job cancelled">Cancelled</span>');
                        edit.replaceWith('<span class="glyphicon glyphicon-edit margin-left-5 done" aria-hidden="true"></span>');
                        cancelLink.replaceWith('<span class="glyphicon glyphicon-remove margin-left-5 done" aria-hidden="true"></span>');
                    }
                });
            }
        })

        return false;
    });

}

// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ----------------------------------------------------------- ***  QUOTATIONS *** -------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //


/*
 * Web scan shipment.
 */
$(".toggle-quotation-status").click(function () {

    var link = $(this);
    var ajaxURL = link.attr("href");
    var tr = link.closest('tr');
    var quotationReference = tr.find('.quotation-reference').text();
    var quotationCompany = tr.find('.quotation-company').text();
    var badge = tr.find('.status');
    var message = "Mark quotation " + quotationReference + " (" + quotationCompany + ") as successful?";

    if (badge.hasClass('badge-success')) {
        message = "Mark quotation " + quotationReference + " (" + quotationCompany + ") as unsuccessful?";
    }

    swal({
        title: 'Quotation Status',
        text: message,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#66CC00',
        confirmButtonText: 'Yes, update status!',
        closeOnConfirm: false
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: ajaxURL,
                type: 'POST',
                data: {
                    _token: $("input[name=_token]").val()
                },
                success: function (data) {

                    var message = 'Quote marked as successful';

                    if (data == 'Y') {
                        badge.text('Yes');
                        badge.removeClass('badge-danger').addClass('badge-success');
                    } else {
                        badge.text('No');
                        badge.removeClass('badge-success').addClass('badge-danger');
                        message = 'Quote marked as not successful';
                    }

                    swal('Quotation Updated!', message, 'success');

                }
            });
        }
    });

    return false;
});


// ---------------------------------------------------------------------------------------------------------------------------------------- //
// --------------------------------------------------------- *** customs_entries  *** ----------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

$('.customs-company-id').change(function () {
    var companyId = $(this);
    checkDutyVatFlag(companyId.val());
})

// Populate the fields with an address (collection request screen)
function checkDutyVatFlag(id) {
    $.get('/customs-entries/type/' + id,
        function (data, status) {
            if (data != 1) {
                $("#fullDutyAndVatOnly").hide();
            } else {
                $("#fullDutyAndVatOnly").show();
            }
        }
    );
}

// ---------------------------------------------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------------                          ----------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------------------------------- //

if (path.indexOf("/jobs") != -1) {

    $("#failed").hide();
    getJobs();
    getFailedJobs();

    // Refresh every 5 seconds
    window.setInterval(function () {
        getJobs();
    }, 5000);

    // Refresh every 20 seconds
    window.setInterval(function () {
        getFailedJobs();
    }, 20000);


    $(document).on('click', '.retry-job', function () {
        var button = $(this);
        var id = button.data("job-id");
        var tr = button.closest('tr');

        $.ajax({
            url: 'retry-job',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                tr.remove();
                $('#failed-job-count').text($('#failed-job-count').text() - 1);
            }
        });
    });


    $(document).on('click', '#retry-all', function () {
        $.ajax({
            url: 'retry-all',
            type: 'POST',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $("#failed").hide();
            }
        });
    });


    function getJobs() {

        $.ajax({
            url: 'get-jobs',
            type: 'POST',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                $("#job-queue").empty();

                if (data.data.length > 0) {

                    $.each(data.data, function (key, value) {

                        var html = '<tr><th scope="row">' + value.id + '</th><td><span class="badge badge-secondary text-uppercase">' + value.queue + '</span></td><td class="text-primary font-weight-bold">' + value.display_name + '</td> <td class="text-center"><span class="badge badge-' + value.badge + ' badge-pill">' + value.attempts + '</span></td><td class="text-center">' + value.created_at + '</span></td><td class="text-center">';

                        if (value.reserved_at) {
                            html += value.reserved_at;
                        } else {
                            html += '<i class="fas fa-ellipsis-h text-small faded"></i>';
                        }

                        html += '</td><td class="text-right">';

                        if (value.duration > 0) {
                            html += value.duration + '<i class="fas fa-sync fa-spin text-success ml-2"></i>';
                        } else {
                            html += '<i class="fas fa-ellipsis-h text-small faded"></i>';
                        }

                        html += '</td></tr>';

                        $('#job-count').text(data.count);
                        $("#job-queue").append(html);

                    });

                } else {
                    $('#job-count').text('0');
                    $("#job-queue").append('<tr><td colspan="7" class="text-center font-italic font-weight-bold text-primary text-large">Job queue empty!</td></tr>');
                }

                $('#job-queue-last-updated').text(data.timestamp);

            }
        });

    }


    function getFailedJobs() {

        $.ajax({
            url: 'get-failed-jobs',
            type: 'POST',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                if (data.data.length > 0) {

                    $("#failed-jobs").empty();

                    $.each(data.data, function (key, value) {
                        var html = '<tr><th scope="row">' + value.id + '</th><td><span class="badge badge-secondary text-uppercase">' + value.queue + '</span></td><td class="text-danger font-weight-bold">' + value.display_name + '</td><td class="font-italic">' + value.exception + '...</td><td class="text-center">' + value.failed_at + '</td><td class="text-right"><button type="button" class="btn btn-xs btn-primary retry-job" data-job-id="' + value.id + '"><i class="fas fa-redo"></i></button></td></tr>';
                        $('#failed-job-count').text(data.count);
                        $("#failed-jobs").append(html);
                    });

                    $("#failed").show();
                    $('#failed-jobs-last-updated').text(data.timestamp);

                }
            }
        });

    }

    $("#refresh").click(function () {
        $("#job-queue").fadeOut("slow");
        getJobs();
        getFailedJobs();
        $("#job-queue").fadeIn("slow");
    });


}


if (path.indexOf("/processes") != -1) {

    getProcessess();

    // Refresh every 5 seconds
    window.setInterval(function () {
        getProcessess();
    }, 5000);

    function getProcessess() {
        $.ajax({
            url: 'get-processes',
            type: 'POST',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                $("#running-processes").empty();

                if (data.length > 0) {
                    $.each(data, function (key, value) {
                        key += 1;
                        $("#running-processes").append('<tr><td><span class="badge badge-primary">' + key + '</span></td><td class="log-text"">' + value + '</td></tr>');
                    });
                    $('#process-count').text(data.length);
                } else {
                    $('#process-count').text('0');
                    $("#running-processes").append('<tr><td colspan="2" class="text-center font-italic font-weight-bold text-primary text-large">No running processes!</td></tr>');

                }
            }
        });
    }

}


/**
 * Display log changes modal.
 */
$(document).on('click', '.get-log-data', function () {

    var ajaxURL = $(this).attr("href");

    $.ajax({
        method: 'POST',
        url: ajaxURL,
        dataType: "json",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {

            var obj = JSON.parse(data);
            var html = '<table class="table table-striped table-sm"><tbody>';

            $.each(obj, function (key, value) {
                html += '<tr><th scope="row">' + key + '</th>';
                html += '<td>' + value + '</td>';
            });

            html += '</tbody></table>';

            $('#log-data-body').empty();
            $("#log-data-body").append(html);
            $('#log-data').modal();

        }
    });

    return false;

});


/**
 * Expands hidden table rows when see more button clicked.
 */
$(".see-more").click(function () {
    var target = $(this).data("see-more");
    $(target + ' tr').removeClass('d-none');
    $(this).remove();
});
