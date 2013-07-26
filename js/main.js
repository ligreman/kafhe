$(document).ready(function() {
    resizeNavBar();
    prepareEnrollmentForm();
});

function resizeNavBar(){
    $('#secondary_nav').height($('#main').height());
}

function prepareEnrollmentForm(){
    $('input:checked').siblings('label').addClass('selected');

    $('#meals ul label,#drinks ul label').click(function(){
        $(this).parent().siblings().children('label').removeClass('selected');
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{
            $(this).addClass('selected');
        }
    });

    $('.itoSelect label,.itoSelect label').click(function(){
        $(this).parent().siblings().children('label').removeClass('selected');
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{
            $(this).addClass('selected');
        }
    });
}