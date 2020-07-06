function allaround(dir, getstr) {
    var checkedstr = "";
    fillTrad("#divTradCate"); // 行业填充内容
    // 恢复行业选中条件
    if (getstr) {
        if (getstr[0]) {
            var recoverTradArray = getstr[0].split(",");
            $.each(recoverTradArray, function(index, val) {
                $("#tradList a").each(function() {
                    if (val == $(this).attr('cln')) {
                        $(this).addClass('selectedcolor');
                    }
                });
            });
            copyTradItem();
            var a_cn = new Array();
            $("#tradAcq a").each(function(index) {
                var checkText = $(this).attr('title');
                a_cn[index] = checkText;
            });
            $("#tradText").html(a_cn.join(","));
            $("#tradText").css("color", "#4095ef");
            $("#jobsTrad").css("border-color", "#4095ef");
            checkedstr += '<a href="javascript:;" ty="trade_id" class="cnt"><span>' + $("#tradText").html() + '</span><i class="del"></i></a>';
        }
    }
    /* 行业列表点击显示到已选 */
    $("#tradList li a").unbind().live('click', function() {
        // 判断选择的数量是否超出
        if ($("#tradList .selectedcolor").length >= 5) {
            $("#tradropcontent").show(0).delay(3000).fadeOut("slow");
        } else {
            $(this).addClass('selectedcolor');
            copyTradItem(); // 将行业已选的拷贝
        }
    });
    // 行业确定选择
    $("#tradSure").unbind().click(function() {
        var a_cn = new Array();
        var a_id = new Array();
        $("#tradAcq a").each(function(index) {
            var checkID = $(this).attr('rel');
            var checkText = $(this).attr('title');
            a_id[index] = checkID;
            a_cn[index] = checkText;
        });
        if (a_cn.length > 0) {
            $("#tradText").html(a_cn.join(","));
            $("#tradText").css("color", "#4095ef");
            $("#jobsTrad").css("border-color", "#4095ef");
            $("#trade_cn").val(a_cn.join(","));
            $("#trade_id").val(a_id.join(","));
        } else {
            $("#tradText").html("请选择行业类别");
            $("#tradText").css("color", "#cccccc");
            $("#jobsTrad").css("border-color", "#cccccc");
            $("#trade_cn").val("");
            $("#trade_id").val("");
        }
        $("#divTradCate").hide();
    });
    fillJobs("#divJobCate"); // 职位填充内容
    // 恢复职位选中条件
    if (getstr) {
        if (getstr[1]) {
            var jobArray = getstr[1].split(".");
            if (jobArray[2] == "0" && jobArray[1] != "0") {
                var id = jobArray[1];
            } else if (jobArray[1] == "0" && jobArray[0] != "0") {
                var id = jobArray[0];
            } else {
                var id = jobArray[2];
            }
            $.ajax({
                type: 'get',
                url: '/plus/ajax_index_c.php?act=page_search_getcn&id=' + id + '&table=category_jobs&get=categoryname',
                async: false,
                success: function(data) {
                    if (data != "") {
                        $('#jobs_id').find('p.flag').html(data + "<i></i>");
                        $('#searckeybox').find('input#jobs_id').val(getstr[1]);
                        $('#searckeybox').find('input#jobs_cn').val(data);
                        $('#jobs_id').find('p.flag').html(data + "<i></i>");
                        $('#jobs_id').css("border-color", "#4095ef");
                        $('#jobs_id').find('p.flag').css("color", "#4095ef");
                        checkedstr += '<a href="javascript:;" ty="jobs_id" class="cnt"><span>' + data + '</span><i class="del"></i></a>';
                    }
                }
            });
            /*
             var recoverJobArray = getstr[1].split(",");
             $.each(recoverJobArray, function(index, val) {
             var demojobArray = val.split(".");
             if(demojobArray[2] == "0") { // 如果第三个参数是0 则只选到第二级
             $(".jobcatebox p a").each(function() {
             if(demojobArray[1] == $(this).attr("rcoid")) {
             $(this).addClass('selectedcolor');
             }
             });
             } else {
             $(".jobcatebox .subcate a").each(function() {
             if(demojobArray[2] == $(this).attr("rcoid")) {
             $(this).addClass('selectedcolor');
             }
             });
             }
             });
             copyJobItem();
             var a_cn=new Array();
             $("#jobAcq a").each(function(index) {
             var checkText = $(this).attr('title');
             a_cn[index]=checkText;
             });
             $("#jobText").html(a_cn.join(","));
             $("#jobText").css("color","#4095ef");
             $("#jobsSort").css("border-color","#4095ef");
             checkedstr += '<a href="javascript:;" ty="jobs_id" class="cnt"><span>'+$("#jobText").html()+'</span><i class="del"></i></a>';
             */
        }

    }
    /* 职位列表点击显示到已选 */
    $("#divJobCate li p a").unbind().live('click', function() {
        // 判断选择的数量是否超出
        if ($("#divJobCate .selectedcolor").length >= 5) {
            $("#jobdropcontent").show(0).delay(3000).fadeOut("slow");
        } else {
            $(this).addClass('selectedcolor');
            copyJobItem(); // 将职位已选的拷贝
        }
    });
    $("#divJobCate .subcate a").unbind().live('click', function() {
        // 判断选择的数量是否超出
        if ($("#divJobCate .selectedcolor").length >= 5) {
            $("#jobdropcontent").show(0).delay(3000).fadeOut("slow");
        } else {
            if ($(this).attr("p") == "qb") {
                $(this).parent().prev().find('font a').addClass('selectedcolor');
                $(this).parent().find('a').removeClass('selectedcolor');
            } else {
                $(this).parent().prev().find('font a').removeClass('selectedcolor');
                $(this).addClass('selectedcolor');
            }
            copyJobItem(); // 将职位已选的拷贝
        }
    });
    // 职位确定选择
    $("#jobSure").unbind().click(function() {
        var a_cn = new Array();
        var a_id = new Array();
        $("#jobAcq a").each(function(index) {
            // 如果选择的是二级分类将第三个参数补0
            var chid = new Array();
            if ($(this).attr('pid')) {
                chid = $(this).attr('pid').split(".");
                if (chid.length < 3) {
                    chid.push(0);
                }
            }
            var checkID = chid.join(".");
            var checkText = $(this).attr('title');
            a_id[index] = checkID;
            a_cn[index] = checkText;
        });
        if (a_cn.length > 0) {
            $("#jobText").html(a_cn.join(","));
            $("#jobText").css("color", "#4095ef");
            $("#jobsSort").css("border-color", "#4095ef");
            $("#jobs_cn").val(a_cn.join(","));
            $("#jobs_id").val(a_id.join(","));
        } else {
            $("#jobText").html("请选择职位类别");
            $("#jobText").css("color", "#cccccc");
            $("#jobsSort").css("border-color", "#cccccc");
            $("#jobs_cn").val("");
            $("#jobs_id").val("");
        }
        $("#divJobCate").hide();
    });
    fillCity("#divCityCate"); // 地区内容填充
    // 恢复地区选中条件
    if (getstr) {
        if (getstr[2]) {
            var cityArray = getstr[2].split(".");
            if (cityArray[1] == "0") {
                var id = cityArray[0];
            } else {
                var id = cityArray[1];
            }
            $.ajax({
                type: 'get',
                url: '/plus/ajax_index_c.php?act=page_search_getcn&id=' + id + '&table=category_district&get=categoryname',
                async: false,
                success: function(data) {
                    if (data != "") {
                        $('#district_id').find('p.flag').html(data + "<i></i>");
                        $('#searckeybox').find('input#district_id').val(getstr[2]);
                        $('#searckeybox').find('input#district_cn').val(data);
                        $('#district_id').css("border-color", "#4095ef");
                        $('#district_id').find('p.flag').css("color", "#4095ef");
                        checkedstr += '<a href="javascript:;" ty="district_id" class="cnt"><span>' + data + '</span><i class="del"></i></a>';
                    }
                }
            });
            /*
             var recoverCityArray = getstr[2].split(",");
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
             checkedstr += '<a href="javascript:;" ty="district_id" class="cnt"><span>'+$("#cityText").html()+'</span><i class="del"></i></a>';
             */
        }
    }
    /* 地区列表点击显示到已选 */
    $("#divCityCate li p a").unbind().live('click', function() {
        // 判断选择的数量是否超出
        if ($("#divCityCate .selectedcolor").length >= 5) {
            $("#citydropcontent").show(0).delay(3000).fadeOut("slow");
        } else {
            $(this).addClass('selectedcolor');
            copyCityItem(); // 将地区已选的拷贝
        }
    });
    $("#divCityCate .subcate a").unbind().live('click', function() {
        // 判断选择的数量是否超出
        if ($("#divCityCate .selectedcolor").length >= 5) {
            $("#citydropcontent").show(0).delay(3000).fadeOut("slow");
        } else {
            if ($(this).attr("p") == "qb") {
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
        var a_cn = new Array();
        var a_id = new Array();
        $("#cityAcq a").each(function(index) {
            // 如果选择的是一级地区将第二个参数补 0
            var chid = new Array();
            if ($(this).attr('pid')) {
                chid = $(this).attr('pid').split(".");
                if (chid.length < 2) {
                    chid.push(0);
                }
            }
            var checkID = chid.join(".");
            var checkText = $(this).attr('title');
            a_id[index] = checkID;
            a_cn[index] = checkText;
        });
        if (a_cn.length > 0) {
            $("#cityText").html(a_cn.join(","));
            $("#cityText").css("color", "#4095ef");
            $("#jobsCity").css("border-color", "#4095ef");
            $("#district_cn").val(a_cn.join(","));
            $("#district_id").val(a_id.join(","));
        } else {
            $("#cityText").html("请选择地区分类");
            $("#cityText").css("color", "#cccccc");
            $("#jobsCity").css("border-color", "#cccccc");
            $("#district_cn").val("");
            $("#district_id").val("");
        }
        $("#divCityCate").hide();
    });
    // 恢复关键字搜索框
    if (getstr) {
        if (getstr[7]) {
            $('#searckey').css("color", "#4095ef");
            $('.keybox').css("border-color", "#4095ef");
            $('#searckey').val(getstr[7]);
        }
    }
    // 处理关键字搜索框
    $("#searckey").focus(function() {
        if ($(this).val() == "请输入关键字") {
            $(this).val('');
        }
    }).blur(function() {
        if ($(this).val() == "") {
            $(this).val('请输入关键字');
        }
    });
    showOption("#resumeducation", "education", QS_education);	//学历
    // 恢复学历选中条件
    if (getstr) {
        if (getstr[3]) {
            $("#searoptions").show();
            var edustr = "";
            $("#resumeducation a").each(function() {
                $(this).removeClass('selc');
                var demoEduArray = $(this).attr('id').split('-');
                if (getstr[3] == demoEduArray[1]) {
                    $(this).addClass('selc');
                    edustr = $(this).html();
                }
            });
            checkedstr += '<a href="javascript:;" ty="education" class="cnt"><span>' + edustr + '</span><i class="del"></i></a>';
        }
    }
    showOption("#resumexperience", "experience", QS_experience); // 经验
    // 恢复经验选中条件
    if (getstr) {
        if (getstr[4]) {
            $("#searoptions").show();
            var expstr = "";
            $("#resumexperience a").each(function() {
                $(this).removeClass('selc');
                var demoExpArray = $(this).attr('id').split('-');
                if (getstr[4] == demoExpArray[1]) {
                    $(this).addClass('selc');
                    expstr = $(this).html();
                }
            });
            checkedstr += '<a href="javascript:;" ty="experience" class="cnt"><span>' + expstr + '</span><i class="del"></i></a>';
        }
    }
    // 恢复照片选中条件
    if (getstr) {
        if (getstr[5]) {
            $("#searoptions").show();
            var photostr = "";
            $("#resumephoto a").each(function() {
                $(this).removeClass('selc');
                var demoPhotoArray = $(this).attr('id').split('-');
                if (getstr[5] == demoPhotoArray[1]) {
                    $(this).addClass('selc');
                    photostr = $(this).html();
                }
            });
            checkedstr += '<a href="javascript:;" ty="photo" class="cnt"><span>' + photostr + '</span><i class="del"></i></a>';
        }
    }
    // 恢复等级选中条件
    if (getstr) {
        if (getstr[6]) {
            $("#searoptions").show();
            var talentstr = "";
            $("#resumetalent a").each(function() {
                $(this).removeClass('selc');
                var demoTalentArray = $(this).attr('id').split('-');
                if (getstr[6] == demoTalentArray[1]) {
                    $(this).addClass('selc');
                    talentstr = $(this).html();
                }
            });
            checkedstr += '<a href="javascript:;" ty="talent" class="cnt"><span>' + talentstr + '</span><i class="del"></i></a>';
        }
    }
    // 关键字显示到以选择
    if ($("#searckey").attr("data")) {
        checkedstr += '<a href="javascript:;" ty="key" class="cnt"><span>' + $("#searckey").attr("data") + '</span><i class="del"></i></a>';
    }
    // 点击显示更多条件
    $("#showmoreoption").unbind().click(function() {
        if ($("#searoptions").css('display') == "none") {
            $(this).find('span').html("收起更多");
            $(this).find('i').addClass('sq');
            $("#searoptions").show();
        } else {
            $(this).find('span').html("更多条件");
            $(this).find('i').removeClass('sq');
            $("#searoptions").hide();
        }
    });
    // 点击搜索按钮
    $("#btnsearch").unbind().click(function() {
        search_location();
    });
    // 更多条件选项的点击
    $("#searoptions .opt").click(function() {
        var opt = $(this).attr('id');
        opt = opt.split("-");
        $("#searckeybox input[name=" + opt[0] + "]").val(opt[1]);
        search_location();
    });
    // 简历列表页面选中条件的显示
    if (checkedstr != "") {
        $("#showselected").html(checkedstr);
        $("#resumeselected").show();
        $("#showselected").show();
    }
    $("#showselected .cnt").click(function() {
        var opt = $(this).attr('ty');
        $("#searckeybox input[name=" + opt + "]").val('');
        setTimeout(function() {
            search_location();
        }, 1);
    });
    $("#clearallopt").click(function() {
        $("#searckeybox input[type='hidden']").val('');
        $("#searckeybox input[name='key']").val('');
        setTimeout(function() {
            search_location();
        }, 1);
    });
    // 搜索跳转
    function search_location() {
        var key = $("#searckeybox input[name=key]").val();
        if ($("#searckeybox input[name=key]").val() == "请输入关键字") {
            key = '';
        }
        var trade = $("#searckeybox input[name=trade_id]").val();
        var jobcategory = $("#searckeybox input[name=jobs_id]").val();
        var citycategory = $("#searckeybox input[name=district_id]").val();
        var education = $("#searckeybox input[name=education]").val();
        var experience = $("#searckeybox input[name=experience]").val();
        var photo = $("#searckeybox input[name=photo]").val();
        var talent = $("#searckeybox input[name=talent]").val();
        var sort_1 = $("#searckeybox input[name=sort]").val();
        var page = $("#searckeybox input[name=page]").val();
        $.get(dir + "plus/ajax_search_location.php", {"act": "QS_resumelist", "key": key, "trade": trade, "jobcategory": jobcategory, "citycategory": citycategory, "education": education, "experience": experience, "photo": photo, "talent": talent, "sort": sort_1, "page": page},
        function(data, textStatus)
        {
            window.location.href = data;
        }, "text"
                );
    }
    // 照片简历 最新简历切换卡
    $span_tit = $("#jobsmix .tit span");
    $info_div = $("#jobsmix div.info");
    $span_tit.click(function() {
        $(this).addClass('slect').siblings().removeClass('slect');
        var index = $span_tit.index(this);
        $info_div.eq(index).show().siblings('.info').hide();
    });
}
/*
 * 74cms 职位搜索页面 行业内容的填充
 |   @param: fillID      -- 填入的ID
 */
function fillTrad(fillID) {
    var tradli = '';
    $.each(QS_trade, function(index, val) {
        if (val) {
            var trads = val.split(",");
            tradli += '<li><a title="' + trads[1] + '" cln="' + trads[0] + '" href="javascript:;">' + trads[1] + '</a></li>';
        }
    });
    $(fillID + " ul").html(tradli);
}
/*
 * 74cms 职位搜索页面 拷贝行业已选
 */
function copyTradItem() {
    var tradacqhtm = '';
    $("#tradList .selectedcolor").each(function() {
        tradacqhtm += '<a href="javascript:;" rel="' + $(this).attr('cln') + '" title="' + $(this).attr('title') + '"><div class="text">' + $(this).attr('title') + '</div><div class="close" id="c-' + $(this).attr('cln') + '"></div></a>';
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
/*
 * 74cms 职位搜索页面 职位内容的填充
 |   @param: fillID      -- 填入的ID
 */
function fillJobs(fillID) {
    var jobstr = '';
    $.each(QS_jobs_parent, function(pindex, pval) {
        if (pval) {
            jobstr += '<tr>';
            var jobs = pval.split(",");
            jobstr += '<th>' + jobs[1] + '</th>';
            jobstr += '<td><ul class="jobcatelist">';
            var sjobsArray = QS_jobs[jobs[0]].split("|");
            $.each(sjobsArray, function(sindex, sval) {
                if (sval) {
                    var sjobs = sval.split(",");
                    jobstr += '<li>';
                    jobstr += '<p><font><a rcoid="' + sjobs[0] + '" pid="' + jobs[0] + '.' + sjobs[0] + '" title="' + sjobs[1] + '" href="javascript:;">' + sjobs[1] + '</a></font></p>';
                    if (QS_jobs[sjobs[0]]) {
                        jobstr += '<div class="subcate" style="display:none;">';
                        var cjobsArray = QS_jobs[sjobs[0]].split("|");
                        jobstr += '<a p="qb" href="javascript:;">不限</a>';
                        $.each(cjobsArray, function(cindex, cval) {
                            if (cval) {
                                var cjobs = cval.split(",");
                                jobstr += '<a rcoid="' + cjobs[0] + '" title="' + cjobs[1] + '" pid="' + jobs[0] + '.' + sjobs[0] + '.' + cjobs[0] + '" href="javascript:;">' + cjobs[1] + '</a>';
                            }
                        });
                        jobstr += '</div>';
                    }
                    jobstr += '</li>';
                }
            });
            jobstr += '</ul></td>';
            jobstr += '</tr>';
        }
    });
    $(fillID + " tbody").html(jobstr);
    $(".jobcatelist li").each(function() {
        if ($(this).find('.subcate').length <= 0) {
            $(this).find('font').css("background", "none");
        }
    });
}
/*
 * 74cms 职位搜索页面 地区内容的填充
 |   @param: fillID      -- 填入的ID
 */
function fillCity(fillID) {
    var citystr = '';
    citystr += '<tr>';
    citystr += '<td><ul class="jobcatelist">';
    $.each(QS_city_parent, function(pindex, pval) {
        if (pval) {
            var citys = pval.split(",");
            citystr += '<li>';
            citystr += '<p><font><a rcoid="' + citys[0] + '" pid="' + citys[0] + '" title="' + citys[1] + '" href="javascript:;">' + citys[1] + '</a></font></p>';
            if (QS_city[citys[0]]) {
                citystr += '<div class="subcate" style="display:none;">';
                var ccitysArray = QS_city[citys[0]].split("|");
                citystr += '<a p="qb" href="javascript:;">不限</a>';
                $.each(ccitysArray, function(cindex, cval) {
                    if (cval) {
                        var ccitys = cval.split(",");
                        citystr += '<a rcoid="' + ccitys[0] + '" title="' + ccitys[1] + '" pid="' + citys[0] + '.' + ccitys[0] + '" href="javascript:;">' + ccitys[1] + '</a>';
                    }
                });
                citystr += '</div>';
            }
            citystr += '</li>';
        }
    });
    citystr += '</ul></td>';
    citystr += '</tr>';
    $(fillID + " tbody").html(citystr);
    $(".jobcatelist li").each(function() {
        if ($(this).find('.subcate').length <= 0) {
            $(this).find('font').css("background", "none");
        }
    });
}
/*
 * 74cms 职位搜索页面 拷贝地区已选
 */
function copyCityItem() {
    var cityacqhtm = '';
    $("#divCityCate .selectedcolor").each(function() {
        cityacqhtm += '<a pid="' + $(this).attr('pid') + '" href="javascript:;" title="' + $(this).attr('title') + '"><div class="text">' + $(this).attr('title') + '</div><div class="close"></div></a>';
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
/*
 * 74cms 职位搜索页面 拷贝职位已选
 */
function copyJobItem() {
    var jobacqhtm = '';
    $("#divJobCate .selectedcolor").each(function() {
        jobacqhtm += '<a pid="' + $(this).attr('pid') + '" href="javascript:;" title="' + $(this).attr('title') + '"><div class="text">' + $(this).attr('title') + '</div><div class="close"></div></a>';
    });
    $("#jobAcq").html(jobacqhtm);
    // 已选项目绑定点击事件
    $("#jobAcq a").unbind().click(function() {
        var selval = $(this).attr('title');
        $("#divJobCate .selectedcolor").each(function() {
            if ($(this).attr('title') == selval) {
                $(this).removeClass('selectedcolor');
                copyJobItem();
            }
        });
    });
    // 清空
    $("#jobEmpty").unbind().click(function() {
        $("#jobAcq").html("");
        $("#divJobCate .selectedcolor").each(function() {
            $(this).removeClass('selectedcolor');
        });
    });
}
/*
 * 74cms 职位搜索页面 填充更多条件
 |   @param: showid      -- 填入的ID
 |   @param: type      -- 条件的类型
 |   @param: QSarr      -- 类型数组
 */
function showOption(fillID, type, QSarr) {
    var href = "javascript:void(0);";
    var opthtm = '';
    for (var i = 0; i < QSarr.length; i++)
    {
        arr = QSarr[i].split(",");
        opthtm += '<a href="' + href + '" id="' + type + '-' + arr[0] + '" class="opt">' + arr[1] + '</a>';
    }
    $(fillID).html(opthtm);
}

function favorites(ajaxurl)
{
    $(".add_favorites").unbind().click(function() {
        var url = ajaxurl + "user/user_favorites_resume.php?id=" + $(this).attr("id") + "&act=add";
        dialog("添加到人才库", "url:get?" + url, "500px", "auto", "");
    });

    $(".allfavorites").click(function()
    {
        var sltlength = $("#formresumelist input:checked").length;
        if ($("#formresumelist .list input:checked").length == 0)
        {
            dialog("系统提示", "text:请选择简历", "300px", "auto");
        }
        else
        {
            var jidArr = new Array();
            $("#formresumelist .list :checkbox[checked]").each(function(index) {
                jidArr[index] = $(this).val();
            });
            dialog("加入收藏", "url:get?" + ajaxurl + "user/user_favorites_resume.php?id=" + jidArr.join("-") + "&act=add", "500px", "auto", "");
        }
    });
}


// 加入人才库
function allfavorites(ajaxurl)
{
    $(".add_favoritesr").unbind().click(function() {
        var url = ajaxurl + "user/user_favorites_resume.php?id=" + $(this).attr("id") + "&act=add";
        dialog("收藏到人才库", "url:" + url, "500px", "auto", "");
    });

    $(".allfavorites").click(function()
    {
        var sltlength = $("#infolists input:checked").length;
        if ($("#infolists .list input:checked").length == 0)
        {
            dialog("系统提示", "text:请选择简历", "300px", "auto");
        }
        else
        {
            var jidArr = new Array();
            $("#infolists .list :checkbox[checked]").each(function(index) {
                jidArr[index] = $(this).val();
            });
            dialog("加入人才库", "url:" + ajaxurl + "user/user_favorites_resume.php?id=" + jidArr.join("-") + "&act=add", "500px", "auto", "");
        }
    });
}

$(document).on('mouseover', 'div.search_type', function() {
    $(this).find('div.type_box').show();
    $(this).find('div.job_type_box').show();
})

$(document).on('mouseleave', 'div.search_type', function() {
    $(this).find('div.type_box').hide();
    $(this).find('div.job_type_box').hide();
})

$(document).on('click', 'li#select_bt', function() {
    $(this).addClass('selected');
    $(this).parents('div.search_type').find('ul.select_box').removeClass('select_ul');
    $(this).parents('div.search_type').find('#selected_bt').removeClass('selected');
    $(this).parents('div.search_type').find('#selected_bt').hide();
    var p_id = $(this).parents('div.search_type').find('ul.select_box').attr('id');
    $.get("/plus/ajax_index_c.php", {"act": "index_search_type", "id": 0, "type": p_id},
    function(data) {
        $('#' + p_id).html(data);
    }
    );
})

$(document).on('click', 'div.type_box div.hot ul li', function() {
    var str = $(this).html() + "<i></i>";
    var val_str = str.replace("<i></i>", "");
    var id = $(this).attr('val');
    ids = id.split(".");
    id = ids[0] + "." + ids[1];
    $(this).parents('div.search_type').find('p.flag').html(str);
    var input_id = $(this).parents('div.search_type').attr('id');
    var input_cn = $(this).parents('div.search_type').attr('cn');
    $('input#' + input_id).attr('value', id);
    $('input#' + input_cn).attr('value', val_str);
    $('div.type_box').hide();
    $(this).parents('div.search_type').css("border-color", "#4095ef");
    $(this).parents('div.search_type').find('p.flag').css("color", "#4095ef");
})

$(document).on('click', 'div.type_box ul.select_box li', function() {
    if ($(this).parent().hasClass('select_ul')) {
        //alert(1)
        var str = $(this).html() + "<i></i>";
        var val_str = str.replace("<i></i>", "");
        if (val_str == "全部") {
            str = $(this).parents('div.search_type').find('#selected_bt').html() + "<i></i>";
        }
        var id = $(this).attr('val');
        ids = id.split(".");
        id = ids[0] + "." + ids[1];
        $(this).parents('div.search_type').find('p.flag').html(str);
        var input_id = $(this).parents('div.search_type').attr('id');
        var input_cn = $(this).parents('div.search_type').attr('cn');
        $('input#' + input_id).attr('value', id);
        $('input#' + input_cn).attr('value', val_str);
        $('div.type_box').hide();
        $(this).parents('div.search_type').css("border-color", "#4095ef");
        $(this).parents('div.search_type').find('p.flag').css("color", "#4095ef");
    } else {
        //alert(2)
        var p_id = $(this).parent().attr('id');
        var id = $(this).attr('val');
        $.get("/plus/ajax_index_c.php", {"act": "index_search_type", "id": id, "type": p_id},
        function(data) {
            $('#' + p_id).html(data);
        }
        );
        if (!$(this).parents('div.search_type').find('#selected_bt').hasClass('selected')) {
            $(this).parents('div.search_type').find('#selected_bt').addClass('selected');
            $(this).parents('div.search_type').find('#selected_bt').show();
        }
        var str = $(this).html();
        $(this).parents('div.search_type').find('#select_bt').removeClass('selected');
        $(this).parents('div.search_type').find('#selected_bt').html(str);
        $(this).parent().addClass('select_ul');
    }
})

$(document).on('click', '.no_select', function() {
    $(this).parents('div.search_type').css("border-color", "#ccc");
    $(this).parents('div.search_type').find('p.flag').css("color", "#acacac");
    var id_str = $(this).parents('div.search_type').find('p.flag').attr('id');
    if (id_str == "city") {
        var str = "请选择地区分类<i></i>";
    } else {
        var str = "请选择职位类别<i></i>";
    }
    $(this).parents('div.search_type').find('p.flag').html(str);
    var input_id = $(this).parents('div.search_type').attr('id');
    var input_cn = $(this).parents('div.search_type').attr('cn');
    $('input#' + input_id).attr('value', "");
    $('input#' + input_cn).attr('value', "");
    $(this).parents('div.search_type').find('div').hide();
    return false;
})

$(document).on('click', 'div.job_type_box ul.job_ptype_ul li', function() {
    $('div.job_type_box ul.job_ptype_ul li').removeClass('cur');
    $(this).addClass('cur');
    var id = $(this).attr('val');
    $.get("/plus/ajax_index_c.php", {"act": "index_search_type", "id": id, "type": "type_box"},
    function(data) {
        $('ul.job_type_ul').html(data);
    }
    );
    var min_height = $(this).parents('div.job_type_box').height();
    $('ul.job_type_ul').css('height', min_height);
    $('ul.job_type_ul').show();
})



$(document).on('click', 'ul.job_type_ul li', function() {
    var str = $(this).html() + "<i></i>";
    var val_str = str.replace("<i></i>", "");
    if (val_str == "全部") {
        str = $('div.job_type_box ul.job_ptype_ul li.cur span').html() + "<i></i>";
    }
    var id = $(this).attr('val');
    $(this).parents('div.search_type').find('p.flag').html(str);
    var input_id = $(this).parents('div.search_type').attr('id');
    var input_cn = $(this).parents('div.search_type').attr('cn');
    $('input#' + input_id).attr('value', id);
    $('input#' + input_cn).attr('value', val_str);
    $('div.job_type_box').hide();
    $(this).parents('div.search_type').css("border-color", "#4095ef");
    $(this).parents('div.search_type').find('p.flag').css("color", "#4095ef");
})