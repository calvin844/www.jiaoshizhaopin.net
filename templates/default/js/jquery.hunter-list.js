function allaround(dir,getstr){
	fillCity("#divCityCate"); // 地区内容填充
	// 恢复地区选中条件
	if(getstr) {
			var recoverCityArray = getstr.split(",");
			$.each(recoverCityArray, function(index, val) {
				 var democityArray = val.split(".");
				 if(democityArray[1] == 0) { // 如果第二个参数为 0 说明选择的是一级地区
				 	$(".citycatebox p a").each(function() {
				 		if(democityArray[0] == $(this).attr("rcoid")) {
				 			$(this).addClass('selectedcolor');
				 		}
				 	});
				 } else { // 选择的是二级地区
				 	$(".citycatebox .subcate a").each(function() {
				 		if(democityArray[1] == $(this).attr("rcoid")) {
				 			$(this).addClass('selectedcolor');
				 		}
				 	});
				 }
			});
			copyCityItem();
			var a_cn=new Array();
			$("#cityAcq a").each(function(index) {
				var checkText = $(this).attr('title');
				a_cn[index]=checkText;
			});
			$("#cityText").html(a_cn.join(","));
			$("#cityText").css("color","#4095ef");
			$("#jobsCity").css("border-color","#4095ef");
	}
	/* 地区列表点击显示到已选 */
	$("#divCityCate li p a").unbind().live('click', function(){
		// 判断选择的数量是否超出
		if($("#divCityCate .selectedcolor").length >= 5) {
			$("#citydropcontent").show(0).delay(3000).fadeOut("slow");
		} else {
			$(this).addClass('selectedcolor');
			copyCityItem(); // 将地区已选的拷贝
		}
	});
	$("#divCityCate .subcate a").unbind().live('click', function() {
		// 判断选择的数量是否超出
		if($("#divCityCate .selectedcolor").length >= 5) {
			$("#citydropcontent").show(0).delay(3000).fadeOut("slow");
		} else {
			if($(this).attr("p") == "qb") {
				$(this).parent().prev().find('font a').addClass('selectedcolor');
				$(this).parent().find('a').removeClass('selectedcolor');
			} else {
				$(this).parent().prev().find('font a').removeClass('selectedcolor');
				$(this).addClass('selectedcolor');
			}
			copyCityItem(); // 将地区已选的拷贝
		}
	});
	// 地区确定选择
	$("#citySure").unbind().click(function() {
		var a_cn=new Array();
		var a_id=new Array();
		$("#cityAcq a").each(function(index) {
			// 如果选择的是一级地区将第二个参数补 0
			var chid = new Array();
			if($(this).attr('pid')) {
				chid = $(this).attr('pid').split(".");
				if(chid.length < 2) {
					chid.push(0);
				}
			}
			var checkID = chid.join(".");
			var checkText = $(this).attr('title');
			a_id[index]=checkID;
			a_cn[index]=checkText;
		});
		if (a_cn.length > 0) {
			$("#cityText").html(a_cn.join(","));
			$("#cityText").css("color","#4095ef");
			$("#jobsCity").css("border-color","#4095ef");
			$("#district_cn").val(a_cn.join(","));
			$("#district_id").val(a_id.join(","));
		} else {
			$("#cityText").html("请选择地区分类");
			$("#cityText").css("color","#cccccc");
			$("#jobsCity").css("border-color","#cccccc");
			$("#district_cn").val("");
			$("#district_id").val("");
		}
		$("#divCityCate").hide();
	});
	
	/*
	 * 74cms  地区内容的填充
	|   @param: fillID      -- 填入的ID
	*/
	function fillCity(fillID){
		var citystr = '';
		citystr += '<tr>';
		citystr += '<td><ul class="jobcatelist">';
		$.each(QS_city_parent, function(pindex, pval) {
			if(pval) {
				var citys = pval.split(",");
				citystr += '<li>';
				citystr += '<p><font><a rcoid="'+citys[0]+'" pid="'+citys[0]+'" title="'+citys[1]+'" href="javascript:;">'+citys[1]+'</a></font></p>';
				if(QS_city[citys[0]]) {
					citystr += '<div class="subcate" style="display:none;">';
					var ccitysArray = QS_city[citys[0]].split("|");
					citystr += '<a p="qb" href="javascript:;">不限</a>';
					$.each(ccitysArray, function(cindex, cval) {
						if(cval) {
							var ccitys = cval.split(",");
							citystr += '<a rcoid="'+ccitys[0]+'" title="'+ccitys[1]+'" pid="'+citys[0]+'.'+ccitys[0]+'" href="javascript:;">'+ccitys[1]+'</a>';
						}
					});
					citystr += '</div>';
				}
				citystr += '</li>';
			}
		});
		citystr += '</ul></td>';
		citystr += '</tr>';
		$(fillID+" tbody").html(citystr);
		$(".jobcatelist li").each(function() {
			if($(this).find('.subcate').length <= 0) {
				$(this).find('font').css("background","none");
			}
		});
	}
	/*
	 * 74cms  拷贝地区已选
	*/
	function copyCityItem() {
		var cityacqhtm = '';
		$("#divCityCate .selectedcolor").each(function() {
			cityacqhtm += '<a pid="'+$(this).attr('pid')+'" href="javascript:;" title="'+$(this).attr('title')+'"><div class="text">'+$(this).attr('title')+'</div><div class="close"></div></a>';
		});
		$("#cityAcq").html(cityacqhtm);
		// 已选项目绑定点击事件
		$("#cityAcq a").unbind().click(function() {
			var selval = $(this).attr('title');
			$("#divCityCate .selectedcolor").each(function() {
				if ($(this).attr('title') == selval) {
					$(this).removeClass('selectedcolor');
					copyCityItem();
				}
			});
		});
		// 清空
		$("#cityEmpty").unbind().click(function() {
			$("#cityAcq").html("");
			$("#divCityCate .selectedcolor").each(function() {
				$(this).removeClass('selectedcolor');
			});
		});
	}
}