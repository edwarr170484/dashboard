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
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $(".modal-body-cover").hide();
            err=xhr.responseText;
        }
    });
}

function addAdvert(locale_code,text, isDraft){
    var error = 0;
    $(".advertFiltersItems input[type='text']").each(function(){
       if($(this).attr('required') == 'required'){
           if($(this).val() == 'null' || $(this).val() == '' || $(this).val() == 'NULL' || $(this).val() == '0' || $(this).val() === undefined || $(this).val() == 0){
               $(this).addClass("error");
               error = 1;
               $(document).scrollTop(0);
               return;
           }
       } 
    });
    
    if(error === 1){alert(text);}
    
    var draftParameter = (isDraft) ? '/' + isDraft : '';
    
    if(!error){
        $.ajax({
            url: '/' + locale_code + '/account/createadvert' + draftParameter,
            type:'post',
            data: $(".advertFiltersItems input[type='text'], .advertFiltersItems input[type='checkbox']:checked, .advertFiltersItems input[type='hidden']"),
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

function getGenerationsByBoard(boardId,locale_code){
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

function getCityCodesByValue(element, locale_code){
    var searchText = element.val();
    
    $.ajax({
        url: '/account/addadvert/getcitycodes/' + searchText,
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

function selectCityCode(code, element){
    element.parent().parent().find("input").val(code);
    element.parent().removeClass('active');
    element.parent().html('');
}

function selectCity(city, element){
    element.parent().parent().find("input").val(city);
    element.parent().removeClass('active');
    element.parent().html('');
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

function doAdvertAction(productId, locale_code, text, action){
    if(confirm(text)){
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
}

function toggleProductService(productId, serviceId, servicePrice, locale_code, element, titleText, buttonText){
    $("body").find('.accountBottomPaymentSumm').remove();
    var serviceAction = '';
    var data = productId + ";" + serviceId + ";" + servicePrice;
    var totalPrice = 0;
    
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
                    $("body").append('<div class="accountBottomPaymentSumm"><div class="container"><div class="row"><div class="col-lg-12"><div class="accountBottomPaymentSummValue"><div class="accountBottomPaymentSummValueText">' + titleText + ':</div><div class="accoutnProductServicesTotalSumma">' + data.totalPrice + ' &euro;</div><div class="accountBottomPaymentSummValueButton"><a href="/account/payments/' + data.billId + '">' + buttonText + '</a></div></div></div></div></div></div>');
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