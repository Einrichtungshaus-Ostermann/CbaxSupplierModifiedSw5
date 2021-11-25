;(function($) {
	"use strict";
	
	$('#brand_index button').click( function()
	{
		var supplierDuration = parseInt($('#brand_index').attr("data-supplier-duration"));
		var supplierStop = parseInt($('#brand_index').attr("data-supplier-stop"));
		
        var url = $(this).attr("data-supplier-href");
		if (url)
		{
			$('html, body').stop(false, false).animate({ scrollTop: ($(url).offset().top - supplierStop)}, supplierDuration);
		}
    });
	
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

})(jQuery);