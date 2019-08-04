$(document).ready(function(){

    if($('.n-manage').length) {
        menuFixed ('.n-manage', 0);
    }
    
    
            //отправка формы
    jQuery('.send-ajax').click( function() {
        var form = jQuery(this).closest('form');

        if ( form.valid() ) {
            form.css('opacity','.5');
            var actUrl = form.attr('action');

            jQuery.ajax({
                url: actUrl,
                type: 'post',
                dataType: 'html',
                data: form.serialize(),
                success: function(data) {
                form.html(data);
                form.css('opacity','1');
            },
                error:	 function() {}
            });
            $(form)[0].reset();
        }
        event.preventDefault();
    });

        //инициализация маски
    $('.phone').inputmask("+7 (999) 999 - 99 - 99");

    
    //моб.меню
    $('.manage .humb, .n-manage .humb').click(function(){
        $('.left-menu').addClass('active');
        $('.menu-backdrop').addClass('active');
    });
    
    $('.close-menu, .menu-backdrop').click(function(){
        $('.left-menu').removeClass('active');
        $('.menu-backdrop').removeClass('active');
    });

    var topMenu = $('.top-line .top-menu').html();
    var category = $('.bottom-line .category').html();
    
    $('.for-menu .links').append(topMenu);
    $('.for-category .links').append(category);
    

    
    
    jQuery(".go-to-block").click(function(e) {
        e.preventDefault();
		var target = jQuery(this).data('target');
		
	    jQuery('html, body').animate({
	        scrollTop: jQuery(target).offset().top
	    }, 400);
	});
    
    $( function() {
        $( "#datepicker" ).datepicker({
            showOn: "both",
            buttonImage: "images/select.png",
            //buttonImageOnly: true,
            buttonText: "Выбрать дату",
            //dateformat: "Y-m-d"
        });
        
    } );
    
    if( $(window).width() < 768) {
        $('.modal').on('shown.bs.modal', function (e) {
        $('body').addClass('active');
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            $('body').removeClass('active');
          $(window).animate({
              scrollTop: currentScroll
          }, 400);
        });
    }
    
    var day = new Date();
    var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000)
    var weekday = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
    var month = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
  
    
    $('.d-tod').click(function(e){
        $('.tod-tom').removeClass('d-flex').addClass('d-none');
        e.preventDefault();
        $('#datepicker').val(weekday[day.getDay()] + ', ' + day.getDate() + ' ' + month[day.getMonth()]);
    });
    
    $('.d-tom').click(function(e){
        $('.tod-tom').removeClass('d-flex').addClass('d-none');
        e.preventDefault();
        $('#datepicker').val(weekday[day.getDay() + 1] + ', ' + tomorrow.getDate() + ' ' + month[day.getMonth()]);
    });
    
    $('#datepicker').on('change', function(){
        $('.tod-tom').removeClass('d-flex').addClass('d-none');
    });
    
    var slider1;
    var slider2;
    var slider3;

    if ( $('#slide-counter1').length ) {
		$('#slide-counter1').prepend('<span class="current-index"></span>');
		slider1 = $('#slideshow1').bxSlider({
			infiniteLoop: false,
			//autoReload:true,
            pagerCustom: '#bx-pager',
			onSliderLoad: function (currentIndex){
				$('#slide-counter1 .current-index').text(currentIndex + 1);
			},
			onSlideBefore: function ($slideElement, oldIndex, newIndex){
				$('#slide-counter1 .current-index').text(newIndex + 1);
                
			}
            
		});
		$('#slide-counter1, .slides-count-1').append('<span>' + slider1.getSlideCount()+ '</span>');
	};
    

		slider2 = $('#slideshow2').bxSlider({
            
            //slideWidth: 240,
            //maxSlides: 3, 
            moveSlides: 1,
            slideMargin: 20,
			infiniteLoop: false,
            hideControlOnEnd: true, 
			pager: false, 
            adaptiveHeight: true,
            nextText: '<i class="fas fa-angle-right"></i>',
            prevText: '<i class="fas fa-angle-left"></i>',
            breaks: [{screen:0, slides:1},{screen:576, slides:2},{screen:768, slides:2},{screen:991, slides:3}]
		});
    
        slider3 = $('.slideshow3').bxSlider({
            
            //slideWidth: 240,
            //maxSlides: 3, 
            moveSlides: 1,
            slideMargin: 20,
			infiniteLoop: false,
            hideControlOnEnd: true, 
			pager: false, 
            adaptiveHeight: true,
            nextText: '<i class="fas fa-angle-right"></i>',
            prevText: '<i class="fas fa-angle-left"></i>',
            breaks: [{screen:0, slides:1},{screen:576, slides:2},{screen:768, slides:2},{screen:991, slides:3}]
		});
        
    
    $('#slideshow2 .feed-slide').each(function() {
        
        $(this).find('.p-block').each(function() {

            var text = $(this).find('.vis-text'),
                openHidden = $(this).find('.open-hidden a'),
                visTextHeight = $('.vis-text').outerHeight(),
                visInnerHeight = $('.vis-inner').outerHeight();
                
            
            openHidden.on('click', function(e) {
                e.preventDefault();
                
                if ($(this).hasClass('to-hide')) {
                    text.css({height: visTextHeight + 'px'});
                    $(this).text('читать полностью').removeClass('to-hide');
                    if($('.to-hide').length < 1) {
                        $('.feedbacks .bx-viewport').removeClass('active');
                    }
                }
                else {
                    text.css({height: visInnerHeight + 'px'});
                    $(this).text('свернуть').addClass('to-hide');
                    $('.feedbacks .bx-viewport').addClass('active');
                }

            });
            
            $('.feedbacks .bx-next, .feedbacks .bx-prev').click(function(){
                $('.feedbacks .bx-viewport').removeClass('active');
                text.css({height: visTextHeight + 'px'});
                $('.open-hidden a').text('читать полностью').removeClass('to-hide');
            });
        
        });
        
    });

    
    
    //-----БОЛЬШЕ-МЕНЬШЕ-----//
    $('.minus').click(function (e) {
        e.preventDefault();
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 0 ? 0 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function (e) {
        e.preventDefault(); 
            var $input = $(this).parent().find('input');
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
    });
    
});


