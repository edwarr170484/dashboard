function showHideAllCategories(element){
    var newText = element.data('text');
    var text = element.html();
    element.html(newText);
    element.data('text', text);
    $(".shortCategoryList").toggleClass("hide");
    $(".fullCategoryList").toggleClass("view");
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