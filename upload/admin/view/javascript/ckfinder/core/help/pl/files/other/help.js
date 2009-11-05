window.onload = function()
{
	var copyP = document.createElement( 'p' ) ;
	copyP.className = 'copyright' ;
	copyP.innerHTML = '&copy; 2007-2009 <a href="http://cksource.com" target="_blank">CKSource</a> - Frederico Knabben . Wszystkie prawa zastrzeżone.<br /><br />' ;
	document.body.appendChild( document.createElement( 'hr' ) ) ;
	document.body.appendChild( copyP ) ;

	window.top.SetActiveTopic( window.location.pathname ) ;
}
