function getStep11(categoryId, locale_code){
    $.ajax({
        url: '/account/addadvert/step11/' + categoryId,
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
        url: '/account/addadvert/step12/' + categoryId,
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $("html").animate({scrollTop:0});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep2(locale_code){
    
    $.ajax({
        url: '/account/addadvert/step2',
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $("html").animate({scrollTop:0});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep3(locale_code){
    $.ajax({
        url: '/account/addadvert/step3',
        type:'get',
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $(".custom-checkbox").customCheckbox();
            $("html").animate({scrollTop:0});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep4(locale_code){
    $.ajax({
        url: '/account/addadvert/step4',
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
            $("html").animate({scrollTop:0});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getStep5(locale_code){
    $.ajax({
        url: '/account/addadvert/step5',
        type:'post',
        data: $(".advertFiltersItems input[type='checkbox']:checked, .advertFiltersItems input[type='radio']:checked,.advertFiltersItems input[type='text'], .advertFiltersItems textarea, .advertFiltersItems select"),
        dataType: 'html',
        beforeSend: function(){$(".modal-body-cover").show();},
        success: function(html)
        {
            $(".modal-body-cover").hide();
            $("#addAdvertStep").html(html);
            $("html").animate({scrollTop:0});
            $(".custom-select").customSelect();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function addAdvert(locale_code, isDraft){
    
    var draftParameter = (isDraft) ? '/' + isDraft : '';
    
    $.ajax({
            url: '/account/createadvert' + draftParameter,
            type:'post',
            data: $(".advertFiltersItems input[type='text'], .advertFiltersItems input[type='checkbox']:checked, .advertFiltersItems input[type='hidden'], .advertFiltersItems select"),
            dataType: 'json',
            beforeSend: function(){$(".modal-body-cover").show();},
            success: function(data)
            {
                if(data.redirect){
                    window.location.href = data.href;
                }else{
                    $(".modal-body-cover").hide();
                    $("#addAdvertStep").html(data.view);
                    $("html").animate({scrollTop:0});
                }
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
        url: '/account/addadvert/boards/' + year,
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

function getBoardTypesByOldYear(year, locale_code, element){
    
    if(element){$(".olderBloakcTrigger span").html(element.html());}
    $(".years").find('li').each(function(){$(this).find('a').removeClass('active');});
    $(".olderBloakcTrigger").addClass('active');
    
    $.ajax({
        url: '/account/addadvert/boards/' + year,
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
            $("#addAdvertCarBoards").html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGenerationsByBoard(boardId, locale_code){
    $.ajax({
        url: '/account/addadvert/generations/' + boardId,
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

function getGenerationEngine(generationId, locale_code, element){
    element.parent().find('.addAdvertGeneration').each(function(){$(this).removeClass('active');});
    element.addClass('active');
    
    $.ajax({
        url: '/account/addadvert/engines/' + generationId,
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
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getGearType(gasTypeId, locale_code){
    $.ajax({
        url: '/account/addadvert/gears/' + gasTypeId,
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
        url: '/account/addadvert/transmission/' + gearType,
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
        url: '/account/addadvert/modification/' + transmissionId,
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
        url: '/account/addadvert/setmodification/' + modificationId,
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
        url: '/account/addadvert/setcolor/' + colorId,
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

function resetGarant(element, event){
    event.stopPropagation();
    element.find("input").prop("checked", false);
    element.removeClass("active");
}

function getCityCodesByValue(element){
    var id = element.val();
    
    $.ajax({
        url: '/account/addadvert/getcitycodes/' + id,
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            $("#advertCityCodeBlock").html(data);
            $("#advertCityCode").customSelect();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getCitiesByValue(element, locale_code){
    var searchText = element.val();
    
    $.ajax({
        url: '/account/addadvert/getcities/' + searchText,
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function(data)
        {
            element.parent().find(".addAdvertContextParamenterValues").html(data).addClass("active");
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function selectServicePack(servicePackId, price, text){
    $("input[name='servicePack']").val(servicePackId);
    $("#addAdvertFinalButton").html(text + ' ' + price);
}

function selectService(element, text){
    $("input[name='servicePack']").val(null);
    $("#addAdvertFinalButton").html(text);
}

function addService(element, textEmpty, textFull, currencyCode){
    element.toggleClass('active');
    var price = 0;
    $(".addAdvertServiceItemCheck").each(function(){
        if($(this).hasClass('active')){
            price += $(this).data('price');
        }
    });
    
    if(price >0){
        $("#addAdvertFinalButton").html(textFull + ' ' + price + ' ' + currencyCode);
    }else{
        $("#addAdvertFinalButton").html(textEmpty);
    }
}

function setWheelValue(element, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/setWheel/' + element.prop("checked"),
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function(html)
        {
            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function setGasValue(element, locale_code){
    $.ajax({
        url: '/' + locale_code + '/account/addadvert/setGas/' + element.prop("checked"),
        type:'get',
        dataType: 'html',
        beforeSend: function(){},
        success: function(html)
        {
            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function checkAddAdvertColor(element, headerText, text, buttonText){
    var marker = 1;
    element.find(".advertFiltersItemValue a").each(function(){
        if($(this).hasClass('active')){
            marker = 0;
        }
    });
    
    if(marker === 1){
        var buttonHtml = '<button lass="close" data-dismiss="modal" aria-label="Close">' + buttonText + '</button>';
    
        $("#addAdvertModalHeader").html(headerText);
        $("#productToolsButtons").html(buttonHtml);
        $("#addAdvertModalText").html(text);
        $("#addAdvertModal").modal();
    }
    
    return !marker;
}

function checkAddAdvertFields(element, headerText, text, buttonText){
    var error = 0;
    
    element.find('input[required="required"]').each(function(){
       if($(this).attr('required') == 'required'){
           if($(this).val() == 'null' || $(this).val() == '' || $(this).val() == 'NULL' || $(this).val() == '0' || $(this).val() === undefined || $(this).val() == 0){
               error = 1;
           }
       }
    });
    
    element.find('select[required="required"]').each(function(){
       if($(this).attr('required') == 'required'){
           if($(this).val() == 'null' || $(this).val() == '' || $(this).val() == 'NULL' || $(this).val() == '0' || $(this).val() === undefined || $(this).val() == 0){
               error = 1;
           }
       }
    });
    
    element.find('textarea[required="required"]').each(function(){
       if($(this).attr('required') == 'required'){
           if($(this).val() == 'null' || $(this).val() == '' || $(this).val() == 'NULL' || $(this).val() == '0' || $(this).val() === undefined || $(this).val() == 0){
               error = 1;
           }
       }
    });
    
    if(error == 1){
        var buttonHtml = '<button lass="close" data-dismiss="modal" aria-label="Close">' + buttonText + '</button>';
    
        $("#addAdvertModalHeader").html(headerText);
        $("#productToolsButtons").html(buttonHtml);
        $("#addAdvertModalText").html(text);
        $("#addAdvertModal").modal();
    }
    
    return !error;
}

function showAdvertActionDialog(productId, locale_code, text, headerText, dismissText, buttonText, action){
    var buttonHtml='<button onclick="doAdvertAction(' + productId + ',\'' + locale_code + '\',\'' + action + '\')">' + buttonText + '</button><button class="gray" class="close" data-dismiss="modal" aria-label="Close">' + dismissText + '</button>';
    
    $("#productToolsHeader").html(headerText);
    $("#productToolsButtons").html(buttonHtml);
    $("#productToolsText").html(text);
    
    $("#productToolsModal").modal();
}

function doAdvertAction(productId, locale_code, action){
    $.ajax({
            url: '/' + locale_code + '/account/advert/ajax/' + action + '/' + productId,
            type:'get',
            dataType: 'html',
            beforeSend: function(){},
            success: function()
            {
                window.location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $(".modal-body-cover").hide();
                err=xhr.responseText;
            }
    });
}

function toggleProductService(productId, serviceId, servicePrice, locale_code, element, titleText, buttonText){
    $("body").find('.accountBottomPaymentSumm').remove();
    var serviceAction = '';
    var data = productId + ";" + serviceId + ";" + servicePrice;
    
    if(element.hasClass("active")){
        serviceAction = 'removeservice'; 
    }else{
        serviceAction = 'addservice';
    }
    
    $.ajax({
            url: '/' + locale_code + '/account/advert/ajax/' + serviceAction + '/' + data,
            type:'get',
            dataType: 'json',
            beforeSend: function(){},
            success: function(data)
            {
                element.toggleClass("active");

                if(data.totalPrice > 0){
                    $("body").append('<div class="accountBottomPaymentSumm"><div class="container"><div class="row"><div class="col-lg-12"><div class="accountBottomPaymentSummValue"><div class="accountBottomPaymentSummValueText">' + titleText + ':</div><div class="accoutnProductServicesTotalSumma">' + data.totalPrice + ' &euro;</div><div class="accountBottomPaymentSummValueButton"><a href="/account/payments/' + data.billId + '/Bill">' + buttonText + '</a></div></div></div></div></div></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $(".modal-body-cover").hide();
                err=xhr.responseText;
            }
        });
}

function removeSession(){
    $.ajax({
        url: '/account/addadvert/removesession',
        type:'get',
        dataType: 'html',
        success: function(html)
        {
            window.location.href = "/account/addadvert";
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function getChildrenCategories(element, category){
    $.ajax({
        url: '/account/' + category + '/addadvert',
        type:'post',
        dataType: 'html',
        data:"markaXmlHttp=" + element.val(),
        success: function(html)
        {
           $("#advertModelCategories").html(html);
           $(".custom-select-models").customSelect();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    }); 
}