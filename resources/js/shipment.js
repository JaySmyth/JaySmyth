var path = window.location.pathname;

if (path.indexOf("/shipments") != -1 || path === '/') {

    // Prevent form submission from enter key
    $('#create-shipment').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $('#summary').hide();
    $('#panel-sender').hide();
    $('#panel-alerts').hide();
    $('#panel-billing').hide();
    $('#panel-broker').hide();
    $('#panel-invoice').hide();
    $('#panel-options').hide();
    $('#panel-docs-only').hide();
    $('#panel-goods-description').hide();

    $("#button-sender").click(function () {
        $('#panel-recipient').hide();
        $('#panel-sender').show();
        $('#sender_name').focus();
    });

    $("#button-recipient").click(function () {
        $('#panel-sender').hide();
        $('#panel-recipient').show();
        $('#recipient_name').focus();
    });

    $("#button-alerts").click(function () {
        $('#panel-details').hide();
        $('#panel-alerts').show();
    });

    $("#button-alerts-return").click(function () {
        $('#panel-alerts').hide();
        $('#panel-details').show();
    });

    $("#button-billing").click(function () {
        $('#panel-details').hide();
        $('#panel-billing').show();
    });

    $("#button-billing-return").click(function () {
        $('#panel-billing').hide();
        $('#panel-details').show();
    });

    $("#button-broker").click(function () {
        $('#panel-details').hide();
        $('#panel-broker').show();
    });

    $("#button-broker-return").click(function () {
        $('#panel-broker').hide();
        $('#panel-details').show();
    });

    $("#button-invoice").click(function () {
        $('#panel-details').hide();
        $('#panel-invoice').show();
    });

    $("#button-invoice-return").click(function () {
        $('#panel-invoice').hide();
        $('#panel-details').show();
    });

    $("#button-options").click(function () {
        $('#panel-details').hide();
        $('#panel-options').show();

        if ($('#alcohol-type').val() == '') {
            $("#alcohol-fieldset").prop("disabled", true);
        } else {
            $("#alcohol-fieldset").prop("disabled", false);
        }

        if ($('#dry-ice-flag').val() == '') {
            $("#dry-ice-fieldset").prop("disabled", true);
        } else {
            $("#dry-ice-fieldset").prop("disabled", false);
        }

    });

    $("#recipient_email").change(function () {
        $("#display_recipient_email").val($(this).val());
    });

    $("#recipient_account_number").change(function () {

        if ($("#bill_shipping").val() === 'recipient') {
            $("#bill_shipping_account").val($(this).val());
        }

        if ($("#bill_tax_duty").val() === 'recipient') {
            $("#bill_tax_duty_account").val($(this).val());
        }

    });

    $("#bill_shipping").change(function () {

        $("#bill_shipping_account").val('');

        if ($(this).val() === 'recipient' && $("#recipient_account_number").val().length > 0) {
            $("#bill_shipping_account").val($("#recipient_account_number").val());
        }
    });

    $("#bill_tax_duty").change(function () {

        $("#bill_tax_duty_account").val('');

        if ($(this).val() === 'recipient' && $("#recipient_account_number").val().length > 0) {
            $("#bill_tax_duty_account").val($("#recipient_account_number").val());
        }
    });


    $("#sender_email").change(function () {
        $("#display_sender_email").val($(this).val());
    });

    $("#broker_email").change(function () {
        $("#display_broker_email").val($(this).val());
    });

    $("#button-options-return").click(function () {
        $('#panel-options').hide();
        $('#panel-details').show();
    });

    // Set the options on the collection date picker
    $("#collection_date").datepicker({
        dateFormat: 'dd-mm-yy',
        beforeShowDay: $.datepicker.noWeekends,
        minDate: 0,
        maxDate: "+2w",
    });

    if ($("#collection_date").val() == '') {
        $("#collection_date").datepicker("setDate", new Date());
    }

    // Display popover
    $('#insurance_value').popover({
        title: 'Insured Shipment',
        content: "Please note that insured shipments will incur an additional charge. This charge is not reflected in the total shipping cost on the following screen. For more information please contact the courier department",
        trigger: "focus"
    })

    // Set the hidden commodity count
    var commodityCount = $('#container-contents').find('.item').length;
    setCommodityCount(commodityCount);

    /**
     * If the shipper drop down is present, load the user's preferences on change.
     * Otherwise, user only has one company, load the user's preferences for this
     * company record (taken from hidden input field).
     */
    if ($('#company_id').is('select')) {

        if (!$('#company_id').val() > 0) {
            // Disable the form until the user selects a shipper
            $("#company_id").focus();
            $('#shipment :input').not('#company_id').attr('disabled', true);
        }

        // User selects a value from shipper drop down
        $("#company_id").change(function () {

            if ($('#company_id').val() > 0) {

                // Load the localisation settings for this company
                getLocalisation();

                // Load packaging types for this company ID
                getPackagingTypes();

                // Load the users preferences OR saved shipment and update the form accordingly
                getDefaultsOrSavedShipment();

                // Enable the form for editing
                $(':input').not('#company_id').attr('disabled', false);

                $('#button-create').attr('disabled', true);

                // Set focus on the recipient name
                $("#recipient_name").focus();
            } else {
                // User has un-selected the shipper, so disable the form until shipper has been selected
                $(':input').not('#company_id').attr('disabled', true);
                $("#company_id").focus();
            }
        });

        // User has only one company record, so load the user's preferences for this company
    } else {

        if ($('#data_loaded').val() == 'false') {
            getDefaultsOrSavedShipment();
        }
        $("#recipient_name").focus();
    }

    // Autocomplete sender recipient
    $("#recipient_name, #sender_name").autocomplete({
        source: function (request, response) {

            var definition = getAddressBookDefinition(this.element.attr('id'))

            $.ajax({
                url: "/addresses/autocomplete",
                data: {
                    term: request.term,
                    company_id: $("#company_id").val(),
                    definition: definition
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        messages: {
            noResults: '',
            results: function () {
            }
        },
        minLength: 3,
        select: function (event, ui) {
            getAddress(ui.item.id);
            event.preventDefault();

            $(this).val('');

            // set focus on the shipment reference field
            $("#pieces").focus();
        }
    });


    // Highlight the text within the input upon focus
    $('#pieces').focusin(function () {
        $(this).select();
    });

    // set the visibility of the duplicate button
    if ($('#pieces').val() == 1) {
        $('#button-duplicate').hide();
    }

    $("#commodity_filter").keyup(function () {
        loadCommodities();
    });

    $("#commodity_filter_currency").change(function () {
        loadCommodities();
    });

    $("#set-preferences").click(function () {

        if ($('#company_id').val() > 0) {

            var message = 'The current values on screen will be set as the default values to be loaded for every new shipment.'
            var successMessage = 'Your defaults have been set.';

            if ($('#company_id').is('select')) {
                var shipper = $("#company_id option:selected").text();
                message = "As you have multiple companies associated with your account, the current values on screen will be set as the default values to be loaded for every new shipment against " + shipper + "."
                successMessage = 'Your defaults have been set for ' + shipper + '.';
            }

            swal({
                title: 'Set Defaults?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CC0000',
                confirmButtonText: 'Yes, set defaults!',
                closeOnConfirm: false

            }).then(function (isConfirm) {

                if (isConfirm) {

                    var token = $("input[name=_token]").val();
                    var values = $('#create-shipment').find('input:text,select,:input:checkbox,textarea,input[name^="contents"],#commodity_count').serialize();

                    $.ajax({
                        url: '/preferences',
                        type: 'POST',
                        data: {
                            _method: 'PUT',
                            _token: token,
                            values: values,
                            company_id: $('#company_id').val(),
                            mode_id: $('#mode_id').val()
                        },
                        success: function (response) {
                            swal('Defaults Set!', successMessage, 'success');
                        }
                    });
                }
            })
        }
    });

    $("#reset-preferences").click(function () {

        if ($('#company_id').val() > 0) {
            var message = 'Your defaults will be removed so that the form is not prepopulated with data.'
            var successMessage = 'Your defaults have been removed.';

            if ($('#company_id').is('select')) {
                var shipper = $("#company_id option:selected").text();
                message = "As you have multiple companies associated with your account, your defaults saved against the selected shipper '" + shipper + "' will be removed."
                successMessage = 'Your defaults have been removed for ' + shipper + '.';
            }

            swal({
                title: 'Reset Defaults?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CC0000',
                confirmButtonText: 'Yes, reset defaults!',
                closeOnConfirm: false
            }).then(function (isConfirm) {

                if (isConfirm) {

                    $.ajax({
                        url: '/preferences',
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $("input[name=_token]").val(),
                            company_id: $('#company_id').val(),
                            mode_id: $('#mode_id').val()
                        },
                        success: function (response) {
                            swal('Defaults Reset!', successMessage, 'success');
                        }
                    });
                }

            })
        }
    });

    $("#ship_reason").change(function () {
        setDocumentsOnlyVisibility($(this).val());
        setDomestic();
    });

    setDocumentsOnlyVisibility($("#ship_reason").val());

    setDomestic();

    $("#recipient_country_code").change(function () {
        setDomestic();
        getStates($(this).val(), 'recipient');
        $("#ultimate_destination_country_code").val($(this).val());
    });


    $("#dry-ice-flag").change(function () {
        if ($(this).val()) {
            $("#dry-ice-fieldset").prop("disabled", false);
        } else {
            $("#dry-ice-fieldset").prop("disabled", true);
        }
    });

    $("#alcohol-type").change(function () {
        if ($(this).val()) {
            $("#alcohol-fieldset").prop("disabled", false);
        } else {
            $("#alcohol-fieldset").prop("disabled", true);
        }
    });

    $("#pieces").change(function () {
        setPieces($(this).val());
    });

    $("#sender_country_code").change(function () {
        getStates($(this).val(), 'sender');
    });


    /**
     * Open the address book and load results
     */
    $("#button-sender-address-book, #button-recipient-address-book").click(function () {
        var definition = getAddressBookDefinition($(this).attr('id'));
        loadAddressBook(definition);
    });


    /**
     *
     *
     * @param null
     * @returns void
     */
    function loadAddressBook(definition) {

        $('#address_book_title').empty().append(definition + " Address Book");
        $('#address_book_body').empty();
        $("#address_book_filter").val('');

        // Show the page loading indicator
        $('.modal-loading').show();

        $.get("/addresses", {
            company_id: $("#company_id").val(),
            definition: definition
        }, function (response) {

            var htmlArray = [];

            $.each(response, function (i, item) {
                htmlArray.push('<tr id="address-' + item.id + '" class="select-address"><td><span class="address-name">' + item.name + '</span></td><td><span class="address-company">' + item.company_name + '</span></td><td><span class="address-address">' + item.address1 + ', ' + item.city + ' ' + item.postcode + '</span></td><td>' + item.country_code + '</td><td><a href="/addresses/' + item.id + '" title="Delete ' + item.name + '" class="delete" data-record-name="address"><i class="fas fa-times" aria-hidden="true"></i></a></td></tr>');
            });

            $('#address_book_body').append(htmlArray.join(""));

            // Hide the page loading indicator
            $('.modal-loading').hide();

            $("#address_book_filter").focus();
        });
    }


    // Clear the address fields button
    $("#clear-sender-address, #clear-recipient-address").click(function () {

        var definition = getAddressBookDefinition($(this).attr('id'));
        var inputs = $('#panel-' + definition).find('input, select');

        $.each(inputs, function () {
            if ($(this).attr('id') != 'company_id') {
                $(this).val('');
                $(this).parent("div").removeClass("has-danger");
            }
        });

        $("#display_" + definition + "_email").val('');

        if (definition === 'recipient' && $('#bill_shipping').val() === 'recipient') {
            $('#bill_shipping_account').val('');
        }

        if (definition === 'recipient' && $('#bill_tax_duty').val() === 'recipient') {
            $('#bill_tax_duty_account').val('');
        }

        // Set focus on the sender/recipient name
        $('#' + definition + '_name').focus();
    });


// Save address button
    $("#save-sender-address, #save-recipient-address").click(function () {

        var definition = getAddressBookDefinition($(this).attr('id'));

        var id = $('#' + definition + '_id').val();
        var url = '/addresses';
        var method = 'POST';
        var accountNumber = '';
        var address3 = '';

        if (id) {
            url = '/addresses/' + id;
            method = 'PATCH';
        }

        if (definition === 'recipient') {
            accountNumber = $('#recipient_account_number').val();
        } else {
            address3 = $('#sender_address3').val();
        }

        $.ajax({
            url: url,
            type: 'POST',
            method: method,
            data: {
                _token: $("input[name=_token]").val(),
                name: $('#' + definition + '_name').val(),
                company_name: $('#' + definition + '_company_name').val(),
                address1: $('#' + definition + '_address1').val(),
                address2: $('#' + definition + '_address2').val(),
                address3: address3,
                city: $('#' + definition + '_city').val(),
                state: $('#' + definition + '_state').val(),
                postcode: $('#' + definition + '_postcode').val(),
                country_code: $('#' + definition + '_country_code').val(),
                telephone: $('#' + definition + '_telephone').val(),
                email: $('#' + definition + '_email').val(),
                account_number: accountNumber,
                type: $('#' + definition + '_type').val(),
                definition: definition,
                company_id: $('#company_id').val(),
            },
            success: function (data) {

                $('#' + definition + '_id').val(data);

                if (id) {
                    swal("Updated!", 'Address updated successfully.', "success");
                } else {
                    swal("Saved!", 'Address saved to ' + definition + ' address book.', "success");
                }
            },
            error: function (data) {
                // Error...
                var response = $.parseJSON(data.responseText);

                console.log(response);

                $.each(response['errors'], function (index, value) {
                    $('#' + definition + '_' + index).parent("div").addClass("has-danger");
                });

            }
        });
    });


// Open the address book and load results
    $("#button-add-commodity").click(function () {
        $("#commodity_filter").val('');
        loadCommodities();

        // Set the default currency
        if ($('#commodity_filter_currency').val() === '') {
            $('#commodity_filter_currency').val($('#currency_code').val());
        }

    });

    $(document).on('click', '.duplicate-commodity', function () {
        var commodityCount = $("#commodity_count").val();
        var selectedOption = $(this).closest('.item').find('.package-index').val();
        var newRow = $(this).closest('.item').clone(true, true).appendTo('#container-contents');
        commodityCount++;
        setCommodityCount(commodityCount);
        updateInputAttributes('#container-contents');
        newRow.find('.package-index').val(selectedOption);
    });

    $(document).on('click', '.remove-commodity', function () {
        $(this).closest('.item').remove();
        var commodityCount = $("#commodity_count").val();
        commodityCount--;
        setCommodityCount(commodityCount);
        updateInputAttributes('#container-contents');
    });


    /*
     * Open the address book and load results
     */
    $(document).on('click', '#button-fill', function () {

        var pieces = $("#pieces").val();
        var commodityCount = $("#commodity_count").val();

        if (commodityCount == 1 && pieces > 1) {

            var row = $('#container-contents').find('.item').first();
            var loop = pieces - commodityCount;

            for (var i = 1; i <= loop; i++) {
                row.clone(true, true).appendTo('#container-contents');
                commodityCount++;
            }

            var i = 1;
            $('#container-contents').find('select').each(function () {
                $(this).val(i);
                i++;
            });

            setCommodityCount(commodityCount);
            updateInputAttributes('#container-contents');

            // set focus on the 1st quantity field
            $('#contents-0-quantity').focus();

        }

    });

    $(document).on('click', '#button-duplicate', function () {

        var packagingId = $('#packages-0-packaging-code').val();
        var weight = $('#packages-0-weight').val();
        var length = $('#packages-0-length').val();
        var width = $('#packages-0-width').val();
        var height = $('#packages-0-height').val();

        for (i = 0; i < $("#pieces").val(); i++) {
            $('#packages-' + i + '-packaging-code').val(packagingId);
            $('#packages-' + i + '-weight').val(weight);
            $('#packages-' + i + '-length').val(length);
            $('#packages-' + i + '-width').val(width);
            $('#packages-' + i + '-height').val(height);
        }

    });


    /**
     * Check that the contents of each package has been specified by the user.
     * Alerts the user if action is required.
     *
     * @returns {Boolean}
     */
    function validateContents() {

        // No need to define shipment contents for docs or domestic shipments
        if ($('#ship_reason').val() == 'documents' || isDomestic()) {
            return true;
        }

        var pieces = $('#pieces').val();
        var passValidation = [];

        for (var i = 1; i <= pieces; i++) {

            passValidation[i] = false;

            $(".package-index option:selected").each(function () {
                if ($(this).val() == i) {
                    passValidation[i] = true;
                }
            });
        }

        for (var i = 1; i <= pieces; i++) {

            if (passValidation[i] === false) {

                swal({
                    title: "Shipment Contents",
                    text: "Please add commodity details to your shipment.",
                    type: "error"
                })

                return false;
            }
        }

        return true;

    }


    $('#button-proceed').click(function () {

        $('#sender-panel-validator').val(0);
        $('#recipient-panel-validator').val(0);

        if ($("#create-shipment").valid() && validateContents()) {
            setTotals();
            getServices();
            setSummaryValues();

            // Hide the main shipment form
            $('#shipment').hide();

            // Enable the submit button
            $("#button-create").prop("disabled", false);

            // Show the summary screen
            $('#summary').show();
        }
    });

    $('#button-previous').click(function () {

        $('#service_id').val(0);
        $('#shipping_charge').val(0);
        $('#summary_service').text('');
        $('#summary_total').text('');
        $('#summary_volumetric_weight').text('');

        $('#summary').hide();

        // Disable the submit button
        $("#button-create").prop("disabled", false);

        // Show the main shipment form
        $('#shipment').show();
    });

    $('#button-new-commodity').click(function () {

        $('.lightbox-form-title').text('Create Commodity');
        $('#commodity-list').hide();
        $('#commodity-form').show();

        // Clear the form fields
        $(':input', '#commodity-form').val('');

        $('#commodity_description').focus();

        // Set the default values
        $('#commodity_currency_code').val($('#currency_code').val());
        $('#commodity_weight_uom').val($('#weight_uom').val());
        $('#commodity_country_of_manufacture').val($('#sender_country_code').val());
        $('#commodity_uom').val('EA');

    });

    $(document).on('click', '.edit-commodity', function () {

        $('#commodity-list').hide();

        // Show the page loading indicator
        $('.modal-loading').show();

        // get numeric section of id
        var id = $(this).attr('id').match(/\d+/);

        $.get("/commodities/" + id,
            function (response) {

                // Hide the page loading indicator
                $('.modal-loading').hide();

                $.each(response, function (key, value) {
                    $("#commodity_" + key).val(value);
                });

                $('.lightbox-form-title').text('Edit Commodity');

                $('#commodity-form').show();
            });

    });


    $('#button-cancel-commodity').click(function () {
        $('#commodity-form').hide();
        $('#commodity-list').show();
    });

    $('#button-save-commodity').click(function () {

        if ($("#commodity-form").valid()) {

            var id = $('#commodity_id').val();
            var url = '/commodities';
            var method = 'POST';

            if (id) {
                url = '/commodities/' + id;
                method = 'PATCH';
            }

            $.ajax({
                url: url,
                type: 'POST',
                method: method,
                data: {
                    _token: $("input[name=_token]").val(),
                    description: $('#commodity_description').val(),
                    product_code: $('#commodity_product_code').val(),
                    commodity_code: $('#commodity_commodity_code').val(),
                    harmonized_code: $('#commodity_harmonized_code').val(),
                    uom: $('#commodity_uom').val(),
                    manufacturer: $('#commodity_manufacturer').val(),
                    country_of_manufacture: $('#commodity_country_of_manufacture').val(),
                    unit_value: $('#commodity_unit_value').val(),
                    currency_code: $('#commodity_currency_code').val(),
                    unit_weight: $('#commodity_unit_weight').val(),
                    weight_uom: $('#commodity_weight_uom').val(),
                    company_id: $('#company_id').val()
                },
                success: function () {
                    $('#commodity-form').hide();
                    $('#commodity-list').show();
                    loadCommodities();
                }
            });

        }
    });

    $(document).on('click', '.service', function () {
        selectService($(this).attr('id'));
    });


// When submit is clicked, disable any irrelevant fields to clean up data sent to API
    $("#create-shipment").submit(function (event) {

        $("#address_book_definition").prop("disabled", true);

        if ($('#alcohol-type').val() == '') {
            $("#alcohol-type").prop("disabled", true);
            $("#alcohol-fieldset").prop("disabled", true);
        }

        if ($('#broker-company').val() || $('broker-contact').val() || $('broker-id').val()) {
            $("#broker-fieldset").prop("disabled", false);
        } else {
            $("#broker-fieldset").prop("disabled", true);
        }

        if ($('#dry-ice-flag').val() == '') {
            $("#dry-ice-flag").prop("disabled", true);
            $("#dry-ice-fieldset").prop("disabled", true);
        }

        if ($('#ship_reason').val() != 'documents') {
            $("#documents_description").prop("disabled", true);
        }

        if (!isDomestic() || $('#ship_reason').val() == 'documents') {
            $("#goods_description").prop("disabled", true);
        }

        if (isDomestic()) {
            $('input[name^="contents"]').prop("disabled", true);
        }

        // Serialized form values
        var values = $('#create-shipment').find('input:text,select,:input:checkbox,textarea,input[name^="contents"],#commodity_count').serialize();
        $('#form_values').val(values);

        swal({
            title: 'Creating shipment...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            html: '<p>Please wait while we contact the carrier</p><div class="fas fa-spinner fa-pulse fa-4x fa-fw text-primary mt-3 mb-1"></div>'
        });

        return true;

    });


    /**
     *
     *
     * @param null
     * @returns boolean
     */

    function isDomestic() {

        var domesticCountries = ['GB', 'IE'];
        var countryCode = $('#recipient_country_code').val();

        if ($.inArray(countryCode, domesticCountries) !== -1) {
            return true;
        }

        return false;
    }


    /**
     *
     *
     * @param null
     * @returns void
     */

    function setDomestic() {

        if ($("#ship_reason").val() != 'documents') {

            if (isDomestic()) {
                $('#panel-contents').hide();
                $('#panel-goods-description').show();
                $('#commercial_invoice').hide();

                // Clear Commodity form fields as not required
                for (var i = 0; i < $("#commodity_count").val(); i++) {
                    $('#commodity-' + i).remove();
                }

                setCommodityCount(0);
                updateInputAttributes('#container-contents');
            } else {
                $('#panel-contents').show();
                $('#commercial_invoice').show();
                $('#panel-goods-description').hide();
            }
        }

    }


    /**
     *
     *
     * @param null
     * @returns void
     */

    function setDocumentsOnlyVisibility(value) {
        if (value == 'documents') {
            $('#panel-contents').hide();
            $('#panel-goods-description').hide();
            $('#panel-docs-only').show();
            $('#commercial_invoice').hide();
        } else {
            $('#panel-docs-only').hide();
            $('#panel-contents').show();
            $('#commercial_invoice').show();
        }
    }

    // Get packaging types for company
    function getPackagingTypes() {
        $.get('/packaging', {
            company_id: $('#company_id').val(),
            mode_id: $('#mode_id').val()
        }, function (data) {
            $('.packaging-type').empty();
            $.each(data, function (key, value) {
                $('.packaging-type').append('<option value=' + value['code'] + '>' + value['description'] + '</option>');
            });
        }, "json");
    }

    // Get the dims for a packaging type
    function getDims(code, id) {
        $.get('/packaging/dims', {
            company_id: $('#company_id').val(),
            mode_id: $('#mode_id').val(),
            code: code
        }, function (data) {
            $.each(data, function (key, value) {
                $('#packages-' + id + '-' + key).val(value);
            });
        }, "json");
    }

    // Populate the dims if packaging type changes
    $(document).on('change', '.packaging-type', function () {
        getDims($(this).val(), $(this).attr('id').match(/\d+/));
    });


    function getStates(countryCode, field, value) {

        return $.get('/states', {
            country_code: countryCode,
        }, function (data) {

            if (data.length == 0) {
                // no states
                if ($('#' + field + '_state').is("select")) {
                    $('#' + field + '-state-placeholder').empty();
                    $('#' + field + '-state-placeholder').append('<input id="' + field + '_state" class="form-control form-control-sm" name="' + field + '_state" type="text">');
                }

            } else {
                // has states
                $('#' + field + '-state-placeholder').empty();
                $('#' + field + '-state-placeholder').append('<select id="' + field + '_state" class="form-control form-control-sm" name="' + field + '_state"><option value="">Please select</option></select>');

                $.each(data, function (key, val) {
                    $('#' + field + '_state').append('<option value=' + key + '>' + val + '</option>');
                });
            }

            // Set the value of the field if passed through
            if (value) {
                $('#' + field + '_state').val(value);
            }

        });
    }

    /**
     * Get the localisation settings for a company
     *
     * @param null
     * @returns void
     */
    function getLocalisation() {

        $.get('/localisation', {
            company_id: $('#company_id').val()
        }, function (data) {

            $('#dims_uom').val(data['dims_uom']);
            $('#weight_uom').val(data['weight_uom']);
            $('#currency_code').val(data['currency_code']);
            //$('#date_format').val(data['date_format']);

            $(".localisation-weight").each(function () {
                if ($(this).text() != data['weight_uom']) {
                    $(this).empty();
                    $(this).append(data['weight_uom']);
                }
            });

            $(".localisation-dims").each(function () {
                if ($(this).text() != data['dims_uom']) {
                    $(this).empty();
                    $(this).append(data['dims_uom']);
                }
            });

        }, "json");

    }

    /**
     * Get the users shipping preferences (default values) or load in a saved shipment.
     */
    function getDefaultsOrSavedShipment() {

        $('#loading').show();

        if ($('#shipment_id').val() > 0) {

            // Load saved shipment data
            $.get('/shipments/get', {
                id: $('#shipment_id').val()
            }, function (data) {
                populateForm(data);

                if ($("#form_view").length) {
                    $('#shipment :input').not('.btn').attr('disabled', true);
                }

                $('#loading').hide();
            }, "json");

        } else {

            // Load user defaults (preferences)
            $.get('/preferences', {
                company_id: $('#company_id').val(),
                mode_id: $('#mode_id').val()
            }, function (data) {
                populateForm(data);
                $('#loading').hide();
            }, "json");
        }
    }

    /**
     *
     * @param {type} data
     * @returns {undefined}
     */
    function populateForm(data) {
        // reset the entire form before loading in new values
        $(':input', '#create-shipment')
            .not(':button, :submit, :reset, :hidden, #company_id, #collection_date, input[name="save_sender"], input[name="save_recipient"]')
            .val('')
            .removeAttr('selected')
            .removeAttr('checked');

        // reset the hidden address ids
        $('#sender_id').val('');
        $('#recipient_id').val('');

        // reset the select boxes
        $("select").not('#company_id').prop("selected", false);
        $("select").not('#company_id').prop("selectedIndex", 0);

        // Load the states drop down/text
        getStates(data['recipient_country_code'], 'recipient', data['recipient_state']);
        getStates(data['sender_country_code'], 'sender', data['sender_state']);

        // set commodities to zero
        setCommodityCount(0);

        for (var i = 0; i < data['commodity_count']; i++) {
            addCommodity(data['contents.' + i + '.id'], data['contents.' + i + '.package_index'], data['contents.' + i + '.quantity'], data['contents.' + i + '.unit_weight'], data['contents.' + i + '.unit_value']);
        }

        // set packages to one
        setPieces(1);

        // set the piece count and package lines
        setPieces(data['pieces']);

        // loop through all of the other fields and set the values
        $.each(data, function (field, value) {

            if (field != 'pieces' && field != 'commodity_count') {

                // if the field name contains a ".", replace it with a dash
                if (/\./i.test(field)) {
                    field = field.replace(/\./g, "-");
                    field = field.replace(/\_/g, "-");
                }

                // if field is a checkbox, set the checked attribute to true
                if ($("#" + field).is("input:checkbox")) {
                    $("#" + field).prop("checked", true);
                }

                $('#' + field).val(value);
            }

        });

        // set the visibility of docs only
        setDocumentsOnlyVisibility(data['ship_reason']);

        setDomestic();

        // Update the email fields in the alerts panel
        $("#display_recipient_email").val(data['recipient_email']);
        $("#display_sender_email").val(data['sender_email']);
        $("#display_broker_email").val(data['broker_email']);

        $('#data_loaded').val('true');

    }


    /**
     * Update the hidden commodity count input and set visibility of related elements
     *
     * @param null
     * @returns void
     */
    function setCommodityCount(commodityCount) {

        // Commodity count set to zero so remove commodity elements
        if (commodityCount == 0) {
            $('#container-contents').find('.item').remove();
        }

        // Update the hidden file with the commodity count
        $("#commodity_count").val(commodityCount);

        // show or hide the customs info box
        if (commodityCount <= 0) {
            $('#commodity-headings').hide();
            $('#customs-information').show();
        } else {
            $('#customs-information').hide();
            $('#commodity-headings').show();
        }

        //Set the visibility of the fill commodities button
        setFillVisibility();
    }


    /**
     * Sets the visibility of the fill commodities button
     *
     * @param null
     * @returns void
     */
    function setFillVisibility() {
        if ($("#pieces").val() > 1 && $("#commodity_count").val() == 1) {
            $('#button-fill').show();
        } else {
            $('#button-fill').hide();
        }
    }

    /**
     * Populate the address panel fields with an address (sender or recipient)
     *
     * @param  integer id
     * @returns void
     */
    function getAddress(id) {

        $.get("/addresses/" + id,
            function (response) {

                // Load the states drop down/text field
                getStates(response['country_code'], response['definition'], response['state']);

                // Set the values of the fields
                $.each(response, function (key, value) {
                    $("#" + response['definition'] + "_" + key).val(value);
                    $("#" + response['definition'] + "_" + key).parent("div").removeClass("has-danger");
                });

                setDomestic();

                $("#display_" + response['definition'] + "_email").val(response['email']);

                if (response['definition'] == 'recipient') {

                    $("#ultimate_destination_country_code").val(response['country_code']);

                    if ($('#bill_shipping').val() === 'recipient') {
                        $('#bill_shipping_account').val(response['account_number']);
                    }

                    if ($('#bill_tax_duty').val() === 'recipient') {
                        $('#bill_tax_duty_account').val(response['account_number']);
                    }

                }

            });
    }

    // Address book item clicked
    $(document).on('click', '.select-address', function () {
        var itemId = $(this).attr('id').match(/\d+/);
        getAddress(itemId);
        $('#address_book').modal('hide');
    });

    /**
     * Returns a string of sender or recipient
     *
     * @param string text
     * @returns string
     */
    function getAddressBookDefinition(text) {
        var definition = 'recipient';
        if (/sender/i.test(text)) {
            definition = 'sender';
        }
        return definition;
    }


    /**
     * Get the largest girth.
     *
     * @returns integer
     */
    function getLargestGirth() {

        var largestGirth = 0;

        for (i = 0; i < $("#pieces").val(); i++) {
            var girth = eval($('#packages-' + i + '-length').val()) + (eval($('#packages-' + i + '-width').val()) + eval($('#packages-' + i + '-height').val())) * 2;

            if (girth > largestGirth) {
                largestGirth = girth;
            }
        }

        return largestGirth;
    }

    // Get the largest girth on dim change
    $(document).on('change', '.dim-input', function () {

        var largestGirth = getLargestGirth();

        if (largestGirth > 330) {
            $('#largest_girth').text('Largest girth: ' + largestGirth + 'cm');
            $('#largest_girth').show();
        } else {
            $('#largest_girth').hide();
        }
    });


    /**
     *
     *
     * @param integer pieces
     * @returns void
     */
    function setPieces(pieces) {

        // Ensure that the piece count is never zero
        if (!pieces || pieces <= 0) {
            pieces = 1;
        }

        $("#pieces").val(pieces);

        var commodityCount = $("#commodity_count").val();

        var count = $('#container-packages').find('.item').length;
        var difference;

        // piece count has increased
        if (pieces > 1 && pieces > count) {

            difference = pieces - count;

            for (var i = 1; i <= difference; i++) {

                // Clone the first row and append it to #container-packages
                var row = $('#container-packages').find('.item').first().clone(true, true).appendTo('#container-packages');

                var index = $('#container-packages').find('.item').length;

                var selectedPackagingType = $('#container-packages').find('.packaging-type').first().val();

                // update the selected packaging type
                row.find('.packaging-type').val(selectedPackagingType);

                // update the package number display
                row.find('.package-number').empty().append(index);

                // Update the package index select boxes
                var inputs = $('.package-index');

                $.each(inputs, function () {
                    $(this).append($('<option>', {
                        value: index,
                        text: index
                    }));
                });


                // Dry ice - clone the first row and append it to #dry-ice-fieldset
                var row = $('#dry-ice-fieldset').find('.item').first().clone(true, true).appendTo('#dry-ice-fieldset');

                // update the package number display
                row.find('.package-number').empty().append(index);
            }
        }

        // piece count has decreased
        if (pieces > 0 && pieces < count) {

            difference = count - pieces;

            for (var i = 1; i <= difference; i++) {

                var index = $('#container-packages').find('.item').length;

                // Remove the list row
                $('#container-packages').find('.item').last().remove();

                $(".package-index option:selected").each(function () {
                    if ($(this).val() == index) {
                        $(this).closest('.item').remove();
                        commodityCount--;
                        setCommodityCount(commodityCount);
                    }
                });

                // Remove the last dry ice field from options panel
                $('#dry-ice-fieldset').find('.item').last().remove();

            }

            // remove the options from the package select
            $(".package-index option").each(function () {
                if ($(this).val() > pieces) {
                    $(this).remove();
                }
            });
        }

        setFillVisibility();
        updateInputAttributes('#container-packages');
        updateInputAttributes('#dry-ice-fieldset');

        // set the visibility of the duplicate button
        if (pieces == 1) {
            $('#button-duplicate').hide();
        } else {
            $('#button-duplicate').show();
        }
    }

    // Address book item clicked
    $(document).on('click', '.select-commodity', function () {
        var itemId = $(this).parent().attr('id').match(/\d+/);
        addCommodity(itemId);
    });

    /**
     *
     *
     * @param null
     * @returns void
     */
    function loadCommodities() {

        // Show the page loading indicator
        $('.modal-loading').show();

        $.get("/commodities", {
            filter: $("#commodity_filter").val(),
            company_id: $("#company_id").val(),
            currency_code: $("#commodity_filter_currency").val()
        }, function (response) {

            $('#commodities_body').empty();

            // Hide the page loading indicator
            $('.modal-loading').hide();

            $.each(response, function (i, commodity) {
                var tr = $('<tr id="commodity-' + commodity.id + '">').append(
                    $('<td class="select-commodity">').text(commodity.description),
                    $('<td class="select-commodity">').text(commodity.commodity_code),
                    $('<td class="select-commodity">').text(commodity.harmonized_code),
                    $('<td class="select-commodity">').text(commodity.country_of_manufacture),
                    $('<td class="select-commodity">').text(commodity.unit_value + " " + commodity.currency_code),
                    $('<td class="p-2 text-right">').html('<a href="/commodities/' + commodity.id + '" title="Delete Commodity" class="delete mr-2" data-record-name="commodity"><i class="fas fa-times"></i></a><a href="#" id="edit-commodity-' + commodity.id + '" class="edit-commodity"><span class="far fa-edit" aria-hidden="true"></span></a>')
                );
                tr.appendTo('#commodities_body');
            });

            $("#commodity_filter").focus();
        });
    }


    /**
     * Add commodity.
     *
     * @param {type} commodityId
     * @param {type} packageIndex
     * @param {type} quantity
     * @param {type} unitWeight
     * @param {type} unitValue
     * @returns {jqXHR}
     */
    function addCommodity(commodityId, packageIndex, quantity, unitWeight, unitValue) {

        if (packageIndex === undefined) {
            packageIndex = '';
        }

        if (quantity === undefined) {
            quantity = '';
        }

        if (unitWeight === undefined) {
            unitWeight = '';
        }

        if (unitValue === undefined) {
            unitValue = '';
        }

        return $.ajax({
            type: "GET",
            url: "/commodities/" + commodityId,
            cache: false,
            success: function (response) {

                if (!unitWeight) {
                    unitWeight = response.unit_weight;
                }

                if (!unitValue) {
                    unitValue = response.unit_value;
                }

                var currency_code = $('#container-contents').find('.commodity-currency-code').first().text();

                if (currency_code && response.currency_code !== currency_code) {
                    swal('Currency mismatch!', 'You cannot add commodities with different currency codes.', 'warning');
                } else {

                    // set the currency code field
                    $("#customs_value_currency_code").val(response.currency_code);

                    var commodityCount = $("#commodity_count").val();
                    var pieces = $("#pieces").val();
                    var select = $('<select class="form-control form-control-sm package-index" name="contents[' + commodityCount + '][package_index]" id="contents-' + commodityCount + '-package-index">');

                    // Build the package index select box
                    for (var i = 1; i <= pieces; i++) {
                        select.append($("<option></option>").attr("value", i).text(i));
                    }

                    if (packageIndex) {
                        select.val(packageIndex);
                    }

                    var hiddenFields = '';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][id]" id="contents-' + commodityCount + '-id" value="' + response.id + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][product_code]" id="contents-' + commodityCount + '-product-code" value="' + response.product_code + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][currency_code]" id="contents-' + commodityCount + '-currency-code" value="' + response.currency_code + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][country_of_manufacture]" id="contents-' + commodityCount + '-country-of-manufacture" value="' + response.country_of_manufacture + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][manufacturer]" id="contents-' + commodityCount + '-manufacturer" value="' + response.manufacturer + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][uom]" id="contents-' + commodityCount + '-uom" value="' + response.uom + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][commodity_code]" id="contents-' + commodityCount + '-commodity-code" value="' + response.commodity_code + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][harmonized_code]" id="contents-' + commodityCount + '-harmonized-code" value="' + response.harmonized_code + '">';
                    hiddenFields += '<input type="hidden" name="contents[' + commodityCount + '][shipping_cost]" id="contents-' + commodityCount + '-shipping-cost" value="' + response.shipping_cost + '">';

                    var div = $('<div id="commodity-' + commodityCount + '" class="form-group row item">').append(
                        $('<div class="col-xl-3">').html('<input type="text" name="contents[' + commodityCount + '][description]" id="contents-' + commodityCount + '-description" class="form-control form-control-sm" value="' + response.description + '" readonly>' + hiddenFields),
                        $('<div class="col-xl-2">').html(select),
                        $('<div class="col-xl-2">').html('<input class="form-control form-control-sm numeric-only-required" name="contents[' + commodityCount + '][quantity]"  id="contents-' + commodityCount + '-quantity" value="' + quantity + '" type="text" maxlength="8">'),
                        $('<div class="col-xl-2">').html('<div class="input-group input-group-sm"><input class="form-control decimal-only-required" name="contents[' + commodityCount + '][unit_weight]"  id="contents-' + commodityCount + '-unit-weight" type="text" value="' + unitWeight + '" maxlength="8"><div class="input-group-append"><span id="weight_uom_' + commodityCount + '" class="input-group-text commodity-weight-uom text-uppercase">' + response.weight_uom + '</span></div></div>'),
                        $('<div class="col-xl-2">').html('<div class="input-group input-group-sm"><input class="form-control decimal-only-required" name="contents[' + commodityCount + '][unit_value]"  id="contents-' + commodityCount + '-unit-value" type="text" value="' + unitValue + '" maxlength="12"><div class="input-group-append"><span id="currency_code_' + commodityCount + '" class="input-group-text commodity-currency-code text-uppercase">' + response.currency_code + '</span></div></div>'),
                        $('<div class="col-xl-1">').html('<div class="row action-links"><a href="#" title="Duplicate Commodity"><span class="far fa-copy duplicate-commodity" aria-hidden="true"></span></a> <a href="#" title="Remove Commodity"><span class="fas fa-times remove-commodity ml-xl-1" aria-hidden="true"></span></a></div>')
                    );

                    div.appendTo('#container-contents');

                    $('#commodities').modal('hide');

                    commodityCount++;
                    setCommodityCount(commodityCount);
                }
            }
        });
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function nextNearest(value, number) {
        var remainder = value % number;
        if (remainder > 0)
            value = value - remainder + number;
        return value;
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function calcWeight() {
        var weight = 0;
        for (i = 0; i < $("#pieces").val(); i++) {
            weight += eval($('#packages-' + i + '-weight').val());
        }
        weight = nextNearest(weight, .5);

        return weight.toFixed(2);
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function calcVolumetricWeight(volumetricDivisor) {
        var volumetricWeight = 0;
        for (i = 0; i < $("#pieces").val(); i++) {
            var thisVolumetricWeight = eval($('#packages-' + i + '-length').val()) * eval($('#packages-' + i + '-width').val()) * eval($('#packages-' + i + '-height').val()) / eval(volumetricDivisor);
            volumetricWeight += thisVolumetricWeight;
        }
        volumetricWeight = nextNearest(volumetricWeight, .5);
        return volumetricWeight.toFixed(2);
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function calcValue() {
        var value = 0;

        if ($("#ship_reason").val() != 'documents') {
            for (i = 0; i < $("#commodity_count").val(); i++) {
                value += eval($('#contents-' + i + '-quantity').val() * $('#contents-' + i + '-unit-value').val());
            }
        }

        return value.toFixed(2);
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function setTotals() {
        $('#weight').val(calcWeight());
        $('#customs_value').val(calcValue());
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function updateInputAttributes(selector) {

        var items = $(selector).find('.item');
        var count = 0;

        $.each(items, function () {

            // update the id of the row
            if ($(this).attr("id")) {
                $(this).attr('id', $(this).attr('id').replace(/\d+/, count));
            }

            // update the id and names of the inputs within the row
            $(this).find('input,select').each(function () {
                $(this).attr('name', $(this).attr('name').replace(/\d+/, count));

                if ($(this).attr("id")) {
                    $(this).attr('id', $(this).attr('id').replace(/\d+/, count))
                }
            });

            count++;

        });
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function getServices() {

        $("#button-create").prop("disabled", true);
        $('#select-service-body').empty();

        var token = $("input[name=_token]").val();
        var data = $('#create-shipment').serialize();

        $.ajax({
            url: '/services/available',
            type: 'POST',
            dataType: "json",
            data: {
                _token: token,
                data: data,

            },
            success: function (response) {

                $('#select-service-body').empty();

                if (response.length == 0) {

                    $('#select-service-body').append('<h2 class="text-center mt-5 mb-5"><span class="badge badge-warning text-white ml-sm-3"><span class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></span> Sorry, no services available!</span></h2><div class="text-center"><p>It is possible that the weights/dimensions you have entered do not match a suitable service.</p><p>Please check the values on the previous screen. If the issue persists, please <strong>SAVE</strong> the shipment and contact customer services for assistance.</p></div>');

                } else {

                    $('#cost_breakdown_body').empty();

                    $.each(response, function (i, item) {

                        if (item.price > 0) {
                            var html = '<div id="' + item.id + '" class="service row"><div class="col-sm-7"><input id="' + item.id + '-volumetric-divisor" value="' + item.volumetric_divisor + '" type="hidden"><div class="checkbox-container">&nbsp;</div><span class="service-name">' + item.name + '</span></div><div class="col-sm-5 text-right"><a class="cost-breakdown breakdown-' + item.id + '">Cost Breakdown</a> <span class="price">' + item.price.toFixed(2) + ' ' + item.price_currency + '</span> <span class="vat">exc VAT</span></div></div>';

                            var priceDetail;

                            $.each(item.price_detail, function (index, value) {
                                priceDetail += '<tr class="price-detail-' + item.id + '"><td>' + value.code + '</td><td>' + value.description + '</td><td class="text-right">' + value.value + '</td></tr>';
                            });

                            priceDetail += '<tr class="info price-detail-' + item.id + '"><td>&nbsp;</td><td>&nbsp;</td><td class="text-right"><b>Total: ' + item.price.toFixed(2) + ' ' + item.price_currency + '</b></td></tr>';

                            $('#cost_breakdown_body').append(priceDetail);

                        } else {
                            var html = '<div id="' + item.id + '" class="service row"><div class="col-sm-7"><input id="' + item.id + '-volumetric-divisor" value="' + item.volumetric_divisor + '" type="hidden"><div class="checkbox-container">&nbsp;</div><span class="service-name">' + item.name + '</span></div><div class="col-sm-5 text-right"><span class="price">Call for Price</div></div>';
                        }

                        if ($('#insurance_value').val() > 0) {
                            html += '<div id="insurance-charge-warning" class="lead text-center pt-3"><i class="fas fa-info-circle text-warning"></i> Price does not include additional charge for shipment insurance</div>';
                        }

                        $('#select-service-body').append(html);

                    });

                    var serviceId = $('#service_id').val();

                    if (serviceId <= 0 || $("#" + serviceId).length == 0) {
                        serviceId = $('#select-service-body').find('.service').first().attr('id');
                    }

                    selectService(serviceId);
                }

            },
            error: function (e) {

                $('#cost_breakdown_body').empty();

                switch (e.status) {
                    case 401:
                        $('#select-service-body').append('<h5 class="text-danger text-center mt-4"><p>Authentication Error!</p><p>Please log in again.</p></h5>');
                        break;

                    default:
                        $('#select-service-body').append('<div class="text-danger text-center mt-4"><h4 class="text-danger">Unrecognised error [' + e.status + ']</h4><p class="text-large mt-4">Please log off/on and try again.</p><p class="text-large">If the issue persists, please SAVE your shipment send a screenshot of the previous screen to it@antrim.ifsgroup.com</p></div>');
                        break;
                }
            }
        });

    }


    /*
     * Display the cost breakdown / service info lightbox
     */
    $(document).on('click', '.cost-breakdown', function () {

        // get numeric section of class
        var id = $(this).attr('class').match(/\d+/);
        var serviceName = $(this).parent().parent().find('.service-name').text();

        // hide all the rows in the tbody
        $('#cost_breakdown_body').children('tr').hide();

        // only show the ones we are interested in
        $('.price-detail-' + id).show();

        // Set the lightbox title
        $('#cost_breakdown_title').text(serviceName);

        $('#cost_breakdown').modal('show');

    });


    /**
     *
     *
     * @param null
     * @returns void
     */
    function selectService(serviceId) {

        $("#button-create").prop("disabled", false);

        var div = $('#' + serviceId).find('.checkbox-container');
        var serviceName = $('#' + serviceId).find('.service-name').text();
        var shippingCharge = $('#' + serviceId).find('.price').text();
        var volumetricDivisor = $('#' + serviceId + '-volumetric-divisor').val();

        $('#service_id').val(serviceId);
        $('#shipping_charge').val(shippingCharge);
        $('#summary_service').text(serviceName);
        $('#summary_total').text(shippingCharge);
        $('#summary_volumetric_weight').text(calcVolumetricWeight(volumetricDivisor));

        var span = $('#panel-select-service').find('.far');
        span.remove();
        div.empty();
        div.append('<span class="far fa-check-square text-success" aria-hidden="true"></span>');
    }

    /**
     *
     *
     * @param null
     * @returns void
     */
    function setSummaryValues() {

        var inputs = $('#create-shipment').find('input[type=text],input[type=hidden],select,input:checked');

        // hide the option li on summary screen
        $('#summary_options li').hide();
        $('#no-options-selected').show();

        $.each(inputs, function () {

            var id = $(this).attr('id');
            var value = null;

            if ($("#summary_" + id).length) {

                if ($(this).is("select")) {
                    value = $("#" + id + " option:selected").text();
                }

                if ($(this).is("input:text") || $(this).is("input:hidden")) {
                    value = $(this).val();
                }

                if (value) {
                    $("#summary_" + id).text(value);
                } else {
                    if ($("#summary_" + id).text() !== 'Default') {
                        $("#summary_" + id).text('');
                    }
                }
            }

        });

        if ($('#insurance_value').val() > 0) {
            $("#summary_insured").show();
            $('#no-options-selected').hide();
        }

        if ($('#packages-0-dry-ice-weight').val() > 0) {
            $("#summary_dry_ice").show();
            $('#no-options-selected').hide();
        }

        if ($('#alcohol-type').val()) {
            $("#summary_alcohol").show();
            $('#no-options-selected').hide();
        }

    }

    $("#button-save-shipment").click(function () {

        if ($('#company_id').val() > 0) {

            // Don't save any commodity info if documents or domestic
            if ($('#ship_reason').val() == 'documents' || isDomestic()) {
                setCommodityCount(0);
            }

            var token = $("input[name=_token]").val();
            var values = $('#create-shipment').find('input:text,select,:input:checkbox,textarea,input[name^="contents"],#commodity_count').serialize();
            var id = $('#shipment_id').val();

            $.ajax({
                url: '/shipments/save',
                type: 'POST',
                dataType: "json",
                data: {
                    _method: 'PUT',
                    _token: token,
                    values: values,
                    shipment_id: id,
                    company_id: $('#company_id').val(),
                    mode_id: $('#mode_id').val(),
                    recipient_name: $('#recipient_name').val(),
                    recipient_company_name: $('#recipient_company_name').val(),
                    recipient_city: $('#recipient_city').val(),
                    recipient_country_code: $('#recipient_country_code').val(),
                    pieces: $('#pieces').val(),
                    shipment_reference: $('#shipment_reference').val(),
                    collection_date: $('#collection_date').val()
                },
                success: function (response) {

                    $('#shipment_id').val(response['shipment_id']);

                    if (id) {
                        swal("Updated!", 'Shipment updated successfully.', "success");
                    } else {
                        swal("Saved!", 'Shipment saved successfully', "success");
                        $("#form_title").html('<h3 class="mb-2 text-secondary"><span class="far fa-save mr-sm-1" aria-hidden="true"></span> Saved Shipment <small>' + response['consignment_number'] + '</small></h3>');
                    }

                }
            });

        }
    });

}