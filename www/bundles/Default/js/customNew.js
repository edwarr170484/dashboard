$(document).ready(function(){
    $(".accountConversationMessagesWindow").mCustomScrollbar({scrollbarPosition: "outline",setTop: "-400px"});
    $(".mapListItems").mCustomScrollbar();
    $(".serviceInfo").mCustomScrollbar();
    $(".salonJobs").mCustomScrollbar();
    
    $(".change-avatar").click(function(){$(this).parent().find(".change-avatar-input").trigger("click");});
    
    $("#selectAutoMark").click(function(){
        $(this).find("input").blur();
        $(".mapListItems").toggleClass("hide");
    });
    
    $("#productModalSlider").owlCarousel({
        items:1,
        center:true,
        callbacks: true,
        URLhashListener: true,
        startPosition: 'URLHash',
        navContainer : '#productModalSlider',
        navText : ['<svg width="24" height="44" viewBox="0 0 24 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 43L1.5 22L22.5 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>','<svg width="24" height="44" viewBox="0 0 24 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.5 43L22.5 22L1.5 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
    });
    
    $("#mapServiceSlider").owlCarousel({
        items:1,
        center:true,
        callbacks: true,
        URLhashListener: true,
        startPosition: 'URLHash',
        navContainer : '#mapServiceSlider',
        navText : ['<svg width="24" height="44" viewBox="0 0 24 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.5 43L1.5 22L22.5 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>','<svg width="24" height="44" viewBox="0 0 24 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.5 43L22.5 22L1.5 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
    });
    
    $(".jobsSublist").click(function(){$(this).toggleClass("opened");});
    
    $("#addDealerPhone").click(function(){
        var prototype = $("#dealerinfo_phones").data("prototype");
        var count = $(".dealerPhonesItem").length;
        var newForm = prototype.replace(/__name__/g, count);
        $(".dealerPhonesList").append(newForm);
    });
    
    $("#addDealerSalonPhone").click(function(){
        var prototype = $("#dealersalon_phones").data("prototype");
        var count = $(".dealerSalonPhonesItem").length;
        var newForm = prototype.replace(/__name__/g, count);
        $(".dealerSalonPhonesList").append(newForm);
    });
    
    $(".dealerAuto").click(function(){$(this).find('.dealerAutoInner').toggleClass('active');});
    
    $("body").click(function(){
        $(".siteMenuGamburgerMenu").hide();
        $(".objectStatusSelectSublist").removeClass("active");
    });
    
});

function toggleGamburgerMenu(event, action){
    event.stopPropagation();
    
    if(action === 'show'){
       $(".siteMenuGamburgerMenu").show(); 
    }
    
    if(action === 'hide'){
       $(".siteMenuGamburgerMenu").hide(); 
    }
}

function resetWorkTimes(element, area){
    element.toggleClass('active');
    area.find('input[type="text"]').val("00:00");
}

function selectWeekDays(element, area, dayClass){
    var hasActive = element.hasClass('active');
    var days = area.find(dayClass);
    element.toggleClass('active');
    
    if(hasActive){
        days.each(function(){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).find("input").prop("checked", false);
            }
        });
    }else{
        days.each(function(){
            if(!$(this).hasClass('active')){
                $(this).addClass('active');
                $(this).find("input").prop("checked", true);
            }
        });
    }
}

