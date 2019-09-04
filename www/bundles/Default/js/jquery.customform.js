(function ( $ ) {
     $.fn.customSelect = function(options) {
		
		var make = function(){
		
			var selectElement = $(this);
                        var selected = 0;
                        var multiple = (selectElement.attr("multiple")) ? 1 : 0;
			var selectValue = selectElement.val();
			var selectOptions = selectElement.find("option");
			var selectOptionGroups = selectElement.find("optgroup");
			
			selectElement.wrap("<div class='select-cover'></div>");
			var cover = selectElement.parent(".select-cover");
			
			selectOptions.each(function(){
                            if($(this).attr("value") == selectValue){
                                cover.append("<div class='select-value'>" + $(this).html() + "</div><div class='select-options'></div>");
                                selected = 1;
                            }
			});
                        
			if(selected === 0){
                            cover.append("<div class='select-value'>" + selectElement.attr("placeholder") + "</div><div class='select-options'></div>");
                        }
                        
                        if(multiple === 1){
                            cover.find(".select-options").append("<div class='clear-selects'>Сбросить</div>");
                        }
			
                        if(typeof(selectOptionGroups.html()) != 'undefined'){
				selectOptionGroups.each(function(){
					var groupOptions = $(this).find("option");
					var groupOptionsList = '';
					
					groupOptions.each(function(){
						groupOptionsList += "<div class='select-option' data-value='" + $(this).attr("value") + "'>" + $(this).html() + "</div>";
					});
					cover.find(".select-options").append("<div class='select-option-group'><div class='select-option-group-label'>" + $(this).attr("label") + "</div>" + groupOptionsList + "</div>");
				});
			}else{
				selectOptions.each(function(){
					cover.find(".select-options").append("<div class='select-option' data-value='" + $(this).attr("value") + "'>" + $(this).html() + "</div>");
				});
			}
			
			selectElement.css("display", "none");
			selectElement.css( "width", "100%" );
			
			var selectOptions = cover.find(".select-options");
			
			cover.click(function(event){
				event.stopPropagation();
				
				$(".select-options").removeClass("opened");
				$(".select-options").slideUp();
                                $(".select-cover").removeClass('active');
				
				var selectOptions = $(this).find(".select-options"); 
				if(selectOptions.hasClass("opened")){
					selectOptions.removeClass("opened");
					selectOptions.slideUp();
                                        $(this).removeClass('active');
				}else{
					selectOptions.addClass("opened");
					selectOptions.slideDown();
                                        $(this).addClass('active');
				}
                            
			});
			
                        if(multiple === 1){
                            selectOptions.find(".clear-selects").click(function(e){
                                e.stopPropagation();
                                $(this).parent().parent().find("select").find("option").each(function(){$(this).attr("selected",null);});
                                $(this).parent().find(".select-option").each(function(){$(this).removeClass("active");});
                                $(this).parent().parent().find(".select-value").html($(this).parent().parent().find("select").attr("placeholder"));
                            });
                        }
                        
			selectOptions.find(".select-option").each(function(){
				$(this).click(function(e){
					e.stopPropagation();
                                        if(multiple === 1){
                                            var value = $(this).data("value");
                                            var active = ($(this).hasClass("active")) ? 1 : 0;
                                            selectElement.find("option").each(function(){
                                               if(value.toString() === $(this).attr("value")){
                                                   if(active === 1){
                                                       $(this).attr("selected",null);
                                                   }else{
                                                       $(this).attr("selected","selected");
                                                   }
                                               } 
                                            });
                                            $(this).toggleClass("active");
                                        }else{
                                            var value = $(this).data("value");
                                            $(this).parent().find(".select-option").removeClass("active");
                                            selectElement.find("option").each(function(){
                                                $(this).attr("selected",null);
                                                if(value.toString() === $(this).attr("value")){
                                                    $(this).attr("selected","selected");
                                                } 
                                            });
                                            cover.find(".select-value").html($(this).html());
                                            selectOptions.removeClass("opened");
                                            $(this).parent().parent().toggleClass('active');
                                            $(this).toggleClass("active");
                                            selectOptions.slideUp();
                                        }
				});
			});
			
			$(".select-option-group").click(function(event){event.stopPropagation();});
			
			$("body").click(function(){
                            cover.removeClass('active');
                            $(".select-options").removeClass("opened");
                            $(".select-options").slideUp();
			});
		}
		
		this.each(make);
    };
	
	$.fn.customCheckbox = function(options) {
	
		var settings = $.extend({
            color: "#556b2f",
            backgroundColor: "white"
        }, options );
		
		var make = function(){
		
			var checkboxElement = $(this);
			if(checkboxElement.prop("checked"))
                        {
                            checkboxElement.wrap("<div class='checkbox-cover active'></div>");
                        }else{
                            checkboxElement.wrap("<div class='checkbox-cover'></div>");
                        }
			
			var cover = checkboxElement.parent(".checkbox-cover");
			cover.append("<div class='checkbox-cover-inner'></div>");
			
			if(checkboxElement.prop("checked"))
			{
				cover.find('.checkbox-cover-inner').addClass('active');
			}
			
			cover.click(function(){
				$(this).find('.checkbox-cover-inner').toggleClass('active');
                                $(this).toggleClass('active');
			});
		}
		
		this.each(make);
	};
	
	$.fn.customRadio = function(options) {
	
		var settings = $.extend({
            color: "#556b2f",
            backgroundColor: "white"
        }, options );
		
		var make = function(){
			
			var radioElement = $(this);
			radioElement.wrap("<div class='radio-cover'></div>");
			var cover = radioElement.parent(".radio-cover");
			cover.append("<div class='radio-cover-inner'></div>");
			
			if(radioElement.prop("checked"))
			{
                            cover.find('.radio-cover-inner').addClass('active');
			}
			
			cover.click(function(){
				var radioElementName = radioElement.attr("name");
				$("input[name='" + radioElementName + "']").prop("checked", false);
				$("input[name='" + radioElementName + "']").parent().find('.radio-cover-inner').removeClass('active');
			
				radioElement.prop("checked", true);
				$(this).find('.radio-cover-inner').toggleClass('active');
			});
		}
		
		this.each(make);
	};
 
}( jQuery ));