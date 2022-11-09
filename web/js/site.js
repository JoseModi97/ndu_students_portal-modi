// Set body text to small
$('body').addClass('text-sm')

//Resolve conflict in jQuery UI tooltip with Bootstrap tooltip
$.widget.bridge('uibutton', $.ui.button)

// App loading indicator
const loader = '<h6 class="text-center spinner"> <i class="fa fa-circle-o-notch fa-spin"></i> </h6>';

// Show success toastr
function successToaster(message){
    toastr.success(message)
}

// Show error toastr
function errorToaster(message){
    toastr.error(message)
}



