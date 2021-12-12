// Den side man er inde på, lyser grønt på nav-baren.

$(document).ready(function() {
	$(".navbar-link[href]").each(function(index) {

		if(this.href == window.location.href) {
			$(this).addClass("active"); // Tilføjer en "active" klasse til element.
		}
	}); 
});