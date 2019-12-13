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
    if(element){$(".olderBloakcTrigger span").html(element.html());}
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
            $("#addAdvertCarGenerations").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}