function deleteDealerLogotype(text){
    if(confirm(text)){
        $.ajax({
            url: '/account/deletelogo',
            type:'post',
            data: $('.accountMessages input[type="checkbox"]:checked'),
            dataType: 'html',
            beforeSend: function(){},
            success: function()
            {
                $("#dealerinfo_logotype").val(0);
                $(".dealerSettingsLogotype").remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });
    }
}

function showHideAllCategories(element){
    var newText = element.data('text');
    var text = element.html();
    element.html(newText);
    element.data('text', text);
    element.parent().parent().parent().find(".shortCategoryList").toggleClass("hide");
    element.parent().parent().parent().find(".fullCategoryList").toggleClass("view");
}

function showHideAllServicesCategories(element){
    var newText = element.data('text');
    var text = element.html();
    element.html(newText);
    element.data('text', text);
    $(".mapAutoNamesList").toggleClass("hide");
}

function selectCategoryItems(element, maxItems){
    var container = element.parent().parent().parent();
    var search = element.val().toLowerCase();
    var itemsNum = 0;
    container.find('.categoryItem').each(function(){
       var part = $(this).data('title').substr(0, search.length);
       if(part !== search){
           $(this).addClass('hide');
       }else{
           itemsNum++;
           $(this).removeClass('hide');
       }
    });
    
    if(itemsNum <= maxItems){
        container.find('.addAdvertFiltersButtons').hide();
    }else{
        container.find('.addAdvertFiltersButtons').show();
    }
}

function radioSetActive(element){
    element.parent().find('li').each(function(){$(this).find('a').removeClass('active');});
    element.find('a').addClass('active');
}

function selectConversations(items, element){
    element.parent().parent().find('a').removeClass('active');
    element.addClass('active');
    
    var conversations = $(".accountMessages").find(".accountMessageItem");
    conversations.each(function(){$(this).removeClass('hide');});
    
    if(items == 'active'){
        conversations.each(function(){
            if($(this).hasClass('active')){
                
            }else{
                $(this).addClass('hide');
            }
        });
    }
    
    if(items == 'readed'){
        conversations.each(function(){
            if($(this).hasClass('active')){
                $(this).addClass('hide');
            }
        });
    }
}

function selectAll(element){
    var checked = element.prop("checked");
    var parent = $(".accountMessages");
    parent.find(".custom-checkbox").each(function(){    
        if( checked == false)
        {
            $(this).prop("checked", false);
            $(this).parent().removeClass('active');
            $(this).next('.checkbox-cover-inner').removeClass('active');
        }
        else
        {
            $(this).prop("checked", true);
            if(!$(this).parent().hasClass('active')){
                $(this).parent().addClass('active');
                $(this).next('.checkbox-cover-inner').addClass('active');
            }
        }                
    });
}

function deleteConversations(text){
    if(confirm(text)){
        $.ajax({
            url: '/account/deleteconversation',
            type:'post',
            data: $('.accountMessages input[type="checkbox"]:checked'),
            dataType: 'html',
            beforeSend: function(){},
            success: function()
            {
                window.location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                
            }
        });
    }
}

function changeConversations(){
    $.ajax({
        url: '/account/changeconversation',
        type:'post',
        data: $('.accountMessages input[type="checkbox"]:checked'),
        dataType: 'html',
        beforeSend: function(){},
        success: function()
        {
            window.location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
                
        }
    });
}

function getMessages(conversationId, element){
    var messages = ($(".accountConversationMessage").length) - 2;
    var parent = element.parent().parent();
    
    $.ajax({
        url: '/account/moremessages/' + conversationId + '/' + messages,
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            if(data){
                parent.after(data);
            }else{
                parent.remove();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
                
        }
    });
}

function deleteFromBlacklist(){
    $.ajax({
        url: '/account/blacklist/delete',
        type:'post',
        data: $('#blackListUsers input[type="checkbox"]:checked'),
        dataType: 'html',
        beforeSend: function(){},
        success: function()
        {
            window.location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
                
        }
    });
}

function getSalonEditForm(salonId, locale_code){
    $.ajax({
        url: '/account/editsalon/' + salonId,
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            $("#dealerAutoSalonEditModal").html(data);
            $("#dealerAutoSalonEditModal").modal();
        },
        error: function(xhr, ajaxOptions, thrownError) {
                
        }
    });
}

function getServiceEditForm(salonId, locale_code){
    $.ajax({
        url: '/account/editservice/' + salonId,
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            $("#dealerAutoSalonEditModal").html(data);
            $("#dealerAutoSalonEditModal").modal();
            $("#salonJobs").mCustomScrollbar();
            $(".custom-checkbox-modal").customCheckbox();
        },
        error: function(xhr, ajaxOptions, thrownError) {
                
        }
    });
}

