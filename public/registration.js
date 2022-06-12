console.log('hiii2');

jQuery(function () {
    console.log('in readyyy');
//        const radio = new MDCRadio(document.querySelector('.mdc-radio'));
 //       const formField = new MDCFormField(document.querySelector('.mdc-form-field'));
   //        formField.input = radio;

    //     const textField = new MDCTextField(document.querySelector('.mdc-text-field'));
    //       const buttonRipple = new MDCRipple(document.querySelector('.mdc-button'));


    jQuery('#khatam-registration-form').on('submit', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var data = jQuery('#khatam-registration-form').serialize();
        data += '&ajaxrequest=true&submit=Submit+Form';

        console.log('data', data);
        jQuery.ajax({
            url: params.url,
            type: 'post',
            data: data
        })
            .done(function (rs) {
                console.log(rs);

            });
    });
});