function setCookie(cname, cvalue, exdays) {
  if(cvalue != ""){
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  } // if
  else{
    alert("Please Enter a name");
  }
}// func setCookie

function getCookie(cname) {
  var i;
  var c;
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(";");
  for(i = 0; i < ca.length; i+=1){
    c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }//while
    if(c.indexOf(name) == 0) {
      debug = c.substring(name.length, c.length);
      console.debug(debug)
      return c.substring(name.length, c.length);
    }//if
  }//for
}// func getCookie#

function checkCookie(cname) {
  var cookieField = getCookie(cname);
  if (cookieField != ""){return 1;}
  else {return 0;}
}// func checkCookie