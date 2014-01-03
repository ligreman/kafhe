var badge = 0;
var favicon = new Favico({
    animation : 'fade',
    bgcolor: '#bf3950'
});

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
    var sly = new Sly($frame, {
        horizontal: 1,
        itemNav: 'basic',
        activateMiddle: 0,
        smart: 1,
        activateOn: null,
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
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
});

function resizeNavBar(){
    //alert($('#content').children().height()+' y la del contenido '+$('#content').children().innerHeight());
    var main = $('#main'),
        muro = $('#muro'),
        corral = $('#corral_notifications'),
        secondary_nav = $('#secondary_nav');

    if(corral.innerHeight() > muro.children().innerHeight()){
        secondary_nav.height(corral.innerHeight());
        muro.height(corral.innerHeight());
    }else{
        secondary_nav.height(muro.children().innerHeight());
        corral.height(muro.children().innerHeight());
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
        //$('.skillDescription').show();
    });

    $('.cancelButton').click(function(){
        $("#skillsUserBlock .targetList li").removeClass('selected');
        if($(this).siblings(".acceptButton").length > 0){
            var button = $(this).siblings(".acceptButton");
            text = button.attr('href').split('&target_id');
            button.attr('href', text[0]);
        }
        $('.skillDescription').hide();
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

    $('.acceptButton').click(function(e){
        e.stopImmediatePropagation();
    });

    $('.sdcontent').click(function(e){
        e.stopImmediatePropagation();
    });

    $('.skillDescription').click(function(){
        $('.skillDescription').hide();
        //return false;
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
               url:base_url+'/ajax/markAsRead?date='+date
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
        date = $('#corral_notifications article:last').attr('data-rel');
        
        var base_url = $('#baseUrl').text();
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
    date = $('#muro article:first').attr('data-rel');

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
        $visible = false;
        if($("#unreadSelfNotificationsList").is(":visible")) $visible = true;

        $("#unreadSelfNotificationsList").toggle();

        return false;
    });
}