$(window).on('load', function(){
   $('#bx-pager').each(function(){
        
            var more = $(this).find('li:nth-of-type(4)');
            var parentInner = $(this).find('ul'),
                pager = $(this),
                liHeight = $(window).width() > 575 ? pager.find('li').outerHeight() : pager.find('li').outerHeight() * 2,
                ulHeight = parentInner.outerHeight();
        console.log(ulHeight);
            more.addClass('show-all');
            parentInner.append('<div class="less-text">Свернуть</div>');
            $('.less-text').slideUp();
        
        
        var lessHeight = $('.less-text').outerHeight();
        pager.css({height: liHeight});
        
            more.on('click', function(e){
                e.preventDefault();
                
                more.removeClass('show-all');
                pager.css({height: ulHeight + lessHeight + 'px'});
                $('.less-text').slideDown();

            });
        
            $('.less-text').click(function(){
                more.addClass('show-all');
                pager.css({height: liHeight});
            });

    }); 
});


function menuFixed (menu, offseting) {
    
    var height = $(menu).outerHeight(),
        offsetParametr = offseting,
        offsetTop = $(menu).offset().top + offsetParametr,
        wrapper = 'wrapper-' + $(menu).attr('class');

    $(menu).wrap('<div class="' + wrapper +'"></div>');
    $('.' + wrapper).css({minHeight: height});

    $(window).scroll(function(){
        if($(window).scrollTop() >= offsetTop) {
            $(menu).addClass('active');
        }
        else {
            $(menu).removeClass('active');
        }
    });

}