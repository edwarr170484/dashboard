function getBoardTypesByYearEdit(year, productId, text){
    $(".olderBloakcTrigger").removeClass('active');
    $(".olderBloakcTrigger span").html(text);
    
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/boards/' + year,
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
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getBoardTypesByOldYearEdit(year, productId, element){
    if(element){$(".olderBloakcTrigger span").html(element.find("a").html());}
    $(".years").find('li').each(function(){$(this).find('a').removeClass('active');});
    $(".olderBloakcTrigger").addClass('active');
    
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/boards/' + year,
        type:'post',
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
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGenerationsByBoardEdit(boardId, productId){
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/generations/' + boardId,
        type:'post',
        dataType: 'html',
        data:$("input[name=rightWeel]:checked"),
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
            $("#addAdvertCarEngines").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGearTypeEdit(gasTypeId, productId){
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/gears/' + gasTypeId,
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

function getTransmissionTypeEdit(gearTypeId, productId){
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/transmission/' + gearTypeId,
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

function getModificationsEdit(transmissionTypeId, productId){
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/modification/' + transmissionTypeId,
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

function setColorEdit(colorId, productId, element){
    
    element.parent().find('a').each(function(){$(this).removeClass('active');});
    element.find("a").addClass('active');
    
    $.ajax({
        url: '/account/editadvert/ajax/' + productId + '/setcolor/' + colorId,
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