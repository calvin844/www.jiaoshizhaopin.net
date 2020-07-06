
//全选反选
$("input[name='selectall']").click(function() {
    alert($(this).is(":checked"));
    if($(this).is(":checked")){
        $("#infolists :checkbox").attr('checked', 'checked')
    }else{
        $("#infolists input[type='checkbox']").removeAttr('checked')
    }
    //$("#formresumelist :checkbox").attr('checked', $(this).attr('checked'))
});
// 申请职位
function apply_jobs(ajaxurl)
{
    $(".deliver").click(function()
    {
        var sltlength = $("#infolists input:checked").length;
        if ($("#infolists .list input:checked").length == 0)
        {
            dialog("系统提示", "text:请选择职位", "300px", "auto");
        }
        else
        {
            var jidArr = new Array();
            $("#infolists .list :checkbox[checked]").each(function(index) {
                jidArr[index] = $(this).val();
            });
            dialog("申请职位", "url:" + ajaxurl + "user/user_apply_jobs.php?id=" + jidArr.join("-") + "&act=app", "500px", "auto", "");
        }
    });
    //单个申请职位
    $(".app_jobs").unbind().click(function() {
        dialog("申请职位", "url:" + ajaxurl + "user/user_apply_jobs.php?id=" + $(this).attr("id") + "&act=app", "500px", "auto", "");
    });
}
// 收藏职位
function favorites(ajaxurl)
{
    $(".collecter").click(function()
    {
        var sltlength = $("#infolists input:checked").length;
        if ($("#infolists .list input:checked").length == 0)
        {
            dialog("系统提示", "text:请选择职位", "300px", "auto");
        }
        else
        {
            var jidArr = new Array();
            $("#infolists .list :checkbox[checked]").each(function(index) {
                jidArr[index] = $(this).val();
            });
            dialog("收藏职位", "url:" + ajaxurl + "user/user_favorites_job.php?id=" + jidArr.join("-") + "&act=add", "500px", "auto", "");
        }
    });
    // 单个收藏职位
    $(".add_favorites").unbind().click(function() {
        dialog("收藏职位", "url:" + ajaxurl + "user/user_favorites_job.php?id=" + $(this).attr("id") + "&act=add", "500px", "auto", "");
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