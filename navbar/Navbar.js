// Den side man er inde på, lyser grønt på nav-baren.

$(document).ready(function() {
	$(".navbar-link[href]").each(function(index) {
		//now only check if it contains, so we dont mess up get forms, but be carefull with overlapping urls.
		if(window.location.href.includes(this.href)) {
			$(this).addClass("active"); // Tilføjer en "active" klasse til element.
		}
	}); 
});