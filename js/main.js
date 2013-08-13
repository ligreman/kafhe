$(document).ready(function() {
    resizeNavBar();
    prepareUserPanel();
    bindCloseLinks();
    prepareHabilities();
    $(window).resize(function(){
        resizeNavBar();
    });
    prepareOrder();
    readOldNotifications();
    loadMoreNotifications();
});

function resizeNavBar(){
    if($('#main').innerHeight() > $('#content').children().innerHeight()){
        $('#secondary_nav').height($('#main').innerHeight());
        $('#content').height($('#main').innerHeight());
    }else{
        $('#secondary_nav').height($('#content').children().innerHeight());
        $('#content').height($('#content').children().innerHeight());
    }
    oldH = $('#vResponsiveContent').height();

    newH = $(window).height()-($('header').innerHeight()+$('footer').innerHeight());
    if(newH > oldH) $('#guest').height(newH);
}

function prepareEnrollmentForm(){
    $('input:checked').siblings('label').addClass('selected');

    $('#meals ul label,#drinks ul label').click(function(e){
        $(this).parent().siblings().children('label').removeClass('selected');
        if($(this).hasClass('selected')){
			e.preventDefault(); //para que no me marque de nuevo el sólo el radiobutton
            $(this).removeClass('selected');
			$(this).siblings(':radio').prop('checked', false);			
        }else{
            $(this).addClass('selected');
        }
    });
	
	//ITOS
	if ($("div.itoSelect input[type='checkbox']").is(':checked'))
		$('input[rel-ito="no"]').parents('li.radio_row').hide();

    $('.itoSelect label').click(function(){
        $(this).parent().siblings().children('label').removeClass('selected');
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
			$('input[rel-ito="no"]').parents('li.radio_row').show();
        }else{
            $(this).addClass('selected');
			$('input[rel-ito="no"]').parents('li.radio_row').hide();
            $('input[rel-ito="no"]:checked').siblings('label').removeClass('selected');
            $('input[rel-ito="no"]:checked').attr('checked' , 'false');
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
            $.cookie('userPanelHidden','0');
        }else{
            $(this).children('img').attr('src',src.replace('hide','show'));
            $(this).children('img').attr('title','Quiero ver mi panel de usuario');
            $.cookie('userPanelHidden','1');
        }
        return false;
    });
}

function prepareHabilities(){
    //Boton para mostrar habilidades
    $('#skillsIcon a').click(function(){
        $('#skillsUserBlock').slideToggle();
        if($('#skillsUserBlock').is('.visible')){
            $('#skillsUserBlock').removeClass('visible')
            $.cookie('skillsHidden','1');
        }else{
            $('#skillsUserBlock').addClass('visible')
            $.cookie('skillsHidden','0');
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

    $('.sdcontent').click(function(e){
        e.stopImmediatePropagation();
    });

    $('.skillDescription').click(function(){
        $('.skillDescription').hide();
        return false;
    });
}

function prepareOrder(){
    $('.tipoPedido ul.comida li a, .tipoPedido ul.bebida li a').click(function(){
        $(this).parent().css('text-decoration','line-through');
        return false;
    });
}

function readOldNotifications(){
    setTimeout(function (){
        date = $('#muro article:first').attr('data-rel');
        var l = window.location;
        var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1];
        $.ajax({
           url:base_url+'/site/read?date='+date,

        }).done(function(){

            });
    },5000);
}

function loadMoreNotifications(){
    $('#muro').on('click','#moreNotifications a',function(){
        date = $('#muro article:last').attr('data-rel');
        var l = window.location;
        var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1];
        $.ajax({
            url:base_url+'/site/load?date='+date,
            datatype: 'html'
        }).done(function(data){
                if(data==""){
                    $('#moreNotifications').addClass('categoriaNotif');
                    $('#moreNotifications').html('<span>No hay más notificaciones</span>');
                }else{
                    $('#moreNotifications').detach();
                    $('#muro').html($('#muro').html()+data);
                    resizeNavBar();
                }
            });
        return false;
    });
}