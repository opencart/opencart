$(document).ready(function () {

    var mfKey = 'myfatoorah_v2';

//-----------------------------------------------------------------------------------------------------------------------------------------
    function setMyfatoorahPaymentType(mfId) {
        var payment = $('input[name=' + mfId + '_payment]:checked').val();

        $.ajax({
            type: 'post',
            url: 'index.php?route=extension/payment/' + mfId + '/method',
            data: mfId + '_payment=' + payment,
            success: function () {}
        });
    }
//-----------------------------------------------------------------------------------------------------------------------------------------
    //check 1st item in myfatoorah list .. check the myfatoorah is checked
    if ($('input[name=payment_method][value=' + mfKey + ']').is(':checked')) {
        $('input[name=' + mfKey + '_payment]').first().prop('checked', true);
        setMyfatoorahPaymentType(mfKey);
    }

    //----------------------------------------------------------------------
    //if radio payment_method changes (Parent Input)
    $('input[name=payment_method][value!=' + mfKey + ']').change(function () {

        // Reset all myfatoorah children
        $('input[name=' + mfKey + '_payment]').prop('checked', false);
    });

    //if radio payment_method changes myfatoorah ONLY
    $('input[name=payment_method][value=' + mfKey + ']').change(function () {

        //checked 1st item
        if ($('input[name=' + mfKey + '_payment]:checked').val() === undefined) {
            $('input[name=' + mfKey + '_payment]').first().prop('checked', true);
            setMyfatoorahPaymentType(mfKey);
        }
    });

    //----------------------------------------------------------------------
    //if radio myfatoorah_payment changes
    $('input[name=' + mfKey + '_payment]').change(function () {

        setMyfatoorahPaymentType(mfKey);

        //select parent with triger its code if exist
        $('input[name=payment_method][value=' + mfKey + ']').trigger("click");
    });
//-----------------------------------------------------------------------------------------------------------------------------------------
});