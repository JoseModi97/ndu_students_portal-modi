/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * Contains functions to work with Jquery validation plugin
 */

/**
 * Set default configurations
 */
jQuery.validator.setDefaults({
    errorElement: "span",
    errorClass: "help-block",
    highlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    },
    errorPlacement: function (error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});

/**
 * Check for uniqueness
 * https://stackoverflow.com/questions/16964288/jquery-validation-of-multiple-not-equal-inputs
 */
jQuery.validator.addMethod("notEqualToGroup", function (value, element, options) {
    // get all the elements passed here with the same class
    var elems = $(element).parents('form').find(options[0]);
    // the value of the current element
    var valueToCompare = value;
    // count
    var matchesFound = 0;
    // loop each element and compare its value with the current value
    // and increase the count every time we find one
    jQuery.each(elems, function () {
        thisVal = $(this).val();
        if (thisVal == valueToCompare) {
            matchesFound++;
        }
    });
    // count should be either 0 or 1 max
    if (this.optional(element) || matchesFound <= 1) {
        //elems.removeClass('error');
        return true;
    } else {
        //elems.addClass('error');
    }
}, "Please enter a Unique Value.");

/**
 * Check for password strength
 */
$.validator.addMethod("passwordStrength", function (value, element) {
    let password = value;
    // if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
    //     return false;
    // }
    if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(.{8,20}$)/.test(password))) {
        return false;
    }
    return true;
}, function (value, element) {
    let password = $(element).val();
    if (!(/^(.{8,20}$)/.test(password))) {
        return 'Password must be between 8 to 20 characters long.';
    } else if (!(/^(?=.*[A-Z])/.test(password))) {
        return 'Password must contain at least one uppercase.';
    } else if (!(/^(?=.*[a-z])/.test(password))) {
        return 'Password must contain at least one lowercase.';
    } else if (!(/^(?=.*[0-9])/.test(password))) {
        return 'Password must contain at least one digit.';
    }
    // else if (!(/^(?=.*[@#$%&])/.test(password))) {
    //     return "Password must contain special characters from @#$%&.";
    // }
    return false;
});