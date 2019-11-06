function getStep11(categoryId, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step11/' + categoryId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep12(categoryId, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step12/' + categoryId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep2(locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step2',
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep3(locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step3',
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $(".custom-checkbox").customCheckbox();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep4(locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step4',
        type:'post',
        data: $(".advertFiltersItems input[type='checkbox']:checked"),
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $(".custom-checkbox").customCheckbox();
            $(".custom-select").customSelect();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep5(locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/step5',
        type:'post',
        data: $(".advertFiltersItems input[type='checkbox']:checked, .advertFiltersItems input[type='radio']:checked,.advertFiltersItems input[type='text'], .advertFiltersItems textarea, .advertFiltersItems select"),
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function addAdvert(locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/finalAdd',
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getBoardTypesByYear(year, locale_code, text){
    $(".olderBloakcTrigger").removeClass('active');
    $(".olderBloakcTrigger span").html(text);
    
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/boards/' + year,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarGenerations").html('');
            $("#addAdvertCarEngines").html('');
            $("#addAdvertCarGears").html('');
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarBoards").html(html);
            $(".custom-checkbox").customCheckbox();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getBoardTypesByOldYear(year, locale_code, element){
    
    $(".olderBloakcTrigger span").html(element.html());
    $(".years").find('li').each(function(){$(this).find('a').removeClass('active');});
    $(".olderBloakcTrigger").addClass('active');
    
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/boards/' + year,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarGenerations").html('');
            $("#addAdvertCarEngines").html('');
            $("#addAdvertCarGears").html('');
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarBoards").html(html);
            $(".custom-checkbox").customCheckbox();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGenerationsByBoard(boardId,locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/generations/' + boardId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarGenerations").html('');
            $("#addAdvertCarEngines").html('');
            $("#addAdvertCarGears").html('');
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarGenerations").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGenerationEngine(generationId, locale_code, element){
    element.parent().find('.addAdvertGeneration').each(function(){$(this).removeClass('active');});
    element.addClass('active');
    
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/engines/' + generationId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarEngines").html('');
            $("#addAdvertCarGears").html('');
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarEngines").html(html);
            $(".gas-checkbox").customCheckbox();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGearType(gasTypeId, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/gears/' + gasTypeId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarGears").html('');
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarGears").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getTransmissionType(gearType, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/transmission/' + gearType,
        type:'get',
        dataType: 'html',
        beforeSend: function(){
            $("#addAdvertCarTransmittions").html('');
            $("#addAdvertCarModifications").html('');
            $(".cookieAlertButton.nextStep").addClass("hide");
        },
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarTransmittions").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getModifications(transmissionId, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/modification/' + transmissionId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){$("#addAdvertCarModifications").html('');$(".cookieAlertButton.nextStep").addClass("hide");},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertCarModifications").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function setModification(modificationId, locale_code, element){
    
    element.parent().find('.addAdvertModification').each(function(){$(this).removeClass('active');});
    element.addClass('active');
    
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/setmodification/' + modificationId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function()
        {
            $(".cookieAlertButton.nextStep").removeClass("hide");
            return true;
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function setColor(colorId, locale_code, element){
    
    element.parent().parent().find('a').each(function(){$(this).removeClass('active');});
    element.addClass('active');
    
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/setcolor/' + colorId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function()
        {
            return true;
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function selectGarant(element){
    element.parent().find('.categoryAdvertType.garant').each(function(){$(this).removeClass('active')});
    element.addClass('active');
}