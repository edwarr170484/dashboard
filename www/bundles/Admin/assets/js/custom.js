String.prototype.translit = (function(){
    var L = {
'А':'A','а':'a','Б':'B','б':'b','В':'V','в':'v','Г':'G','г':'g',
'Д':'D','д':'d','Е':'E','е':'e','Ё':'Yo','ё':'yo','Ж':'Zh','ж':'zh',
'З':'Z','з':'z','И':'I','и':'i','Й':'Y','й':'y','К':'K','к':'k',
'Л':'L','л':'l','М':'M','м':'m','Н':'N','н':'n','О':'O','о':'o',
'П':'P','п':'p','Р':'R','р':'r','С':'S','с':'s','Т':'T','т':'t',
'У':'U','у':'u','Ф':'F','ф':'f','Х':'Kh','х':'kh','Ц':'Ts','ц':'ts',
'Ч':'Ch','ч':'ch','Ш':'Sh','ш':'sh','Щ':'Sch','щ':'sch','Ъ':'','ъ':'',
'Ы':'Y','ы':'y','Ь':'','ь':'','Э':'E','э':'e','Ю':'Yu','ю':'yu',
'Я':'Ya','я':'ya','_':'-',',':'',' ':'-','\/':'-'
        },
        r = '',
        k;
    for (k in L) r += k;
    r = new RegExp('[' + r + ']', 'g');
    k = function(a){
        return a in L ? L[a] : '';
    };
    return function(){
        return this.replace(r, k);
    };
})();

$(document).ready(function(){
    
    $("#generateTranslit").click(function(){
        var value = $("#forTranslit").val();
        $(".categoryTranslit").val(value.translit());
    });
    
    
    $(".accordion-toggle").click(function(){

        $($(this).attr("href")).slideToggle();
        event.stopPropagation();
        
    });
    $(".productCategoryChange").click(function(){
        var value = $(this).val();
        $(".productCategory").val(value);
    });
    
    
    $(".advtype").click(function(){
        $(".advtype").each(function(){
            $(this).prop("checked", false);
        });
        $(this).prop("checked", true);
    });
    $(".viewtype").click(function(){
        $(".viewtype").each(function(){
            $(this).prop("checked", false);
        });
        $(this).prop("checked", true);
    });
    
    
    $(".selectAllItems").click(function(){
            var checked = $(this).prop("checked");
            var parent = $(this).parent().parent().parent().parent();
            parent.find(".checkbox-item").each(function(){
               
               if( checked == false)
               {
                   $(this).prop("checked", false);
               }
               else
               {
                   $(this).prop("checked", true);
               }
                
            });
        });
        
    $("#add-gallery-image").click(function(){
        
        var prototype = $("#gallery_items").data("prototype");
        var count = $(".table-gallery-items tbody tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-gallery-items tbody").append(newForm);
        
         tinymce.init({
                    language: "ru",
                    valid_elements : "*[*]",
                    cleanup : false,
                    height:"300",
            verify_html : false,
            cleanup_on_startup : false,
            forced_root_block : "",
            validate_children : false,
            remove_redundant_brs : false,
            remove_linebreaks : false,
            force_p_newlines : false,
            force_br_newlines : false,
            valid_children : "+a[div|p|img|br|strong],+ol[p|img|br|strong],+ul[p|img|br|strong]",
            validate: false,
            fix_table_elements : false,
            fix_list_elements:false,
            image_advtab: true,
                    selector: "textarea.tinyeditor",
                    plugins: "advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table contextmenu paste textcolor filemanager",
                    toolbar: "insertfile undo redo | styleselect | bold italic | forecolor | backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | filemanager"
            });
    });
    
    $("#add-product-image").click(function(){
        
        var prototype = $("#product_fotos").data("prototype");
        var count = $(".table-product-fotos tbody tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-product-fotos tbody").append(newForm);
    });
    
    $("#add-translation").click(function(){ 
        var prototype = $("#object_translations").data("prototype");
        var count = $(".table-translations > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-translations > tbody").append(newForm);
    });
    
    $(".add-translation-sub").click(function(){
        var prototype = $(this).next("#object_translations_sub").data("prototype");
        var count = $(this).parent().find(".table-translations-sub > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        $(this).parent().find(".table-translations-sub > tbody").append(newForm);
    });
    
    $("#add-filter-value").click(function(){
        var prototype = $("#filter_values").data("prototype");
        var l = $(".table-filter-values > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, l);
        
        $(".table-filter-values > tbody").append(newForm);
        
        $(".table-filter-values > tbody > tr").eq(l).find(".add-translation-sub").click(function(){
            var prototype = $(this).next("#object_translations_sub").data("prototype");
            var count = $(this).parent().find(".table-translations-sub > tbody > tr").length;
            var newForm = prototype.replace('translations][' + l + '][locale','translations][' + count + '][locale');
            newForm = newForm.replace('translations][' + l + '][value','translations][' + count + '][value');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-translations-sub > tbody").append(newForm);
        });
        
    });
    
    $("#add-description").click(function(){ 
        var prototype = $("#object_descriptions").data("prototype");
        var count = $(".table-descriptions > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-descriptions > tbody").append(newForm);
    });
    
    $("#add-region-city").click(function(){
        
        var prototype = $("#region_city").data("prototype");
        var l = $(".table-region-city > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, l);
        
        $(".table-region-city > tbody").append(newForm);
        
        $(".table-region-city > tbody > tr").eq(l).find(".add-translation-sub").click(function(){
            var prototype = $(this).next("#object_translations_sub").data("prototype");
            var count = $(this).parent().find(".table-translations-sub > tbody > tr").length;
            var newForm = prototype.replace('translations][' + l + '][locale','translations][' + count + '][locale');
            newForm = newForm.replace('translations][' + l + '][value','translations][' + count + '][value');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-translations-sub > tbody").append(newForm);
        });
    });
    
    $("#adminRegion").change(function(){
        
        $.ajax({
                url: '/admin/getregion/' + $(this).val(),
                type:'get',
                dataType: 'html',
                success: function(html)
                {
                    $("#adminCity").html(html);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    err=xhr.responseText;
                }
        });
    });
    
    
});

function getChildCategories(categoryId, element, spaces)
{
    if(element.hasClass("active"))
    {
        $(".subcat-" + categoryId).remove();
    }
    else
    {
        $.ajax({
            url: '/admin/getsub/category/' + categoryId + '/' + spaces,
            type:'get',
            dataType: 'html',
            beforeSend: function(){element.append('<i class="fa fa-spinner fa-spin"></i>')},
            success: function(html)
            {
                element.find("i").remove();
                element.parent().parent().after(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
        });
    }
    element.toggleClass("active");
}

function changeComplaintStatus(element, complaintId)
{
    var complaintStatus = element.val();
    var spinner = element.next(".change-order-status-result");
    
    
    $.ajax({
            url: '/admin/changecomplaintstatus/' + complaintId + '/' + complaintStatus,
            type:'get',
            dataType: 'html',
            beforeSend: function(){
                
                spinner.html('<i class="fa fa-spin fa-spinner fa-2x"></i>');
                spinner.show();
            },
            success: function(html)
            {
                spinner.html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
    });
}

