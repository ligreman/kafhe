var badge = 0;
var favicon = new Favico({
    animation : 'fade',
    bgcolor: '#bf3950'
});
var sly;

$(document).ready(function() {
    resizeNavBar();
    bindCloseLinks();
    $(window).resize(function(){
        resizeNavBar();
    });
    prepareOrder();
    prepareUnreadNotifications();
    readOldNotifications();
    loadMoreNotifications();
    loadMoreCorralNotifications()
    askForNews();
    $('#LoginForm_username').focus();
    //Uso de Favico


    //DESCOMENTAR SI NO SE USA SLY
    //$('#skillsPanel ul').width($('#skillsPanel ul li').outerWidth()*$('#skillsPanel ul li').size());
    //DESCOMENTAR SI SE USA SLY
    var $frame = $('#skillsPanel');
    sly = new Sly($frame, {
        horizontal: 1,
        itemNav: 'centered',
        activateMiddle: 0,
        activateOn: null,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 0,
        startAt: 0,
        activatePageOn: null,
        speed: 400,
        moveBy: 800,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 0
    }).init();
    prepareHabilities();
    prepareWindowResize();
});

function resizeNavBar(){
    var main = $('#main'),
        muro = $('#muro'),
        corral = $('#corral_notifications'),
        minalto = 0, muroalto = 0, corralalto = 0;

    minalto = $('#page').innerHeight() - $('header').innerHeight() - $('#userPanel').innerHeight() - $('#skillsUserBlock').innerHeight() + 2;

    muroalto = muro.height();
    corralalto = corral.height();

    if (muroalto < minalto) muroalto = minalto;
    if (corralalto < minalto) corralalto = minalto;

    if (muroalto > corralalto) corralalto = muroalto;
    else muroalto = corralalto;

    muro.css('min-height', muroalto);
    corral.css('min-height', corralalto);

    /*
    //alert($('#content').children().height()+' y la del contenido '+$('#content').children().innerHeight());
    var main = $('#main'),
        muro = $('#muro'),
        corral = $('#corral_notifications'),
        secondary_nav = $('#secondary_nav');

    if(corral.height() > muro.innerHeight()){
        secondary_nav.height(corral.innerHeight());
        //muro.height(corral.innerHeight());
    }else{
        //muro.height(main.innerHeight());
        main.height(muro.height());
        secondary_nav.height(muro.innerHeight());
        corral.height(muro.innerHeight());
    }

    var oldH = $('#vResponsiveContent').height(),
    newH = $(window).height()-($('header').innerHeight()+$('footer').innerHeight());

    if(newH > oldH) $('#guest').height(newH);*/
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
            var checked = $('input[rel-ito="no"]:checked');
            $(this).addClass('selected');
			$('input[rel-ito="no"]').parents('li.radio_row').hide();

            checked.siblings('label').removeClass('selected');
            checked.prop('checked' , false);
        }
    });

    //Lo del otro día
    $("a[name='btn_otroDia']").on('click', function(){
        var meal = $(this).attr('rel-meal'),
        drink = $(this).attr('rel-drink'),
        ito = $(this).attr('rel-ito'),
        labelito = $("div.itoSelect label");

        //Quito todas las selecciones
        $('#meals ul label,#drinks ul label').each(function(){
            $(this).parent().siblings().children('label').removeClass('selected');
            $(this).removeClass('selected');
            $(this).siblings(':radio').prop('checked', false);
        });

        //Quito el checkbox también
        $("div.itoSelect input[type='checkbox']").prop('checked', false);

        //Marco si es o no desayuno ITO
        if ((ito==0 && labelito.hasClass('selected')) || (ito==1 && !labelito.hasClass('selected'))) {
                $("div.itoSelect label").click(); //simulo click para quitar o poner el label según corresponda
        }

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
        $('.skillDescription').html($(this).siblings('.skillDescriptionIndividual').html()).show();

        $('.sdcontent').click(function(event){
            event.stopPropagation();
        });

        $('.cancelButton').click(function(event){
            $("#skillsUserBlock ul li a").removeClass('selected');

            var boton = $('.skillDescription .sdcontent .acceptButton');
            if(boton.length > 0){
                var text = boton.attr('href').split('&target_id');
                text = text[0].split('&arma');
                boton.attr('href', text[0]);
            }
            $('.skillDescription').hide();
        });

        $('.acceptButton').one('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            $(this).attr('href', '');
            window.location.replace(href);
        });

        $('.skillDescription .sdcontent ul li a').click(function(){
            $('.skillDescription .sdcontent ul li a').removeClass('selected');
            $(this).addClass('selected');

            var objetivo = $(this).parent('li').attr('target_id'),
            arma = $(this).parent('li').attr('weapon'),
            boton = $('.skillDescription .sdcontent .acceptButton');
            var destino = boton.attr('href'), trozos = '';

            if (objetivo!=null && objetivo!='') {
                trozos = destino.split('&target_id');
                destino = trozos[0];
                destino += '&target_id='+objetivo;
            }

            if (arma!=null && arma!='') {
                trozos = destino.split('&extra_param');
                destino = trozos[0];
                destino += '&extra_param='+arma;
            }

            boton.attr('href', destino);
        });
    });

    $('.skillDescription').on('click',function(event){
        $('.skillDescription').hide();
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
        var date = $('#muro article:first').attr('data-rel'),
        base_url = $('#baseUrl').text();

        if(date != null){
            $.ajax({
               url:base_url+'/ajax/markAsRead?date='+date
            }).done(function(){ });
        }
    },5000);
}

