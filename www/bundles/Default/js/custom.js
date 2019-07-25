var productAdditionalFotos;
var productMainFoto;
var myDropzone;

$(document).ready(function(){
    $("img").on("contextmenu", false);
    
    $(".custom-select").customSelect();
    $(".custom-radio").customRadio();
    $(".custom-checkbox").customCheckbox();
    
    $(".template").click(function(){
        
        $(".template").removeClass("active");
        $(this).addClass("active");
        $(".premium-adverts").removeClass("list");
        $(".premium-adverts").removeClass("table");
        $(".premium-adverts").addClass($(this).data("pattern"));
        
        $(".category-list-headers").removeClass("list");
        $(".category-list-headers").removeClass("table");
        $(".category-list-headers").addClass($(this).data("pattern"));
        
        $.ajax({
            url: '/cahngeView/' + $(this).data("pattern"),
            type:'get',
            dataType: 'html',
            success: function(html)
            {
                return 1;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
        });
    });
    
    $(".select-language").click(function(){
        $(this).toggleClass("active");
    });
    $(".select-language-option").click(function(){
        $(".select-language-option-main").html($(this).html());
        window.location.reload()
    });
    
    lightbox.option({
      'albumLabel': ''
    });
    
    $(".dz-hidden-input").attr("multiple", "multiple");
    $('.jcarousel').jcarousel({vertical: true});
    $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
    
    
    $("#userPassword_passwordNew").val('');
    $("#userPassword_passwordConfirm").val('');
    tinymce.init({
            language: "ru",
            valid_elements : 'div,font,p,span,table,ul,ol,strong/b,br,i,em,sup,sub,h1,h2,h3,h4,h5,blockquote,blockquote,pre',
            cleanup : false,
            height:"300",
            verify_html : false,
            cleanup_on_startup : false,
            toolbar_items_size: 'small',
            forced_root_block : "",
            validate_children : false,
            remove_redundant_brs : false,
            remove_linebreaks : false,
            force_p_newlines : false,
            force_br_newlines : false,
            validate: false,
            fix_table_elements : false,
            fix_list_elements:false,
            image_advtab: true,
            selector: "textarea.tinyeditor",
            plugins: "advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table contextmenu paste textcolor filemanager",
            toolbar: "insertfile undo redo | styleselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | filemanager | fontselect | fontsizeselect | bold italic | forecolor | backcolor",
            maxCharsLimit: 3000,
            setup: function(editor) {
                editor.on('init',function(){$(".mce-edit-area").attr('style','border:1px solid #cacaca;border-width:1px 0 0 0;')}),
                editor.on('keyDown', function() {
                        if(editor.getContent().replace(/(<([^>]+)>)/g,"").length > editor.settings.maxCharsLimit)
                        {
                            if(!$(".descriptionFlash").hasClass("active"))
                            {
                                $(".descriptionFlash").addClass("active");
                            }
                        }
                        else
                        {
                            if($(".descriptionFlash").hasClass("active"))
                            {
                                $(".descriptionFlash").removeClass("active");
                            }
                        }
                    });
                editor.on('change', function() {
                        if(editor.getContent().replace(/(<([^>]+)>)/g,"").length > editor.settings.maxCharsLimit)
                        {
                            if(!$(".descriptionFlash").hasClass("active"))
                            {
                                $(".descriptionFlash").addClass("active");
                            }
                        }
                        else
                        {
                            if($(".descriptionFlash").hasClass("active"))
                            {
                                $(".descriptionFlash").removeClass("active");
                            }
                        }
                    });
                }
            });
        
        $(".send-message-file input").change(function(){
            
            $(this).next("span").html($(this).val());
            
        });
        
        $(".selltypeFilterItem").click(function(){
            
            $(".selltypeFilterItem").each(function(){$(this).removeClass("active")});
            $(this).toggleClass("active");
            
            var selltype = $(this).data("selltype");
            
            if(selltype === undefined)
            {
                $(".selltype").show();
            }
            else
            {
                $(".selltype").each(function(){
                    
                    if($(this).hasClass("selltype" + selltype))
                    {
                        $(this).show();
                    }
                    else
                    {
                        $(this).hide();
                    }
                });
            }
            
        });
        
        
        $("#copyToClipboard").click(function(){
        
            var inviteLink = document.querySelector('#referer-link');
            var range = document.createRange();  
            range.selectNode(inviteLink);  
            window.getSelection().addRange(range);  
            
            try {  
                var successful = document.execCommand('copy');  
                var msg = successful ? 'successful' : 'unsuccessful';  
                console.log('Copy email command was ' + msg);  
            } catch(err) {  
                console.log('Oops, unable to copy');  
            }  
            
            window.getSelection().removeAllRanges();  
        });
        
        $(".addadvert-list-subcategories").click(function(event){
            $(this).find("ul").eq(0).slideToggle();
            $(this).toggleClass("opened");
            event.stopPropagation();
        });
        
        $(".reviewModalOpen").click(function(){
            $(".review_product_id").val($(this).data("product"));
            $(".review_review_id").val($(this).data("review"));
            $(".review-user-block").html($(this).data("userhtml"));
        });
        
	$(".div-checkbox").click(function(){
            
                var radio = $(this).data("radio");
                
                if(radio)
                {
                    $(".div-checkbox").each(function(){

                        if($(this).data("radio") == radio)
                        {
                            $(this).removeClass("active");
                            var checkbox = $(this).data('checkbox');
                            var element = $("input#" + checkbox);
                            element.prop("checked",false);
                        }

                    });
                }
	
		$(this).toggleClass("active");
		var checkbox = $(this).data('checkbox');
		var element = $("input#" + checkbox);
		
		if(element.prop("checked") == false)
		{
                    element.prop("checked", true);
		}
		else
		{
                    element.prop("checked",false);
		}
		
	});
        
        $(".div-checkbox-message").click(function(){
            var e = $(this);
            
            $(".tab-pane").each(function(){
                
                if($(this).hasClass("active"))
                {
                    $(this).find(".div-checkbox").each(function(){
                        
                        $(this).toggleClass("active");
                        var checkbox = $(this).data('checkbox');
                        var element = $("input#" + checkbox);

                        if(element.prop("checked") == false)
                        {
                            element.prop("checked", true);
                        }
                        else
                        {
                            element.prop("checked",false);
                        }
                    });
                }
            });
        });
        
        $(".register-form-checkbox").click(function(){
            $(this).toggleClass("active");
            var checkbox = $(this).data('checkbox');
            var element = $("#" + checkbox);
            if(element.prop("checked") == false)
            {
		element.prop("checked", true);
            }
            else
            {
		element.prop("checked",false);
            }
        });
	
	$(".advert-category-button").click(function(){
        $(".main-page-subcategories").hide();
        $(".advert-category-subcategoryes").hide();
                
		$(this).next(".advert-category-subcategoryes").show();
		
		var coords = $(this).parent().position();
		$("#"+$(this).data("sucats")).css({"top" : coords.top+88});
		$("#"+$(this).data("sucats")).show();
		
		
	});
        
        $(".advert-category-name-addadvert").click(function(){
                $(".main-page-subcategories").hide();
                $(".advert-category-subcategoryes").hide();
                
		$(this).next(".advert-category-subcategoryes").show();

		var coords = $(this).parent().position();
		$("#"+$(this).data("sucats")).css({"top" : coords.top+88});
		$("#"+$(this).data("sucats")).show();
        });
        
        $(".close-subcats").click(function(){
            $(this).parent().hide();
            $(".advert-category-subcategoryes").hide();
        });
        
        $(".div-select-helper").click(function(){
            $(this).val('');
            var city = $(this).val();
            city = city.toLowerCase();
            
            $(this).parent().next(".div-select-options").find(".select-option").each(function(){
                
                var dataCity = $(this).html();
                dataCity = dataCity.toLowerCase();
                
                if(dataCity.indexOf(city) != 0)
                {
                    $(this).hide()
                }
                else
                {
                    $(this).show();
                }
            });
        });
        
        $(".div-select-helper").keyup(function(){
            $(this).parent().next(".div-select-options").slideDown();
            var city = $(this).val();
            city = city.toLowerCase();
            
            $(this).parent().next(".div-select-options").find(".select-option").each(function(){
                
                var dataCity = $(this).html();
                dataCity = dataCity.toLowerCase();
                
                if(dataCity.indexOf(city) != 0)
                {
                    $(this).hide()
                }
                else
                {
                    $(this).show();
                }
            });
        });
        $("body").click(function(){
            $(".div-select-options").slideUp();
        });
        $(".div-select").click(function(e){
            e.stopPropagation();
            var slide = $(this).next(".div-select-options");
            slide.slideToggle();
            
            $(".div-select-options").each(function(){
                if($(this).html() != slide.html())
                {
                    $(this).slideUp();
                }
            });
        });
        
        $(".select-option").click(function(){
            var value = $(this).data("value");
            var title = $(this).html();
            var select_id = $(this).parent().next("select");
            
            select_id.val(value);
            
            if($(this).parent().prev(".div-select").hasClass("input"))
            {
                $(this).parent().prev(".div-select").find("input").val(title);
            }
            else
            {
                $(this).parent().prev(".div-select").html(title);
            }
            
            $(this).parent().slideUp();
        });
        
        $("#selectCategory").change(function(){
            //получаем фильтры категории
            $.ajax({
                url: '/' + $(this).data("locale") + '/getsearchfilters/' + $(this).val(),
                type:'get',
                dataType: 'html',
                beforeSend: function(){$(".load-category-filters-spinner").show();},
                success: function(html)
                {
                    $(".load-category-filters-spinner").hide();
                    $(".category-filters").html(html);
                    $("#categoryLoadedFilters").find(".custom-select").customSelect();
                    $("#categoryLoadedFilters").find(".custom-radio").customRadio();
                    $("#categoryLoadedFilters").find(".custom-checkbox").customCheckbox();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $(".load-category-filters-spinner").hide();
                    err=xhr.responseText;
                }
            });
        });
        
        $(".product-thumb-cover").click(function(){
            var src = $(this).prev("a").data("image");
            $(".product-main-image img").attr("src", src);
        });
                
        $(".message-change-status").click(function(){
            
            var element = $(this);
            $.ajax({
                url: '/account/chengemessagestatus/' + element.data("message"),
                type:'post',
                dataType: 'html',
                success: function(html)
                {
                    element.toggleClass("active");
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    err=xhr.responseText;
                }
            });
        });
        
        $(".change-avatar").click(function(){$(".change-avatar-input").trigger("click");});
        
        $(".select-option").each(function(){ 
             if($(this).data("selected") == 1)
             {
                 if($(this).parent().prev(".div-select").hasClass("input"))
                 {
                     $(this).parent().prev(".div-select").find("input").val($(this).html())
                 }
                 else
                 {
                    $(this).parent().prev(".div-select").html($(this).html());
                 }
             }
         });
         
    $(".region-option").click(function(){
        
        var regionId = $(this).data("value");
        
        $.ajax({
                url: '/region/' + regionId,
                type:'get',
                dataType: 'html',
                beforeSend: function(){
                    $(".city-select").find("input").val("Загрузка списка....");
                },
                success: function(html)
                {
                    $(".city-load-spinner").hide();
                    $(".city-select-options").html(html);
                    
                    $(".city-select").find("input").val("- Город -");
                    
                    $("#city").val(0);
                    
                    $(".select-option").click(function(){
                        var value = $(this).data("value");
                        var title = $(this).html();
                        var select_id = $(this).parent().next("select");

                        select_id.val(value);

                        if($(this).parent().prev(".div-select").hasClass("input"))
                        {
                            $(this).parent().prev(".div-select").find("input").val(title);
                        }
                        else
                        {
                            $(this).parent().prev(".div-select").html(title);
                        }
                        
                        $(this).parent().slideUp();
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $(".city-load-spinner").hide();
                    err=xhr.responseText;
                }
        });
        
    });  
    
    $(".adv-foto-input").change(function(event){
        
        var element = $(this);
        productMainFoto = this.files;
        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего
        
        var data = new FormData();
        $.each( productMainFoto, function( key, value ){
            data.append( "file", value );
        });
        
        var formInput = $(this).prev();
        
        $.ajax({
            url: '/account/ajaxloadfotos',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                element.parent().find(".load-product-foto-spinner").show();
            },
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( respond, textStatus, jqXHR ){
                element.parent().find(".load-product-foto-spinner").hide();

                if( typeof respond.error === 'undefined' ){
                    
                    element.parent().find("input[type='file']").hide();
                    element.parent().find("input[type='file']").wrap('<form>').closest('form').get(0).reset();
                    element.parent().find("input[type='file']").unwrap();
                    
                    element.parent().append("<img src='/bundles/images/products/" + respond.imageName + "' style='width:100%' />\n\
                                            <button class=\"delete-product-image\" type=\"button\" onclick=\"if(confirm('Удалить изображение?'))deleteMainProductFoto($(this),'" + respond.imageName + "');else return false;\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></button>");
                    formInput.val(respond.imageName);
                }
                else{
                    element.parent().find(".load-product-foto-spinner").hide();
                    alert(respond.error);
                }
                
                
            },
            error: function( jqXHR, textStatus, errorThrown ){
                element.parent().find(".load-product-foto-spinner").hide();
                alert('Изображение не соответствует требованиям. Допустимые расширения: jpg, jpeg, png, gif.');
            }
        });
    });
    
});

function generatePaymentForm(locale)
{
    $.ajax({
            url: '/' + locale + '/account/userpurse/payment',
            data: $('input[name=\'paymentAmount\'], select[name=\'paymentMethod\']'),
            type:'post',
            dataType: 'html',
            beforeSend: function(){
                $(".load-payment-form").show();
            },
            success: function(html)
            {
                $(".load-payment-form").hide();
                $("#payment-form").html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $(".load-payment-form").show();
                err=xhr.responseText;
            }
        });
}

/*function addNewFoto(fotoBlock)
{
    if(typeof(fotoBlock.find(".adv-foto").find("img").attr("src")) != 'undefined')
    {
        return;
    }
    
    var prototype = $("#product_fotos").data("prototype");
    var count = $(".product-foto-inputs").length;
    var newForm = prototype.replace(/__name__/g, count);
    
    var id = fotoBlock.data("frame");
    
    $(".product-fotos-input").append(newForm);
    fotoBlock.find(".adv-foto").html("<div class='additional-product-foto" + id +"'><i class=\"fa fa-spinner fa-spin fa-3x fa-fw load-product-foto-spinner\"></i></div>");
    
    $("#product_fotos_" + count + "_fotoNew").change(function(event){
        
        var element = $(this);
        productAdditionalFotos = this.files;
        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего

        var data = new FormData();
        $.each( productAdditionalFotos, function( key, value ){
            data.append( key, value );
        });
        
        var formInput = $(this).prev();
        
        $.ajax({
            url: '/account/ajaxloadfotos',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                fotoBlock.find(".load-product-foto-spinner").show();
            },
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( respond, textStatus, jqXHR ){
                fotoBlock.find(".load-product-foto-spinner").show();

                if( typeof respond.error === 'undefined' ){
                    
                    element.parent().find("input[type='file']").hide();
                    fotoBlock.find(".adv-foto").html("<img src='/bundles/images/products/" + respond.imageName + "' style='width:100%' />\n\
                                            <button class=\"delete-product-image\" type=\"button\" onclick=\"if(confirm('Удалить изображение?'))deleteProductFoto($(this), " + count + ", event);else return false;\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></button>");
                    formInput.val(respond.imageName);
                }
                else{
                    fotoBlock.find(".load-product-foto-spinner").hide();
                    alert(respond.error);
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                fotoBlock.find(".load-product-foto-spinner").hide();
                alert('Изображение не соответствует требованиям. Допустимые расширения: jpg, jpeg, png, gif. Максимальный размер - 5Мб.');
            }
        });
        
    });
    
    $("#product_fotos_" + count + "_fotoNew").trigger('click');
}*/

function deleteMainProductFoto(button)
{
    button.parent().find("img").remove();
    button.parent().find("input[type='file']:hidden").show();
    button.parent().find("input[type='file']").wrap('<form>').closest('form').get(0).reset();
    button.parent().find("input[type='file']").unwrap();
    button.parent().find("input[type='hidden']:hidden").val('');
    button.remove();
}

function changeOrderStatus(element, orderId, locale)
{
    var orderStatus = element.val();
    var spinner = element.next(".change-order-status-result");
    
    if(orderStatus == 2)
    {
        element.parent().find(".reviewStatusCommentBlock").show();
        spinner.hide();
        return 0;
    }
    
    element.parent().find(".statusComment").remove();
    
    $.ajax({
            url: '/' + locale + '/account/changeorderstatus/' + orderId + '/' + orderStatus,
            type:'get',
            dataType: 'html',
            beforeSend: function(){
                element.parent().find(".reviewStatusCommentBlock").hide();
                spinner.html('<i class="fa fa-spin fa-spinner fa-2x"></i>');
                spinner.show();
            },
            success: function(html)
            {
                if(orderStatus == 7)
                {
                    element.remove();
                    spinner.parent().html(html);
                    spinner.remove();
                }
                else
                {
                    spinner.html(html);
                }
                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
    });
}

function changeOrderCommentStatus(element, orderId, locale)
{
    var orderStatus = 2;
    var spinner = element.parent().prev(".change-order-status-result");
    spinner.hide();
    var comment = element.prev().val();
            
    $.ajax({
            url: '/' + locale + '/account/changeorderstatus/' + orderId + '/' + orderStatus + '/' + comment,
            type:'get',
            dataType: 'html',
            beforeSend: function(){
                element.parent().parent().find(".reviewStatusCommentBlock").hide();
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

function showAllReviews()
{
    $(".account-review-block").hide();
}

function showSellerNumer(productId)
{
    $.ajax({
            url: '/getsellerphone/' + productId,
            type:'get',
            dataType: 'html',
            beforeSend: function(){
                
                $(".seller-number-spinner").show();
            },
            success: function(html)
            {
                 $(".seller-number-spinner").hide();
                 $(".seller-block-phone").html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
    });
}

function showUserNumer(userId)
{
    $.ajax({
            url: '/getuserphone/' + userId,
            type:'get',
            dataType: 'html',
            beforeSend: function(){
                
                $(".seller-number-spinner").show();
            },
            success: function(html)
            {
                 $(".seller-number-spinner").hide();
                 $(".seller-block-phone").html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
    });
}

function addFavoriteProduct(productId,locale,element)
{
    $.ajax({
            url: '/' + locale + '/addfavorite/' + productId,
            type:'get',
            dataType: 'json',
            success: function(data)
            {
                if(data.error == 'ok')
                {
                    $(".fa-star-o").each(function(){
                        $(this).removeClass("fa-star-o");
                        $(this).addClass("fa-star");
                    });
                    
                    if(element)
                    {
                        element.html('<i class="fa fa-heart" aria-hidden="true"></i>');
                    }
                }
                else
                    alert(data.message);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                err=xhr.responseText;
            }
    });
}

function selectReviews(reviewStatus, element)
{
    $(".review-menu-action").removeClass("active");
    
    $(".account-review-block").hide();
    
    $(".review-status" + reviewStatus).show();
    
    element.parent().toggleClass("active");
}

function selectAllReviews(element)
{
    $(".account-block-content-notfound").hide();
    
    $(".review-menu-action").removeClass("active");
    
    $(".account-review-block").show();
    
    element.parent().toggleClass("active");
}

function selectCategory(category_id, locale_code)
{
    //подкатегории
            $.ajax({
                url: '/' + locale_code + '/account/getsubcategories/' + category_id,
                type:'get',
                dataType: 'html',
                beforeSend: function(){$(".modal-body-cover").show();},
                success: function(html)
                {
                    $(".modal-body-cover").hide();
                    $("#categoriesList").html(html);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $(".modal-body-cover").hide();
                    err=xhr.responseText;
                }
            });
}

function showFilters(element, category_id, locale_code)
{
    $("#categories").modal('hide');
    $("#category").val(category_id);
            $(".button-select-category").html("Изменить");
            $(".addadvert-selected-category-name").html("Категория: " + element.html());
            
            //получаем фильтры категории
            $.ajax({
                url: '/' + locale_code + '/account/getcategoryfilters/' + category_id,
                type:'get',
                dataType: 'html',
                beforeSend: function(){$(".load-category-filters-spinner").show();},
                success: function(html)
                {
                    $(".load-category-filters-spinner").hide();
                    $(".category-loaded-filters").html(html);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $(".load-category-filters-spinner").hide();
                    err=xhr.responseText;
                }
    });
}