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