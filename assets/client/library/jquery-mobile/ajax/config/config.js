//var defUChost = 'http://rc-sumbar.com/ipna/';
//var defUChost = 'http://192.168.137.1/myjob/ipna/';
//var defUChost = 'http://192.168.43.244/myjob/ipna/';
var defUChost = 'http://localhost/myjob/ipna/';

var UChost = defUChost;

if(window.localStorage.getItem("rcuserURL")=== null){
	window.localStorage.setItem("rcuserURL", UChost);
}