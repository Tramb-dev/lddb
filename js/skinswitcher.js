// JavaScript Document
function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = 'fsb2_' + name+"="+value + '; domain=lesdisciplesdebaal.com'+expires+"; path=/";
}

function skin(value)
{
	createCookie('style', value, 365);
	location.reload();
}
