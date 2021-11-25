;(function($) {
	"use strict";
	
	// Aktive Buchstabengruppe in den Filtern öffnen
	var showActiveFilter = $('.supplier-sites--filter').attr("data-supplier-activefilter");
	
	if (showActiveFilter == true)
	{
		$('.supplier-sites--filter .filter-panel').each(function(index)
		{
			if ($(this).find('input').is(':checked'))
			{
				$(this).addClass('is--collapsed');
			}
		});
	}
	
	// Bei Klick auf den Buchstaben in der Navigation gewählte Animation ausführen
	$('#brand_index button').click( function()
	{
		// Gewähltes Zeichen + # holen, Beispiel #A
		var char = $(this).attr("data-supplier-href");
		if (!char)
		{
			return;
		}
		
		var supplierAnimate = $('#brand_index').attr("data-supplier-animate");
		var supplierDuration = parseInt($('#brand_index').attr("data-supplier-duration"));
		var supplierStop = parseInt($('#brand_index').attr("data-supplier-stop"));
		
		if (supplierAnimate == 'click')
		{
			var supplierGroup = $('.supplier-group.hasFocus').length;
		
			if (char == 'all') // Alle Hersteller anzeigen
			{
				// Wenn alle Herstellergruppen aktiviert, dann abbrechen
				if (supplierGroup > 1)
				{
					return false;
				}
				
				$('.supplier-group').addClass('hasFocus');
				$('.supplier-group').fadeIn(450);
			}
			else if (supplierGroup == 1 && $(char).css('display') == 'block')
			{
				// Wenn Herstellergruppe schon gewählt, dann abbrechen
				return false;
			}
			else
			{
				// Gewählten Hersteller anzeigen
				$('.supplier-group').removeClass('hasFocus');
				$('.supplier-group').hide();
				
				$(char).addClass('hasFocus');
				$(char).fadeIn(450);
			}
		}
		else
		{
			$('html, body').stop(false, false).animate({ scrollTop: ($(char).offset().top - supplierStop)}, supplierDuration);
		}
    });

})(jQuery);