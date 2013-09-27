$(document).ready(function() {
    resizeNavBar();
    prepareUserPanel();
    bindCloseLinks();
    prepareHabilities();
    $(window).resize(function(){
        resizeNavBar();
    });
    prepareOrder();
    prepareUnreadNotifications();
    readOldNotifications();
    loadMoreNotifications();
    askForNews();
    $('#LoginForm_username').focus();
    //DESCOMENTAR SI NO SE USA SLY
    //$('#skillsUserBlock ul').width($('#skillsUserBlock ul li').outerWidth()*$('#skillsUserBlock ul li').size());
    //DESCOMENTAR SI SE USA SLY
    //var $frame = $('#skillsUserBlock');
    /*var sly = new Sly($frame, {
        horizontal: 1,
        itemNav: 'centered',
        activateMiddle: 1,
        smart: 1,
        activateOn: 'click',
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        startAt: 10,
        activatePageOn: 'click',
        speed: 400,
        moveBy: 800,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1
    }).init();*/
});

function resizeNavBar(){
    //alert($('#content').children().height()+' y la del contenido '+$('#content').children().innerHeight());
    if($('#main').innerHeight() > $('#content').children().innerHeight()){
        $('#secondary_nav').height($('#main').innerHeight());
        $('#content').height($('#main').innerHeight());
        $('#muro').height($('#main').innerHeight());
    }else{
        $('#secondary_nav').height($('#content').children().innerHeight());
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

    //Lo del otro día
    $("a[name='btn_otroDia']").on('click', function(){
        var meal = $(this).attr('rel-meal'),
        drink = $(this).attr('rel-drink');

        //Quito todas las selecciones
        $('#meals ul label,#drinks ul label').each(function(){
            $(this).parent().siblings().children('label').removeClass('selected');
            $(this).removeClass('selected');
            $(this).siblings(':radio').prop('checked', false);
        });

        $("#meals ul input[value='"+meal+"']").prop('checked', true).siblings('label').addClass('selected');
        $("#drinks ul input[value='"+drink+"']").prop('checked', true).siblings('label').addClass('selected');
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
            $.cookie('userPanelHidden','0', {'path':'/'});
        }else{
            $(this).children('img').attr('src',src.replace('hide','show'));
            $(this).children('img').attr('title','Quiero ver mi panel de usuario');
            $.cookie('userPanelHidden','1', {'path':'/'});
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
            $.cookie('skillsHidden','1', {'path':'/'});
        }else{
            $('#skillsUserBlock').addClass('visible')
            $.cookie('skillsHidden','0', {'path':'/'});
        }
    });

    //Div de detalle de habilidades
    $('.skillLink').click(function(){
        $(this).siblings('.skillDescription').show();
    });

    $('.cancelButton').click(function(){
        $("#skillsUserBlock .targetList li").removeClass('selected');
        if($(this).siblings(".acceptButton").length > 0){
            var button = $(this).siblings(".acceptButton");
            text = button.attr('href').split('&target_id');
            button.attr('href', text[0]);
        }
        $(this).parents('.skillDescription').hide();
    });

    $('.sdcontent ul li').click(function(){
        $(this).siblings().removeClass('selected');
        if($(this).hasClass('selected')){
            //$(this).removeClass('selected');
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
        //var l = window.location;
        //var base_url = l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1];
        var base_url = $('#baseUrl').text();

        if(date != null){
            $.ajax({
               url:base_url+'/site/read?date='+date
            }).done(function(){ });
        }
    },5000);
}

function loadMoreNotifications(){
    $('#muro').on('click','#moreNotifications a',function(){
        date = $('#muro article:last').attr('data-rel');
        type = $('#muro article:last').attr('class');
        if($('.categoriaNotif').is(':visible')){
            type = type.replace('notification','').replace('first','').replace(' ','').replace('unread','');
        }else{
            type="";
        }

        var base_url = $('#baseUrl').text();
        $.ajax({
            url:base_url+'/site/load?date='+date+'&type='+type,
            datatype: 'html'
        }).done(function(data){
                if(data==""){
                    $('#moreNotifications').addClass('categoriaNotif');
                    $('#moreNotifications').html('<span>No hay más notificaciones</span>');
                }else{
                    $(".categoriaNotif.hidden").addClass("visible");
                    $(".categoriaNotif.hidden").removeClass("hidden");
                    $('#moreNotifications').detach();
                    $('#muro').html($('#muro').html()+data);
                    resizeNavBar();
                }
            });
        return false;
    });
}

function askForNewNotifications(){
    date = $('#muro article:first').attr('data-rel');

    if(date != null){
        var base_url = $('#baseUrl').text();
        $.ajax({
            url:base_url+'/site/askForNew?date='+date,
            datatype: 'json'
        }).done(function(data){
                    if(data > 1){
                        $('#newNotifications').detach();
                        $('#muro .categoriaNotif:first').before('<p id="newNotifications" class="centerContainer"><a href="'+location.pathname+'" class="'+$('#moreNotifications a').attr('class')+'">Cargar '+data+' nuevas notificaciones.</a></p>');
                        resizeNavBar();
                    }else if(data > 0){
                        $('#newNotifications').detach();
                        $('#muro .categoriaNotif:first').before('<p id="newNotifications" class="centerContainer"><a href="'+location.pathname+'" class="'+$('#moreNotifications a').attr('class')+'">Cargar '+data+' nueva notificación.</a></p>');
                        resizeNavBar();
                    }
            });
        askForNews();
    }
}

function askForNews(){
    setTimeout(askForNewNotifications,20000);
}

function prepareUnreadNotifications(){
    $("#notificationsMainLink, #numberUnreadNotifications").click(function(){
        $visible = false;
        if($("#unreadSelfNotificationsList").is(":visible")) $visible = true;

        $("#unreadSelfNotificationsList").toggle();

        return false;
    });
}