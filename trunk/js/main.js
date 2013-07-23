$(document).ready(function() {
    prepareEnrollmentForm()
});

function prepareEnrollmentForm(){
    //TODO Para los inputs que estén ya seleccionados, marcar la label como selected

    $('#meals ul label,#drinks ul label').click(function(){
        $(this).parent().siblings().children('label').removeClass('selected');
        $(this).addClass('selected');
    });
}