function deleteDealerSalon(salonId, locale_code, text){
    if(confirm(text)){
        $.ajax({
            url: '/account/deletesalon/' + salonId,
            dataType: 'html',
            beforeSend: function(){},
            success: function(){
                $("#dealerSalon" + salonId).remove();
                $("#dealerAutoSalonEditModal").modal('hide');
            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });
    }
}

function deleteDealerSalonLogotype(salonId, text){
    if(confirm(text)){
        $.ajax({
            url: '/account/deletesalonlogo/' + salonId,
            dataType: 'html',
            beforeSend: function(){},
            success: function(){
                $("#dealerSalonLogotype").parent().find(".change-avatar").removeClass("hide");
                $("#dealerSalon" + salonId).find("img").attr('src','');
                $("#dealersalon_logotype").val('');
                $("#dealerSalonLogotype").remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });
    }
}

function toggleJobs(element){
    element.parent().find(".dealerSalonJobItemJobs").slideToggle();
}

function addAutoserviceRate(salonId, rateId, ratePrice, element, titleText, buttonText){
    $("body").find('.accountBottomPaymentSumm').remove();
    var serviceAction = '';
    var data = salonId + ";" + rateId + ";" + ratePrice;
    
    if(element.hasClass("active")){
        serviceAction = 'removerate'; 
    }else{
        serviceAction = 'addrate';
    }
    
    $.ajax({
            url: '/account/dealer/ajax/' + serviceAction + '/' + data,
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

function toggleSearchModal(){
    if($("#desktopSearchModal").hasClass("active")){
        $('html, body').css({overflow: 'auto',height: 'auto'});
    }else{
        $('html, body').css({overflow: 'hidden',height: '100%'});
    }
    $("#desktopSearchModal").toggleClass("active");
}
function getModalSearchResults(element){
    $.ajax({
        url: '/search/ajax',
        dataType: 'json',
        data: element,
        method:"POST",
        beforeSend: function(){},
        success: function(data){
            $("#modalSearchForm button.submit").removeClass("active");
            if(data.count > 0){
                $("#modalSearchForm button.submit").addClass("active");
            }
            
            $(".modalSearchResult").remove();
            $(".searchBlock").after(data.view);    
        },
        error: function(xhr, ajaxOptions, thrownError) {

        }
    });
}
function toggleQuestion(element){
    element.toggleClass("active");
    element.find(".pageFaqItemAnswer").slideToggle();
}

function getDealerWorkTime(element, dealerId){
    
    var today = new Date();
    var day = today.getDay();
    var time = today.getHours();
    
    $.ajax({
        url: '/dealer/getworkinfo/' + dealerId + '/' + day + '/' + time,
        dataType: 'json',
        beforeSend: function(){},
        success: function(data){
            element.html(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {

        }
    });
}

function getDealerSalonWorkTime(element, salonId){
    
    var today = new Date();
    var day = today.getDay();
    var time = today.getHours();
    
    $.ajax({
        url: '/dealer/getsalonworkinfo/' + salonId + '/' + day + '/' + time,
        dataType: 'json',
        beforeSend: function(){},
        success: function(data){
            element.html(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {

        }
    });
}

function selectDealers(element, action){
    element.parent().parent().find("a").removeClass("active");
    element.addClass("active");
    $("input[name='dealerAutoType']").val(action);
    $.ajax({
        url: '/dealer/getlist/' + action,
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            $("#dealersBlockList").html(data);
        },
        error: function(xhr, ajaxOptions, thrownError) {

        }
    });
}

function setAutoId(autoId, autoName, element){
    $(".mapListAutoLabel").removeClass('active');
    element.parent().addClass('active');
    $("input[name='dealerAuto']").val(autoName);
    $("input[name='dealerAuto']").blur();
    $("input[name='dealerAutoId']").val(autoId);
    $(".mapListItems").toggleClass("hide");
}

function setServiceAutoId(autoId, autoName, element){
    $("input[name='serviceAuto']").val(autoName);
    $("input[name='serviceAuto']").blur();
    $("input[name='serviceAutoId']").val(autoId);
}

function setServiceJobId(categoryId, categoryName){
    $("input[name='serviceJob']").val(categoryName);
    $("input[name='serviceJob']").blur();
    $("input[name='serviceJobId']").val(categoryId);
}

function setReviewRating(element, rating, event){
    event.stopPropagation();
    $(".form-group-rating-star").removeClass("active");
    for(i = 0;i < rating; i++){
        $(".form-group-rating-star").eq(i).addClass("active");
    }
    $("#review_rating").val(rating);
}

function addFavoriteProduct(productId)
{
    $.ajax({
        url: '/addfavorite/' + productId,
        type:'get',
        dataType: 'json',
        success: function(data)
        {
            alert(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            err=xhr.responseText;
        }
    });
}

function deleteFavoriteProduct(productId, text){
    if(confirm(text)){
        $.ajax({
            url: '/deletefavorite/' + productId,
            type:'get',
            dataType: 'json',
            success: function(data)
            {
                if(data.error === 0){
                    $("#favoriteProduct" + productId).remove();
                    window.location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
        });
    }
}

function showReviewAnswerForm(element){
    element.parent().find(".reviewAnswerFormInner").addClass("active");
    element.remove();
}

function sendReviewAnswer(reviewId){
    $.ajax({
        url: '/account/review/answer/' + reviewId,
        type:'post',
        data: $("#reviewAnswerText" + reviewId),
        dataType: 'json',
        success: function(data)
        {
           $("#reviewAnswerForm" + reviewId).html(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            err=xhr.responseText;
        }
    });
}

function showSublist(element, event){
    event.stopPropagation();
    $(".objectStatusSelectSublist").removeClass("active");
    element.next(".objectStatusSelectSublist").addClass("active");
}

function changeReviewStatus(reviewId, statusId){
    $.ajax({
        url: '/account/review/status/' + reviewId + '/' + statusId,
        type:'get',
        dataType: 'json',
        success: function(data)
        {
           $("#reviewStatusSelect" + reviewId).html(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            err=xhr.responseText;
        }
    });
}

function changeOrderStatus(orderId, statusId){
    $.ajax({
        url: '/account/order/status/' + orderId + '/' + statusId,
        type:'get',
        dataType: 'json',
        success: function(data)
        {
           $("#orderStatusSelect" + orderId).html(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            err=xhr.responseText;
        }
    });
}

function deleteOrder(orderId, text){
    if(confirm(text)){
        $.ajax({
            url: '/account/orders/' + orderId,
            type:'get',
            dataType: 'json',
            success: function(data)
            {
               $("#userOrder" + orderId).remove();
               window.location.reload();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
        });
    }
}