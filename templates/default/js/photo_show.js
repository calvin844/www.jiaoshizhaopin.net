// 懒人图库 搜集整理 www.lanrentuku.com
function getStyle2(obj,name)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[name]
	}
	else
	{
		return getComputedStyle(obj,false)[name]
	}
}

function getByClass2(oParent,nClass)
{
	var eLe = oParent.getElementsByTagName('*');
	var aRrent  = [];
	for(var i=0; i<eLe.length; i++)
	{
		if(eLe[i].className == nClass)
		{
			aRrent.push(eLe[i]);
		}
	}
	return aRrent;
}

function startMove2(obj,att,add)
{
	clearInterval(obj.timer)
	obj.timer = setInterval(function(){
	   var cutt = 0 ;
	   if(att=='opacity')
	   {
		   cutt = Math.round(parseFloat(getStyle2(obj,att)));
	   }
	   else
	   {
		   cutt = Math.round(parseInt(getStyle2(obj,att)));
	   }
	   var speed = (add-cutt)/4;
	   speed = speed>0?Math.ceil(speed):Math.floor(speed);
	   if(cutt==add)
	   {
		   clearInterval(obj.timer)
	   }
	   else
	   {
		   if(att=='opacity')
		   {
			   obj.style.opacity = (cutt+speed)/100;
			   obj.style.filter = 'alpha(opacity:'+(cutt+speed)+')';
		   }
		   else
		   {
			   obj.style[att] = cutt+speed+'px';
		   }
	   }
	   
	},30)
}

function photo_show(){
	  var oDiv2 = document.getElementById('photo_show');
	  var oPre2 = getByClass2(oDiv2,'pre')[0];
	  var oNext2 = getByClass2(oDiv2,'next')[0];
	  var oUlBig2 = getByClass2(oDiv2,'oUlplay')[0];
	  var aBigLi2 = oUlBig2.getElementsByTagName('li');
	  var oDiv2Small2 = getByClass2(oDiv2,'smalltitle')[0]
	  var aLiSmall2 = oDiv2Small2.getElementsByTagName('li');
	  
	  function tab()
	  {
	     for(var i=0; i<aLiSmall2.length; i++)
	     {
		    aLiSmall2[i].className = '';
	     }
	     aLiSmall2[now].className = 'thistitle'
	     startMove2(oUlBig2,'left',-(now*aBigLi2[0].offsetWidth))
	  }
	  var now = 0;
	  for(var i=0; i<aLiSmall2.length; i++)
	  {
		  aLiSmall2[i].index = i;
		  aLiSmall2[i].onclick = function()
		  {
			  now = this.index;
			  tab();
		  }
	 }
	  oPre2.onclick = function()
	  {
		  now--
		  if(now ==-1)
		  {
			  now = aBigLi2.length;
		  }
		   tab();
	  }
	   oNext2.onclick = function()
	  {
		   now++
		  if(now ==aBigLi2.length)
		  {
			  now = 0;
		  }
		  tab();
	  }
	  var timer = setInterval(oNext2.onclick,3000) //滚动间隔时间设置
	  oDiv2.onmouseover = function()
	  {
		  clearInterval(timer)
	  }
	   oDiv2.onmouseout = function()
	  {
		  timer = setInterval(oNext2.onclick,3000) //滚动间隔时间设置
	  }
  }
  
  photo_show()