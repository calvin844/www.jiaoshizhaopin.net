var timer;
var timer2;
//获取指示灯的高度
var original_top = $("#banner_controller").offset().top;
var current = 0;
var index = 0;
var current2 = 0;
var index2 = 0;
var flag = false;
var flag2 = false;
var count = 0;
//指示灯的移动
function banner_box_move(){ 
	if (!flag) {
		return;
	}
	var top = $("#banner_controller").offset().top;
	if (index > current) {
		if (top < original_top + 55*index) {
			$("#banner_controller").css("top", (top - original_top + 5) + "px");
		} else {
			flag = false;
			current = index;
			$("#banner_controller").css("top", current*55 + "px");
		}
	} else {
		if (top > original_top + 55*index) {
			$("#banner_controller").css("top", (top - original_top - 5) + "px");
		} else {
			flag = false;
			current = index;
			$("#banner_controller").css("top", current*55 + "px");
		}
	}
} 
//轮播大图的移动
function banner_move() {
	if (!flag2) {
		return;
	}
	if (index2 > current2) {
		console.log($("#banner").css("margin-top"));
		if (count <= 160*index2) {
			$("#banner").css("margin-top", "-" + count + "px");
			count = count + 4;
		} else {
			flag2 = false;
			current2 = index2;
			$("#banner").css("margin-top", "-" + current2*160 + "px");
		}
		
	} else {
		if (count >= 160*index2) {
			$("#banner").css("margin-top", "-" + count + "px");
			count = count - 4;
		} else {
			flag2 = false;
			current2 = index2;
			$("#banner").css("margin-top", "-" + current2*160 + "px");
		}
		
	}
}
timer = window.setInterval(banner_box_move, 2);
timer2 = window.setInterval(banner_move, 1);
function move(index) {
	if (!flag) {
		this.index = index;
		this.index2 = index;
		flag = true;
		flag2 = true;
	}
}
window.setInterval(tick, 5000);
function tick() {
	if (!flag) {
		if (index >= 2) {
			move(0);
		} else {
			move(index + 1);
		}
	}
}