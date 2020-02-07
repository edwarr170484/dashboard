(function ( $ ) {
     $.fn.customSelect = function(options) {
		
		var make = function(){
		
			var selectElement = $(this);
                        var selected = 0;
                        var multiple = (selectElement.attr("multiple")) ? 1 : 0;
			var selectValue = selectElement.val();
			var selectOptions = selectElement.find("option");
			var selectOptionGroups = selectElement.find("optgroup");
                        var isWrite = (selectElement.data('write')) ? 1 : 0;

			selectElement.wrap("<div class='select-cover'></div>");
			var cover = selectElement.parent(".select-cover");
			
			selectOptions.each(function(){
                            if($(this).attr("value") === selectValue && $(this).attr("value") !== "0" && $(this).attr("value") !== ""){
                                
                                if(isWrite){
                                    cover.append("<div class='select-value'><div class='select-value-inner'><input name='select-writable' value='"+ $(this).html() +"'></div></div>");
                                }else{
                                    cover.append("<div class='select-value'><div class='select-value-inner'>" + $(this).html() + "</div></div>");
                                }
                                
                                
                                if(selectOptions.length > 0){
                                    cover.append("<div class='select-options'></div>");
                                }
                                selected = 1;
                            }
			});
                        
			if(selected === 0){
                            if(isWrite){
                                cover.append("<div class='select-value'><div class='select-value-inner'><input name='select-writable' placeholder='" + selectElement.attr("placeholder") + "'></div></div>");
                            }else{
                                cover.append("<div class='select-value'><div class='select-value-inner'>" + selectElement.attr("placeholder") + "</div></div>");
                            }
                            
                            if(selectOptions.length > 0){
                                cover.append("<div class='select-options'></div>");
                            }
                        }
                        
			cover.find(".select-options").append("<div class='clear-selects'>Сбросить</div>");
                        if(typeof(selectOptionGroups.html()) != 'undefined'){
				selectOptionGroups.each(function(){
					var groupOptions = $(this).find("option");
					var groupOptionsList = '';
					
					groupOptions.each(function(){
                                            var active = '';
                                            if($(this).attr("selected") === 'selected'){
                                                active = 'active';
                                                
                                            }
                                            if($(this).attr("value") !== "0" && $(this).attr("value") !== ""){
                                                groupOptionsList += "<div class='select-option " + active + "' data-value='" + $(this).attr("value") + "'>" + $(this).html() + "</div>";
                                            }
					
					});
					cover.find(".select-options").append("<div class='select-option-group'><div class='select-option-group-label'>" + $(this).attr("label") + "</div>" + groupOptionsList + "</div>");
				});
			}else{
				selectOptions.each(function(){
                                    var active = '';
                                    if($(this).attr("selected") === 'selected'){
                                        active = 'active';
                                    }
                                    if($(this).attr("value") !== "0" && $(this).attr("value") !== ""){
                                        cover.find(".select-options").append("<div class='select-option " + active + "' data-value='" + $(this).attr("value") + "'>" + $(this).html() + "</div>");
                                    }
				});
			}
                        
                        var params = '';
                        if(cover.find(".select-option.active").length > 0){
                            cover.find(".select-option.active").each(function(){
                                if(multiple == 1){
                                    params+=$(this).html().trim()+", ";
                                }else{
                                    params+=$(this).html() + "  ";
                                }
                                 
                            });
                            cover.addClass("selected");
                        }else{
                            params = cover.find("select").attr("placeholder") + "  ";
                        }
                        if(isWrite){
                            if(cover.find(".select-option.active").length > 0){
                                cover.find(".select-value").html("<div class='select-value-inner'><input name='select-writable' value='"+ params.slice(0,-2) +"'></div>");
                            }else{
                                cover.find(".select-value").html("<div class='select-value-inner'><input name='select-writable' placeholder='"+ params.slice(0,-2) +"'></div>");
                            }
                        }else{
                            cover.find(".select-value").html("<div class='select-value-inner'>" + params.slice(0,-2) + "</div>");
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
                        
                        selectOptions.find(".clear-selects").click(function(e){
                            e.stopPropagation();
                            $(this).parent().parent().find("select").find("option").each(function(){$(this).attr("selected",null);});
                            $(this).parent().find(".select-option").each(function(){$(this).removeClass("active");});
                            selectElement.val(null);
                            
                            if(isWrite){
                                $(this).parent().parent().find(".select-value").find("input").val(null);
                                $(this).parent().parent().find(".select-value").find("input").attr('placeholder',($(this).parent().parent().find("select").attr("placeholder")));
                            }else{
                                $(this).parent().parent().find(".select-value").html("<div class='select-value-inner'>" + $(this).parent().parent().find("select").attr("placeholder") + "</div>");
                            }
                        });
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
                                            
                                            var params = '';
                                            
                                            if(cover.find(".select-option.active").length > 0){
                                                cover.find(".select-option.active").each(function(){
                                                   params += $(this).html().trim() + ", "; 
                                                });
                                                cover.addClass("selected");
                                            }else{
                                                params = cover.find("select").attr("placeholder") + "  ";
                                                cover.removeClass("selected");
                                            }
                                            
                                            if(isWrite){
                                                cover.find(".select-value").html("<div class='select-value-inner'><input name='select-writable' value='"+ params.slice(0,-2) +"'></div>");
                                            }else{
                                                cover.find(".select-value").html("<div class='select-value-inner'>" + params.slice(0,-2) + "</div>");
                                            }
                                            
                                        }else{
                                            var value = $(this).data("value");
                                            $(this).parent().find(".select-option").removeClass("active");
                                            selectElement.find("option").each(function(){
                                                $(this).attr("selected",null);
                                                if(value.toString() === $(this).attr("value")){
                                                    $(this).attr("selected","selected");
                                                } 
                                            });
                                            
                                            if(isWrite){
                                                cover.find(".select-value").html("<div class='select-value-inner'><input name='select-writable' value='" + $(this).html() + "'></div>");
                                            }else{
                                                cover.find(".select-value").html("<div class='select-value-inner'>" + $(this).html() + "</div>");
                                            }
                                            
                                            selectOptions.removeClass("opened");
                                            $(this).parent().parent().toggleClass('active');
                                            $(this).toggleClass("active");
                                            selectOptions.slideUp();
                                            
                                            if(cover.find(".select-option.active").length > 0){
                                                cover.addClass("selected");
                                            }else{
                                                cover.removeClass("selected");
                                            }
                                        }
                                        
                                        selectElement.trigger("change");
				});
			});
			
			$(".select-option-group").click(function(event){event.stopPropagation();});
			
			$("body").click(function(){
                            cover.removeClass('active');
                            $(".select-options").removeClass("opened");
                            $(".select-options").slideUp();
			});
                        
                        $("input[name='select-writable']").keyup(function(){
                            var optionsList = $(this).parent().next(".select-options").find(".select-option");
                            var controlVal = $(this).val();
                            optionsList.each(function(){
                                var val = $(this).html();
                                if(val.includes(controlVal)){
                                    $(this).removeClass("hide");
                                }else{
                                    $(this).addClass("hide");
                                }
                            });
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