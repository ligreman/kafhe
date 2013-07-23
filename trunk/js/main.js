$(document).ready(function() {
    prepareEnrollmentForm()
});

function prepareEnrollmentForm(){
    $('#meals label,#drinks label').click(function(){
        $(this).parent().siblings().children('label').removeClass('selected');
        $(this).addClass('selected');
    });
}