function allaround(dir){
	if($("#divCityCate").length > 0) {
		fillCity("#divCityCate"); // �������
		// �ָ�����ѡ������
		if($("#sdistrict").val()) {
			var scityid = $("#sdistrict").val();
			$(".citycatebox .subcate a").each(function() {
				if(scityid == $(this).attr("rcoid")) {
					$(this).parent().prev().find('font a').addClass('selectedcolor');
					$(this).addClass('selectedcolor');
				}
			});
		}
		/* ���������ʾ����ѡ */
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
}
function fillCity(fillID){
	var citystr = '';
	citystr += '<tr>';
	citystr += '<td><ul class="jobcatelist">';
	$.each(QS_city_parent, function(pindex, pval) {
		if(pval) {
			var citys = pval.split(",");
	 		citystr += '<li>';
	 		citystr += '<p><font><a rcoid="'+citys[0]+'" pid="'+citys[0]+'" title="'+citys[1]+'" href="javascript:;">'+citys[1]+'</a></font></p>';
	 		citystr += '<div class="subcate" style="display:none;">';
	 		if(QS_city[citys[0]]) {
	 			var ccitysArray = QS_city[citys[0]].split("|");
		 		$.each(ccitysArray, function(cindex, cval) {
		 			if(cval) {
			 			var ccitys = cval.split(",");
			 			citystr += '<a rcoid="'+ccitys[0]+'" title="'+citys[1]+'/'+ccitys[1]+'" pid="'+citys[0]+'.'+ccitys[0]+'" href="javascript:;">'+ccitys[1]+'</a>';
		 			}
		 		});
	 		}
	 		citystr += '</div>';
	 		citystr += '</li>';
		}
	});
	citystr += '</ul></td>';
	citystr += '</tr>';
	$(fillID+" tbody").html(citystr);
}