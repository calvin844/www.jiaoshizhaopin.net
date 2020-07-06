
	$('.search_type').mouseover(function(){
		$(this).find('div.type_box').show();
		$(this).find('div.job_type_box').show();		
	})
	
	$('.search_type').mouseleave(function(){
		$(this).find('div.type_box').hide();
		$(this).find('div.job_type_box').hide();		
	})
	
	$('div.a_box div.type_box div.hot ul li').click(function(){
		var str = $(this).html();
		var id = $(this).attr('val');
		$(this).parents('div.search_type').find('p.flag').html(str);
		var input_id = $(this).parents('div.search_type').find('p.flag').attr('id');
		$('input#'+input_id).attr('data',id);
		$('div.type_box').hide();
	})
	
	$('li.selected').click(function(){
		$(this).addClass('selected');
		$(this).parents('div.search_type').find('ul.select_box').removeClass('select_ul');
		$(this).parents('div.search_type').find('#selected_bt').removeClass('selected');
		$(this).parents('div.search_type').find('#selected_bt').hide();
		var p_id = $(this).parents('div.search_type').find('ul.select_box').attr('id');
		$.get("/plus/ajax_index_c.php", {"act":"index_search_type","id":0,"type":p_id},
			function (data){	
				$('#'+p_id).html(data);
			}
		);
	})
	
	$(document).on('click','div.a_box div.type_box ul.select_box li',function(){
		if($(this).parent().hasClass('select_ul')){
			var str = $(this).html();
			if(str == "全部"){
				str = $(this).parents('div.search_type').find('#selected_bt').html();
			}
			var id = $(this).attr('val');
			$(this).parents('div.search_type').find('p.flag').html(str);
			var input_id = $(this).parents('div.search_type').find('p.flag').attr('id');
			$('input#'+input_id).attr('data',id);
			$('div.type_box').hide();
		}else{
			var p_id = $(this).parent().attr('id');
			var id = $(this).attr('val');
			$.get("/plus/ajax_index_c.php", {"act":"index_search_type","id":id,"type":p_id},
				function (data){	
					$('#'+p_id).html(data);
				}
			);
			if(!$(this).parents('div.search_type').find('#selected_bt').hasClass('selected')){
				$(this).parents('div.search_type').find('#selected_bt').addClass('selected');
				$(this).parents('div.search_type').find('#selected_bt').show();
			}
			var str = $(this).html();
			$(this).parents('div.search_type').find('#select_bt').removeClass('selected');
			$(this).parents('div.search_type').find('#selected_bt').html(str);
			$(this).parent().addClass('select_ul');
		}
	})
	
	$('.no_select').click(function(){
		var id_str = $(this).parents('div.search_type').find('p.flag').attr('id');
		if(id_str == "city"){
			var str = "地区";	
		}else{
			var str = "类别";
		}
		$(this).parents('div.search_type').find('p.flag').html(str);
		$('input#'+id_str).attr('data',0);
		$(this).parents('div.search_type').find('div').hide();
		return false;
	})
	
	$('div.job_type_box ul.job_ptype_ul li').click(function(){
		$('div.job_type_box ul.job_ptype_ul li').removeClass('cur');
		$(this).addClass('cur');
		var id = $(this).attr('val');
		$.get("/plus/ajax_index_c.php", {"act":"index_search_type","id":id,"type":"type_box"},
			function (data){	
				$('ul.job_type_ul').html(data);
			}
		);
		var min_height = $(this).parents('div.job_type_box').height()-1;
		$('ul.job_type_ul').css('height',min_height);
		$('ul.job_type_ul').show();
	})
	
	
	
	$(document).on('click','ul.job_type_ul li',function(){
		var str = $(this).html();
		if(str == "全部"){
			str = $('div.job_type_box ul.job_ptype_ul li.cur span').html();
		}
		var id = $(this).attr('val');
		$(this).parents('div.search_type').find('p.flag').html(str);
		var input_id = $(this).parents('div.search_type').find('p.flag').attr('id');
		$('input#'+input_id).attr('data',id);
		$('div.job_type_box').hide();
	})
	
	
	$('.search_str').focus(function(){
		$(this).val('');	
	})
	
	$('.search_str').blur(function(){
		if($(this).val() == ""){
			$(this).val('请输入职位关键字');	
		}	
	})
        $('#search_jobs_form').submit(function(){
		var key = $(this).find('.search_str').val();
		if(key == "请输入职位关键字"){
			key = "";
		}
		var jobcategory = $(this).find('input#job_type').attr('data');
		if(jobcategory == 0){
			jobcategory = "";
		}
		var citycategory = $(this).find('input#city').attr('data');
		if(citycategory == 0){
			citycategory = "";
		}
		window.location.href = "/jobs/jobs-list.php?search_index=1&key="+key+"&jobcategory="+jobcategory+"&citycategory="+citycategory;
                return false;
        })
        /*
	$('.search_submit').click(function(){
		var key = $('.search_str').val();
		if(key == "请输入职位关键字"){
			key = "";
		}
		var jobcategory = $('input#job_type').attr('data');
		if(jobcategory == 0){
			jobcategory = "";
		}
		var citycategory = $('input#city').attr('data');
		if(citycategory == 0){
			citycategory = "";
		}
		window.location.href = "/jobs/jobs-list.php?key="+key+"&jobcategory="+jobcategory+"&citycategory="+citycategory;
	})
*/
	
	$('.a_box .left .box li').mouseover(function(){
		$(this).find('span.title').css("color","#feb94d");
	})
	
	$('.a_box .left .box li').mouseleave(function(){
		$(this).find('span.title').removeAttr('style');
	})
	
	$(document).on('mouseover','.login_box',function(){
		$(this).find('ul').show();	
	})
	
        $(document).on('mouseleave','.login_box',function(){
		$(this).find('ul').hide();	
	})
        
        
        $(function(){
                $("div#new_teacher_scroll").myScroll({
                        speed:40, //数值越大，速度越慢
                        rowHeight:28 //li的高度
                });
                
                /*$("div#new_company_scroll").myScroll({
                        speed:40, //数值越大，速度越慢
                        rowHeight:28 //li的高度
                });*/
        });
        
        