(function($){$(window).on('load',function(){let allFilter=$('.the4-filter-wrap').find('.wrap_filter>.col-12>.widget-title');$.each(allFilter,function(index,value){if(value.outerText=='по Цвет для фильтров'){$(this).text("По цвету")}
if(value.outerText=='по Размер'){$(this).text('По размеру')}
if(value.outerText=='по Цена'){$(this).text('По цене')}
if(value.outerText=="bo'yicha Narxi"){$(this).text("Narx bo'yicha");}
if(value.outerText=="bo'yicha Размер"){$(this).text("O'lcham bo'yicha");}
if(value.outerText=="bo'yicha Цвет для фильтров"){$(this).text("Rang bo'yicha");}});$('.the4-banner a').removeAttr('target');var window_w=$(window).width();var cat_menu=$('#the4-mobile-menu__cat')
var initDropdownMenuCat=function(){$('#the4-mobile-menu__cat ul li.has-sub').append('<span class="holder"></span>');$(cat_menu).on('click','.holder',function(){var el=$(cat_menu).closest('li');if(el.hasClass('open')){el.removeClass('open');el.find('li').removeClass('open');el.find('ul').slideUp();}else{el.addClass('open');el.children('ul').slideDown();el.siblings('li').children('ul').slideUp();el.siblings('li').removeClass('open');el.siblings('li').find('li').removeClass('open');el.siblings('li').find('ul').slideUp();}});}
if(window_w<768){initDropdownMenuCat();}});})(jQuery);