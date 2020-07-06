function allaround(dir){
	if($("#divCityCate").length > 0) {
		fillCity("#divCityCate"); // 地区填充
		// 恢复地区选中条件
		if($("#sdistrict").val()) {
			var scityid = $("#sdistrict").val();
			if(scityid == 0) {
				var dcityid = $("#district").val();
				$("#divCityCate .citycatebox p a").each(function() {
					if(dcityid == $(this).attr("rcoid")) {
						$(this).addClass('selectedcolor');
					}
				});
			} else {
				$("#divCityCate .citycatebox .subcate a").each(function() {
					if(scityid == $(this).attr("rcoid")) {
						$(this).parent().prev().find('font a').addClass('selectedcolor');
						$(this).addClass('selectedcolor');
					}
				});
			}
		}
		/* 地区点击显示到已选 */
		$("#divCityCate li p a").unbind().live('click', function(){
			$("#divCityCate li p a").each(function() {
				$(this).removeClass('selectedcolor');
			});
			$(this).addClass('selectedcolor');
			var checkID = $(this).attr('pid').split(".");
			var checkText = $(this).attr('title');
			$("#cityText").html(checkText);
			$("#district_cn").val(checkText);
			$("#district").val(checkID[0]);
			$("#sdistrict").val(checkID[1]);
			$("#divCityCate").hide();
		});
		$("#divCityCate .subcate a").unbind().live('click', function() {		
			$("#divCityCate .subcate a").each(function() {
				$(this).parent().prev().find('font a').removeClass('selectedcolor');
				$(this).removeClass('selectedcolor');
			});
			$(this).parent().prev().find('font a').addClass('selectedcolor');
			$(this).addClass('selectedcolor');
			var checkID = $(this).attr('pid').split(".");
			var checkText = $(this).attr('title');
			$("#cityText").html(checkText);
			$("#district_cn").val(checkText);
			$("#district").val(checkID[0]);
			$("#sdistrict").val(checkID[1]);
			$("#divCityCate").hide();
		});
	}
	if($("#divCityCateJob").length > 0) {
		fillCityJob("#divCityCateJob"); // 擅长职能填充
		// 恢复擅长职能选中条件
		if($("#intention_jobs_id").val()) {
				var recoverCityArray = $("#intention_jobs_id").val().split(",");
				$.each(recoverCityArray, function(index, val) {
					 var democityArray = val.split(".");
					 if(democityArray[1] == 0) { // 如果第二个参数为 0 说明选择的是一级
					 	$(".citycatebox p a").each(function() {
					 		if(democityArray[0] == $(this).attr("rcoid")) {
					 			$(this).addClass('selectedcolor');
					 		}
					 	});
					 } else { // 选择的是二级
					 	$(".citycatebox .subcate a").each(function() {
					 		if(democityArray[1] == $(this).attr("rcoid")) {
					 			$(this).addClass('selectedcolor');
					 		}
					 	});
					 }
				});
				copyCityJobItem();
		}
		/* 擅长职能点击显示到已选 */
		$("#divCityCateJob p a").unbind().live('click', function() {
			// 判断选择的数量是否超出
			if($("#divCityCateJob .selectedcolor").length >= 5) {
				$("#citydropcontent").show(0).delay(3000).fadeOut("slow");
			} else {
				$(this).addClass('selectedcolor');
				copyCityJobItem(); // 将擅长职能已选的拷贝
			}
		});
		$("#divCityCateJob .subcate a").unbind().live('click', function() {
			// 判断选择的数量是否超出
			if($("#divCityCateJob .selectedcolor").length >= 5) {
				$("#citydropcontent").show(0).delay(3000).fadeOut("slow");
			} else {
				if($(this).attr("p") == "qb") {
					$(this).parent().prev().find('font a').addClass('selectedcolor');
					$(this).parent().find('a').removeClass('selectedcolor');
				} else {
					$(this).parent().prev().find('font a').removeClass('selectedcolor');
					$(this).addClass('selectedcolor');
				}
				copyCityJobItem(); // 将擅长职能已选的拷贝
			}
		});
		// 擅长职能确定选择
		$("#citySure").unbind().click(function() {
			var a_cn=new Array();
			var a_id=new Array();
			$("#cityAcq a").each(function(index) {
				// 如果选择的是一级将第二个参数补 0
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
				$("#cityTextJob").html(a_cn.join(","));
				$("#intention_jobs").val(a_cn.join(","));
				$("#intention_jobs_id").val(a_id.join(","));
			} else {
				$("#cityTextJob").html("请选择地区分类");
				$("#intention_jobs").val("");
				$("#intention_jobs_id").val("");
			}
			$("#divCityCateJob").hide();
		});
	}
	if($("#divTradCate").length > 0) {
		fillTrad("#divTradCate"); // 行业填充内容
		// 恢复行业选中条件
		if($("#trade").val()) {
			var recoverTradArray = $("#trade").val().split(",");
			$.each(recoverTradArray, function(index, val) {
				 $("#tradList a").each(function() {
					if(val == $(this).attr('cln')) {
						$(this).addClass('selectedcolor');
					}
				});
			});
			copyTradItem();
			var a_cn = new Array();
			$("#tradAcq a").each(function(index) {
				var checkText = $(this).attr('title');
				a_cn[index]=checkText;
			});
			$("#tradText").html(a_cn.join(","));
		}
		/* 行业列表点击显示到已选 */
		$("#tradList li a").unbind().live('click', function() {
			// 判断选择的数量是否超出
			if($("#tradList .selectedcolor").length >= 5) {
				$("#tradropcontent").show(0).delay(3000).fadeOut("slow");
			} else {
				$(this).addClass('selectedcolor');
				copyTradItem(); // 将行业已选的拷贝
			}
		});
		// 行业确定选择
		$("#tradSure").unbind().click(function() {
			var a_cn=new Array();
			var a_id=new Array();
			$("#tradAcq a").each(function(index) {
				var checkID = $(this).attr('rel');
				var checkText = $(this).attr('title');
				a_id[index]=checkID;
				a_cn[index]=checkText;
			});
			if (a_cn.length > 0) {
				$("#tradText").html(a_cn.join(","));
				$("#trade_cn").val(a_cn.join(","));
				$("#trade").val(a_id.join(","));
			} else {
				$("#tradText").html("请选择行业类别");
				$("#trade_cn").val("");
				$("#trade").val("");
			}
			$("#divTradCate").hide();
		});
	}
	if($("#divTradCateD").length > 0) {
		fillTrad("#divTradCateD"); // 所属行业填充
		// 恢复公司所属行业
		if($("#tradeD").val()) {
			var tradid = $("#tradeD").val();
			 $("#tradListD a").each(function() {
				if(tradid == $(this).attr('cln')) {
					$(this).addClass('selectedcolor');
				}
			});
		}
		/* 所属行业列表点击显示到已选 */
		$("#divTradCateD li a").unbind().live('click', function() {
			$("#tradListD a").each(function() {
				$(this).removeClass('selectedcolor');
			});
			$(this).addClass('selectedcolor');
			var checkID = $(this).attr('cln');
			var checkText = $(this).attr('title');
			$("#tradTextD").html(checkText);
			$("#tradeD_cn").val(checkText);
			$("#tradeD").val(checkID);
			$("#divTradCateD").hide();
		});
	}
	if($("#divCityCateAD").length > 0) {
		fillCityJob("#divCityCateAD"); // 职位类别填充
		// 恢复职位类别选中条件
		if($("#subclass").val()) {
			var scityid = $("#subclass").val();
			if(scityid == 0) {
				var ccityid = $("#category").val();
				$("#divCityCateAD .citycatebox p a").each(function() {
					if(ccityid == $(this).attr("rcoid")) {
						$(this).addClass('selectedcolor');
					}
				});
			} else {
				$("#divCityCateAD .citycatebox .subcate a").each(function() {
					if(scityid == $(this).attr("rcoid")) {
						$(this).parent().prev().find('font a').addClass('selectedcolor');
						$(this).addClass('selectedcolor');
					}
				});
			}
		}
		/* 职位类别点击显示到已选 */
		$("#divCityCateAD p a").unbind().live('click', function() {		
			$("#divCityCateAD p a").each(function() {
				$(this).removeClass('selectedcolor');
			});
			$(this).addClass('selectedcolor');
			var checkID = $(this).attr('pid').split(".");
			var checkText = $(this).attr('title');
			$("#cityTextAD").html(checkText);
			$("#category_cn").val(checkText);
			$("#category").val(checkID[0]);
			$("#subclass").val(checkID[1]);
			$("#divCityCateAD").hide();
		});
		$("#divCityCateAD .subcate a").unbind().live('click', function() {		
			$("#divCityCateAD .subcate a").each(function() {
				$(this).parent().prev().find('font a').removeClass('selectedcolor');
				$(this).removeClass('selectedcolor');
			});
			$(this).parent().prev().find('font a').addClass('selectedcolor');
			$(this).addClass('selectedcolor');
			var checkID = $(this).attr('pid').split(".");
			var checkText = $(this).attr('title');
			$("#cityTextAD").html(checkText);
			$("#category_cn").val(checkText);
			$("#category").val(checkID[0]);
			$("#subclass").val(checkID[1]);
			$("#divCityCateAD").hide();
		});
	}
}
function copyCityJobItem() {
	var cityacqhtm = '';
	$("#divCityCateJob .selectedcolor").each(function() {
		cityacqhtm += '<a pid="'+$(this).attr('pid')+'" href="javascript:;" title="'+$(this).attr('title')+'"><div class="text">'+$(this).attr('title')+'</div><div class="close"></div></a>';
	});
	$("#cityAcq").html(cityacqhtm);
	// 已选项目绑定点击事件
	$("#cityAcq a").unbind().click(function() {
		var selval = $(this).attr('title');
		$("#divCityCateJob .selectedcolor").each(function() {
			if ($(this).attr('title') == selval) {
				$(this).removeClass('selectedcolor');
				copyCityJobItem();
			}
		});
	});
	// 清空
	$("#cityEmpty").unbind().click(function() {
		$("#cityAcq").html("");
		$("#divCityCateJob .selectedcolor").each(function() {
			$(this).removeClass('selectedcolor');
		});
	});
}
function copyTradItem() {
	var tradacqhtm = '';
	$("#tradList .selectedcolor").each(function() {
		tradacqhtm += '<a href="javascript:;" rel="'+$(this).attr('cln')+'" title="'+$(this).attr('title')+'"><div class="text">'+$(this).attr('title')+'</div><div class="close" id="c-'+$(this).attr('cln')+'"></div></a>';
	});
	$("#tradAcq").html(tradacqhtm);
	// 已选项目绑定点击事件
	$("#tradAcq a").unbind().click(function() {
		var selval = $(this).attr('title');
		$("#tradList .selectedcolor").each(function() {
			if ($(this).attr('title') == selval) {
				$(this).removeClass('selectedcolor');
				copyTradItem();
			}
		});
	});
	// 清空
	$("#tradEmpty").unbind().click(function() {
		$("#tradAcq").html("");
		$("#tradList .selectedcolor").each(function() {
			$(this).removeClass('selectedcolor');
		});
	});
}
function fillTrad(fillID){
	var tradli = '';
	$.each(QS_trade, function(index, val) {
		if(val) {
			var trads = val.split(",");
		 	tradli += '<li><a title="'+trads[1]+'" cln="'+trads[0]+'" href="javascript:;">'+trads[1]+'</a></li>';
		}
	});
	$(fillID+" ul").html(tradli);
}
function fillCityJob(fillID){
	var citystr = '';
	citystr += '<tr>';
	citystr += '<td><ul class="jobcatelist">';
	$.each(QS_hunter_jobs_parent, function(pindex, pval) {
		if(pval) {
			var citys = pval.split(",");
	 		citystr += '<li>';
	 		citystr += '<p><font><a rcoid="'+citys[0]+'" pid="'+citys[0]+'.0" title="'+citys[1]+'" href="javascript:;">'+citys[1]+'</a></font></p>';
	 		if(QS_hunter_jobs[citys[0]]) {
	 			citystr += '<div class="subcate" style="display:none;">';
	 			var ccitysArray = QS_hunter_jobs[citys[0]].split("|");
		 		$.each(ccitysArray, function(cindex, cval) {
		 			if(cval) {
			 			var ccitys = cval.split(",");
			 			citystr += '<a rcoid="'+ccitys[0]+'" title="'+citys[1]+'/'+ccitys[1]+'" pid="'+citys[0]+'.'+ccitys[0]+'" href="javascript:;">'+ccitys[1]+'</a>';
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
function fillCity(fillID){
	var citystr = '';
	citystr += '<tr>';
	citystr += '<td><ul class="jobcatelist">';
	$.each(QS_city_parent, function(pindex, pval) {
		if(pval) {
			var citys = pval.split(",");
	 		citystr += '<li>';
	 		citystr += '<p><font><a rcoid="'+citys[0]+'" pid="'+citys[0]+'.0" title="'+citys[1]+'" href="javascript:;">'+citys[1]+'</a></font></p>';
	 		if(QS_city[citys[0]]) {
	 			citystr += '<div class="subcate" style="display:none;">';
	 			var ccitysArray = QS_city[citys[0]].split("|");
		 		$.each(ccitysArray, function(cindex, cval) {
		 			if(cval) {
			 			var ccitys = cval.split(",");
			 			citystr += '<a rcoid="'+ccitys[0]+'" title="'+citys[1]+'/'+ccitys[1]+'" pid="'+citys[0]+'.'+ccitys[0]+'" href="javascript:;">'+ccitys[1]+'</a>';
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