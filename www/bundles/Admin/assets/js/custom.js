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
    
    $("#add-page-block").click(function(){
        
        var prototype = $("#page_blocks").data("prototype");
        var count = $(".table-page-blocks tbody tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-page-blocks tbody").append(newForm);
        
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
    
    $("#add-question").click(function(){
        
        var prototype = $("#question_answers").data("prototype");
        var count = $(".table-question-answers tbody tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-question-answers tbody").append(newForm);
        
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
    
    $("#add-generation").click(function(){ 
        var prototype = $("#category_generations").data("prototype");
        var l = $(".table-generations > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, l);
        
        $(".table-generations > tbody").append(newForm);

        $(".table-generations > tbody > tr").eq(l).find(".add-generation-translation").click(function(){
            var prototype = $(this).next("#generation_translations_sub").data("prototype");
            var count = $(this).parent().find(".table-generation-translations-sub > tbody > tr").length;
            var newForm = prototype.replace('translations][' + l + '][locale','translations][' + count + '][locale');
            newForm = newForm.replace('translations][' + l + '][value','translations][' + count + '][value');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-generation-translations-sub > tbody").append(newForm);
        });
        
        $(".table-generations > tbody > tr").eq(l).find(".add-generation-modification").click(function(){
            var prototype = $(this).next("#generation_modifications").data("prototype");
            var count = $(this).parent().find(".table-generation-modifications > tbody > tr").length;
            var newForm = prototype.replace('modifications][' + l + '][power','modifications][' + count + '][power');
            newForm = newForm.replace('modifications][' + l + '][size','modifications][' + count + '][size');
            newForm = newForm.replace('modifications][' + l + '][label','modifications][' + count + '][label');
            newForm = newForm.replace('modifications][' + l + '][sortorder','modifications][' + count + '][sortorder');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-generation-modifications > tbody").append(newForm);
        });
        
        $(".table-generations > tbody > tr").eq(l).find(".add-generation-board").click(function(){
            var prototype = $(this).next("#generation_boards").data("prototype");
            var count = $(this).parent().find(".table-generation-boards > tbody > tr").length;
            var newForm = prototype.replace('boards][' + l + '][image','boards][' + count + '][image');
            newForm = newForm.replace('boards][' + l + '][imageNew','boards][' + count + '][imageNew');
            newForm = newForm.replace('boards][' + l + '][board','boards][' + count + '][board');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-generation-boards > tbody").append(newForm);
        });
        
        $(".table-generations > tbody > tr").eq(l).find(".add-generation-item").click(function(){
            var prototype = $(this).next("#generation_items").data("prototype");
            var count = $(this).parent().find(".table-generation-items > tbody > tr").length;
            var newForm = prototype.replace('items][' + l + '][board','items][' + count + '][board');
            newForm = newForm.replace('items][' + l + '][gasType','items][' + count + '][gasType');
            newForm = newForm.replace('items][' + l + '][transmissionType','items][' + count + '][transmissionType');
            newForm = newForm.replace('items][' + l + '][gearType','items][' + count + '][gearType');
            newForm = newForm.replace('items][' + l + '][itemModifications','items][' + count + '][itemModifications');
            newForm = newForm.replace(/__name__/g, count);
            $(this).parent().find(".table-generation-items > tbody").append(newForm);
        });
        
    });
    
    $(".add-generation-translation").click(function(){
        var prototype = $(this).next("#generation_translations_sub").data("prototype");
        var count = $(this).parent().find(".table-generation-translations-sub > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        $(this).parent().find(".table-generation-translations-sub > tbody").append(newForm);
    });
    
    $(".add-generation-modification").click(function(){
        var prototype = $(this).next("#generation_modifications").data("prototype");
        var count = $(this).parent().find(".table-generation-modifications > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);

        $(this).parent().find(".table-generation-modifications > tbody").append(newForm);
    });
    
    $(".add-generation-item").click(function(){
        var prototype = $(this).next("#generation_items").data("prototype");
        var count = $(this).parent().find(".table-generation-items > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);

        $(this).parent().find(".table-generation-items > tbody").append('<tr>' + newForm + '</tr>');
    });
    
    $(".add-generation-board").click(function(){
        var prototype = $(this).next("#generation_boards").data("prototype");
        var count = $(this).parent().find(".table-generation-boards > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);

        $(this).parent().find(".table-generation-boards > tbody").append(newForm);
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
    
    $("#add-pack-service").click(function(){
        
        var prototype = $("#pack_services").data("prototype");
        var count = $(".table-services tbody tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-services tbody").append(newForm);
    });
    
    $("#add-service-price").click(function(){ 
        var prototype = $("#service_prices").data("prototype");
        var count = $(".table-service-prices > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-service-prices > tbody").append(newForm);
    });
    
    $("#add-pack-price").click(function(){ 
        var prototype = $("#pack_prices").data("prototype");
        var count = $(".table-service-prices > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-service-prices > tbody").append(newForm);
    });
    
    $("#add-rate-service").click(function(){ 
        var prototype = $("#rate_services").data("prototype");
        var count = $(".table-rate-services > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-rate-services > tbody").append(newForm);
    });
    
    $("#add-category-rate").click(function(){ 
        var prototype = $("#category_rates").data("prototype");
        var count = $(".table-rates > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-rates > tbody").append(newForm);
    });
    
    $("#add-job").click(function(){ 
        var prototype = $("#jobcategory_jobs").data("prototype");
        var count = $(".table-jobs > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        
        $(".table-jobs > tbody").append(newForm);
    });
    
    $("#add-product-service").click(function(){ 
        var prototype = $("#product_services").data("prototype");
        var count = $(".table-product-service > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        var serviceCount = $(this).data("services");
        
        if(count <= serviceCount - 1){
            $(".table-product-service > tbody").append(newForm);
        }
    });
    
    $("#add-user-rate").click(function(){ 
        var prototype = $("#user_rates").data("prototype");
        var count = $(".table-user-rates > tbody > tr").length;
        var newForm = prototype.replace(/__name__/g, count);
        $(".table-user-rates > tbody").append(newForm);
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

function addSubcategory(parentId){
    $("#category_parent").val(parentId);
    $("#categoryModal").modal();
}

function saveCategory(id, element){
    $.ajax({
        url: '/admin/category/add',
        type:'post',
        data:$("#" + id + " input[type='text'], #" + id + " input[type='hidden']"),
        dataType: 'json',
        beforeSend: function(){element.append('<i class="fa fa-spinner fa-spin"></i>')},
        success: function(data)
        {
            element.find("i").remove();
            $("#categoriesTableList").html(data.view);
            alert(data.message);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            err=xhr.responseText;
        }
    });
}

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

