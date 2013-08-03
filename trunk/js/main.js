$(document).ready(function() {
    resizeNavBar();
    prepareUserPanel();
    bindCloseLinks();
    prepareHabilities();
});

function resizeNavBar(){
    $('#secondary_nav, #content').height($('#main').height());
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

function bindCloseLinks(){
    $('#submenuBlock').on('click','.closeSubmenuLink',function(event){
        $('#submenuBlock').hide();
        return false;
    });
}

function prepareUserPanel(){
    $('#userpanelMainLink').click(function(){
        $('#userPanel').slideToggle();
        src = $(this).children('img').attr('src');
        if(src.indexOf('show') != -1){
            $(this).children('img').attr('src',src.replace('show','hide'));
            $(this).children('img').attr('title', 'No quiero ver mi panel de usuario');
        }else{
            $(this).children('img').attr('src',src.replace('hide','show'));
            $(this).children('img').attr('title','Quiero ver mi panel de usuario');
        }
        return false;
    });
}

function prepareHabilities(){
    //Boton para mostrar habilidades
    $('#skillsIcon a').click(function(){
        $('#mainUserBlock, #skillsUserBlock').toggle();
        if($('#mainUserBlock').is(':visible')){
            $('#skillsIcon').css('float','right');
        }else{
            $('#skillsIcon').css('float','left');
        }

    });

    //Div de detalle de habilidades
    $('.skillLink').click(function(){
        $(this).siblings('.skillDescription').show();
    });

    $('.cancelButton').click(function(){
        $(this).parents('.skillDescription').hide();
    });

    $('.sdcontent ul li').click(function(){
        $(this).siblings().removeClass('selected');
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
        }else{
            $(this).addClass('selected');
            destino = $(this).parent().parent().parent().siblings('.skillButtons').children('.acceptButton').attr('href');
            if(destino.indexOf('target_id') != -1){
                destino = destino.replace(new RegExp('target_id=\\d'),'target_id='+$(this).attr('target_id'));
            }else{
                destino+='&target_id='+$(this).attr('target_id');
            }
            $(this).parent().parent().parent().siblings('.skillButtons').children('.acceptButton').attr('href', destino);
        }
    });
}