$(document).ready(function(){
    $(".accountConversationMessagesWindow").mCustomScrollbar({scrollbarPosition: "outline",setTop: "-400px"});
    $(".mapListItems").mCustomScrollbar();
    $(".serviceInfo").mCustomScrollbar();
    
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
    
    $(".jobsSublist").click(function(){$(this).toggleClass("opened");})
    
});

function showHideAllCategories(element){
    var newText = element.data('text');
    var text = element.html();
    element.html(newText);
    element.data('text', text);
    $(".shortCategoryList").toggleClass("hide");
    $(".fullCategoryList").toggleClass("view");
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

function changeConverSations(){
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

function getMessages(conversationId, locale_code, element){
    var messages = ($(".accountConversationMessage").length) - 2;
    var parent = element.parent().parent();
    
    $.ajax({
        url: '/' + locale_code + '/account/moremessages/' + conversationId + '/' + messages,
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