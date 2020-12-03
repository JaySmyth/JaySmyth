var hasDialog = false;

$("#create-shipment").validate({
    ignore: '',
    rules: {
        recipient_type: {
            required: true,
            minlength: 1
        },
        recipient_name: {
            required: true,
            minlength: 2
        },
        recipient_address1: {
            required: true,
            minlength: 5
        },
        recipient_city: {
            required: true,
            minlength: 3
        },
        recipient_state: {
        },
        recipient_postcode: {
            required: false,
            minlength: 3
        },
        recipient_country: {
            required: true,
            minlength: 2
        },
        recipient_telephone: {
            required: true,
            minlength: 6
        },
        recipient_email: {
            email: true
        },
        sender_type: {
            required: true,
            minlength: 1
        },
        sender_name: {
            required: true,
            minlength: 2
        },
        sender_address1: {
            required: true,
            minlength: 2
        },
        sender_city: {
            required: true,
            minlength: 2
        },
        sender_state: {
        },
        sender_postcode: {
            required: true,
            minlength: 4
        },
        sender_country: {
            required: true,
            minlength: 2
        },
        sender_telephone: {
            required: true,
            minlength: 6
        },
        sender_email: {
            required: true,
            email: true
        },
        pieces: {
            required: true,
            digits: true
        },
        shipment_reference: {
            required: true,
            minlength: 2
        },
        collection_date: {
            required: true
        },
        export_reason: {
            required: true,
        },
        eori: {
            required: true,
        },
    },
    highlight: function (element, errorClass) {

        $(element).parent("div").addClass("has-danger");

        if (/sender_/i.test($(element).attr('id')) && $(element).is(":hidden") && $('#sender-panel-validator').val() == 0) {
            $('#sender-panel-validator').val(1);

            $('#panel-recipient').hide();
            $('#panel-sender').show();

            swal({
                title: 'Sender Details Required!',
                text: 'Please complete the highlighted fields before trying to proceed.',
                type: 'error'
            });
        }

        if (
            /recipient_/i.test($(element).attr('id')) && $(element).is(":hidden") && $('#recipient-panel-validator').val() == 0 && $('#sender-panel-validator').val() == 0) {

            $('#recipient-panel-validator').val(1);

            $('#panel-sender').hide();
            $('#panel-recipient').show();

            swal({
                title: 'Recipient Details Required!',
                text: 'Please complete the highlighted fields before trying to proceed.',
                type: 'error'
            });
        }

alert($(element).is(":hidden"));
alert($('#customs-panel-validator').val());


        if (($(element).attr('id') =='eori') && $(element).is(":hidden") && $('#invoice-panel-validator').val() == 0 ){

            $('#invoice-panel-validator').val(1);

            $('#panel-details').hide();
            $('#panel-invoice').show();

            swal({
                title: 'EORI Number Required!',
                text: 'Please complete the highlighted fields before trying to proceed.',
                type: 'error'
            });
        }

    },
    unhighlight: function (element, errorClass) {
        $(element).parent("div").removeClass("has-danger");
    },
    errorPlacement: function (error, element) {
        return true;
    },
    invalidHandler: function (form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
            validator.errorList[0].element.focus();
        }
    }
});


$.validator.addClassRules("numeric-only-required", {
    required: true,
    digits: true,
    minlength: 1
});

$.validator.addClassRules("decimal-only-required", {
    required: true,
    number: true,
    minlength: 1
});


$("#commodity-form").validate({
    ignore: '',
    rules: {
        description: {
            required: true,
            minlength: 2
        },
        /*
         manufacturer: {
         required: true,
         minlength: 2
         },
         */
        country_of_manufacture: {
            required: true
        },
        unit_value: {
        required: true
        },
            unit_weight: {
            required: true
        },
        commodity_code: {
            minlength: 8,
            digits: true
        },
        harmonized_code: {
            required: true,
            minlength: 10,
            digits: true
        },
        uom: {
            required: true
        }
    },
    highlight: function (element, errorClass) {
        $(element).parent("div").addClass("has-danger");
    },
    unhighlight: function (element, errorClass) {
        $(element).parent("div").removeClass("has-danger");
    },
    errorPlacement: function (error, element) {
        return true;
    }
});