function loadMoreNotifications(){
    $('#muro').on('click','#moreNotifications a',function(){
        var date = $('#muro article:last').attr('data-rel'),
        type = $('#muro article:last').attr('class');

        if($('.categoriaNotif').is(':visible')){
            type = type.replace('notification','').replace('first','').replace(' ','').replace('unread','');
        }else{
            type="";
        }

        var base_url = $('#baseUrl').text();
        $.ajax({
            url:base_url+'/ajax/loadMoreNotifications?date='+date+'&type='+type,
            datatype: 'html'
        }).done(function(data){
			if(data==""){
				$('#moreNotifications').addClass('categoriaNotif').html('<span>No hay más notificaciones</span>');
				//$('#moreNotifications').html('<span>No hay más notificaciones</span>');
			}else{
				$(".categoriaNotif.hidden").addClass("visible").removeClass("hidden");
				//$(".categoriaNotif.hidden").removeClass("hidden");
				$('#moreNotifications').detach();
				$('#muro').html($('#muro').html()+data);
                resizeNavBar();
			}
		});
        return false;
    });
}

function loadMoreCorralNotifications(){
    $('#corral_notifications').on('click','#moreCorralNotifications a',function(){
        var date = $('#corral_notifications article:last').attr('data-rel'),
        base_url = $('#baseUrl').text();

        $.ajax({
            url:base_url+'/ajax/loadMoreCorralNotifications?date='+date,
            datatype: 'html'
        }).done(function(data){
            if(data==""){
                $('#moreCorralNotifications').addClass('corralNotif').html('<span>No hay más notificaciones</span>');
                //$('#moreNotifications').html('<span>No hay más notificaciones</span>');
            }else{
                $(".corralNotif.hidden").addClass("visible").removeClass("hidden");
                //$(".categoriaNotif.hidden").removeClass("hidden");
                $('#moreCorralNotifications').detach();
                $('#corral_notifications').html($('#corral_notifications').html()+data);
                resizeNavBar();
            }
        });
        return false;
    });
}

function askForNewNotifications(){
    var date = $('#muro article:first').attr('data-rel');

    if(date != null){
        var base_url = $('#baseUrl').text();
        $.ajax({
            url:base_url+'/ajax/askForUpdates?date='+date,
            dataType: 'json'
        }).done(function(data){
			//Notifications
			if(data.notifications > 1){
				$('#newNotifications').detach();
				$('#muro .categoriaNotif:first').before('<p id="newNotifications" class="centerContainer"><a href="'+location.pathname+'" class="'+$('#moreNotifications a').attr('class')+'">Cargar '+data.notifications+' nuevas notificaciones.</a></p>');
				resizeNavBar();
			}else if(data.notifications > 0){
				$('#newNotifications').detach();
				$('#muro .categoriaNotif:first').before('<p id="newNotifications" class="centerContainer"><a href="'+location.pathname+'" class="'+$('#moreNotifications a').attr('class')+'">Cargar '+data.notifications+' nueva notificación.</a></p>');
				resizeNavBar();
			}
			if(badge != data.notifications){
				badge = data.notifications;
				favicon.badge(badge);
			}
			
			//Tueste
			$("span#tueste").removeClass().addClass('w'+data.ptos_tueste_percent);
			$("span#tueste span.title").text(data.ptos_tueste+' puntos de tueste');
			
			//Batalla
			$("span#batteStatusKafhe").removeClass().addClass('w'+data.gungubos_percent);
			$("span#batteStatusKafhe span.score").text(data.gungubos_kafhe + ' - ' + data.gungubos_achikhoria);
		});
        askForNews();
    }
}

function askForNews(){
    //setTimeout(askForNewNotifications,20000);
}

function prepareUnreadNotifications(){
    $("#notificationsMainLink, #numberUnreadNotifications").click(function(){
        var visible = false;
        if($("#unreadSelfNotificationsList").is(":visible")) visible = true;

        $("#unreadSelfNotificationsList").toggle();

        return false;
    });
}

function prepareWindowResize(){
    $("#skillsPanel>ul>li").css("margin-right","-12px");
    $( window ).resize(function() {
        $("#skillsPanel>ul>li").css("margin-right","0px");
        sly.reload();
        $("#skillsPanel>ul>li").css("margin-right","-12px");
    });
}