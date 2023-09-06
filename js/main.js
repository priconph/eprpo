
				$('#sidebar-nav>.withsubmenu').click(function(){
					$(this).next('ul').slideToggle(400).siblings('ul').slideUp(400);
				});

				$('.submenu li').click(function(){
					$(this).addClass('active').siblings().removeClass('active').parent().siblings().removeClass('active').children().removeClass('active');
				});

				$('.nosubmenu').click(function(){
					$(this).addClass('active').siblings().removeClass('active').children().removeClass('active');
				});



				$('.submenu>li, .nosubmenu').click(function(){
					$('#loading-img').removeClass('hidden');
					var js = pagescripts+$(this).data('js');
					$.getScript(js);
					$('.content').load($(this).data('file'),function(){
						setTimeout(function(){
							$('#loading-img').addClass('hidden');
						},300);
						
					});
					$('.content').animate({scrollTop:0},200);

					// if($(this).attr('id')!='dash'){
					// 	clearTimeout(windowRefresh);
					// }


				});

				









