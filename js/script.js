var filter = {
	region: [],
	country: [],
	section: [],
	city: []
};

function debug(obj) {
	obj.map(function (e, x) {
		$.each(this.attributes, function () {
			// this.attributes is not a plain object, but an array
			// of attribute nodes, which contain both the name and value
			if (this.specified) {
				console.log('--- ', e, this.name, this.value);
			}
		});
	});
}

function translate(str) {
	if (typeof str !== 'undefined') {
		// the variable is defined
		return str.normalize('NFD').toLowerCase().replace(/ /g, "-").replace(/[\u0300-\u036f]/g, "");
	} else {
		return "";
	}
}

function resetType(type) {
	if (type !== undefined)
		filter[type] = [];
	else {
		for (var t in filter) {
			filter[t] = [];
		}
	}
}

function resetMenu() {
	$('.in-option-bar .dropdown input[type="checkbox"]').prop('checked', false);
}

function reset() {
	$('.enterprise').show().find('tr').show().find('li').show();
}

function removeElement(type, value) {
	if (type !== undefined) {
		var index = filter[type].indexOf(value);
		filter[type].splice(index, 1);
	}
}

function setElement(type, value) {
	// Select element
	$('.' + type + '-selection[value="' + value + '"]').prop('checked', true);

	// filter out choices
	switch (type) {
		case 'region':
			$('.country-selector').show();
			$('.city-selector').show();
			$('.country-selector:not(#' + value + ')').hide();
			break;
		case 'country':
			$('.city-selector').show();
			$('.city-selector').not('#' + value).hide();
			break;
	}

	// filter out elements
	filter[type].push(value);
	startFilter();
}

function startFilter() {
	reset();
	filterRegionAndCountries();
	filterCity();
	filterSection();
	$(".enterprise table").filter(function () {
		return $(this).find("tr:visible").length;
	}).find("thead tr").show();
}

function filterRegionAndCountries() {
	var selector = "";

	if (filter['region'].length > 0 && filter['country'].length > 0) {
		for (var i = 0; i < filter['region'].length; i++) {
			for (var j = 0; j < filter['country'].length; j++) {
				selector += "." + filter['region'][i] + "." + filter['country'][j] + ",";
			}
		}
		selector = selector.substring(0, selector.length - 1);
	} else if (filter['region'].length > 0) {
		selector = "." + filter['region'].join(',.');
	} else if (filter['country'].length > 0) {
		selector = "." + filter['country'].join(',.');
	} else {
		// everything visible
		return;
	}
	$('.enterprise').hide().siblings(selector).show();
}

function filterCity() {
	if (filter['city'].length > 0) {
		$('.cityKey').hide();
		for (var i = 0; i < filter['city'].length; i++) {
			var id = '.' + filter['city'][i];
			$(id).show();
		}
		// filter countries that have no city shown
		var enterprise = document.getElementsByClassName('enterprise');
		for (var i = 0; i < enterprise.length; i++) {
			var allRowsHidden = true;
			var rows = enterprise[i].getElementsByClassName('cityKey')
			for (var j = 0; j < rows.length; j++) {
				if (rows[j].style.display !== 'none') {
					allRowsHidden = false;
					break;
				}
			}
			if (allRowsHidden) {
				enterprise[i].style.display = 'none';
			}
		}
	} else {
		$('.cityKey').show();
	}
}

function filterSection() {
	if (filter['section'].length === 0) {
		return;
	}
	var sections = "." + filter['section'].join(',.');

	$('.table:visible tr').not(sections).hide();
	$('.table:visible tr:visible .sections li,.table:visible tr:visible .places li').not(sections).hide();

	$('.enterprise').not(':has(tr:visible)').hide();
}

function sortSection(a, b) {
	if ($(a).data("order").f == $(b).data("order").f) {
		return ($(a).data("order").s < $(b).data("order").s) ? -1 : $(a).data("order").s > $(b).data("order").s ? 1 : 0;
	} else {
		return ($(a).data("order").f > $(b).data("order").f) ? 1 : -1;
	}
}

$(document).ready(function () {

	// Reset button
	$('.showall').click(function (e) {
		// reset option bar
		$('.country-selector').show();
		$('.city-selector').show();
		// reset filter
		e.preventDefault();
		resetType();
		resetMenu();
		reset();
	});

	// Dropdown menu
	$('.in-option-bar .dropdown').change(function (event) {
		var type = event.target.name;
		var value = event.target.value;

		switch (type) {
			case 'region':
				resetType('country');
				resetType('city');
				break;
			case 'country':
				resetType('city');
				break;
		}

		if ($(event.target).is(':checked')) {
			filter[type].push(value);
		} else {
			removeElement(type, value);
		}
		startFilter();
	});

	// Dropdown menu show-all
	$('.in-option-bar .dropdown').click(function (e) {
		if ($(e.target).hasClass("show-all")) {
			e.preventDefault();
			$(e.target).parents(".dropdown").eq(0).find(":checked").click();
			// handles allRegions item
			if ($(e.target).hasClass("regions")) {
				if (!$('.country-selector').is(':visible')) {
					$('.country-selector').show();
				}
			}
			// handles all country items
			if ($(e.target).hasClass("countries")) {
				if (!$('.city-selector').is(':visible')) {
					$('.city-selector').show();
				}
			}
		}
	});

	$('#inSectionsFilter').change(function () {
		var val = $('#map-list #sections option:selected').val();
		if (val === 'all') {
			$('#map-list').show();
			$('#map-list .town').show();
			$('#map-list .country-listing').show();
		} else {
			$('#map-list .town').hide();
			$('#map-list .' + val).show();
		}

		$('#map-list .country-hover').each(function () {
			var continent = $(this).attr('id');
			var hasElement = false;

			if (val !== 'all') {
				$(this).find('.country-listing').each(function () {
					if ($(this).find('.' + val).length > 0) {
						$(this).show();
						if (!hasElement)
							hasElement = true;
					} else {
						$(this).hide();
					}
				});
			} else {
				$(this).find('.country-menu').each(function () {
					if ($(this).find('.country-listing').length > 0) {
						if (!hasElement)
							hasElement = true;
					} else {
						$(this).hide();
					}
				});
			}

			if (hasElement) {
				$(this).addClass('active');
				$('#map-list #world .' + continent).addClass('active');
			} else {
				$('#map-list #world .' + continent).removeClass('active');
				$(this).removeClass('active');
			}
		});
	});

	$('#inRegionsFilter').change(function (e) {
		// Reset all country and city selectors
		$('.country-selection').prop('checked', false);
		$('.city-selection').prop('checked', false);
		// hide all countries
		$('.country-selector').hide();
		// show selected country of selected regions
		$('.region-selection').each(function (i) {
			var id = $(this).val();
			if ($(this).is(':checked')) {
				$('.country-selector#' + id).show().find('.city-selector').show();
			}
		});
	});

	$('#inCountriesFilter').change(function (e) {
		// Reset all city selectors
		$('.city-selection').prop('checked', false);
		$('.city-selector').hide();
		$('.country-selection').each(function (i) {
			var id = $(this).val();
			if ($(this).is(':checked')) {
				$('.city-selector#' + id).show();
			}
		});
	});

	$('#showmap').click(function (e) {
		e.preventDefault();
		resetType();
		resetMenu();
		$('#enterprise-list').hide();
		$('#map-list').show();
		$('#map-list .town').show();
		$('#map-list #sections').val("all");

		$('#map-list .country-hover').each(function () {
			var continent = $(this).attr('id');
			var hasElement = false;

			$(this).find('.country-menu').each(function () {
				if ($(this).find('.country-listing').length > 0) {
					if (!hasElement)
						hasElement = true;
				} else {
					$(this).hide();
				}
			});

			if (hasElement) {
				$(this).addClass('active');
				$('#map-list #world .' + continent).addClass('active');
			} else {
				$('#map-list #world .' + continent).removeClass('active');
				$(this).removeClass('active');
			}
		});
	});

	$('.selection-link').on('mousedown', function (e) {
		e.preventDefault();

		var link = $(this).attr('href').substring(1);

		var type = $(this).attr('data-type');

		$('#map-list').hide();
		$('#enterprise-list').show();

		setElement(type, link);
	});

	$('#map-list .town').show();
	$('#map-list .country-hover').each(function () {
		var continent = $(this).attr('id');
		var footer = continent + '-footer';
		var hasElement = false;

		$(this).find('.country-menu').each(function () {
			if ($(this).find('.country-listing').length > 0) {
				if (!hasElement)
					hasElement = true;
			} else {
				$(this).hide();
			}
		});

		if (hasElement) {
			$(this).addClass('active');
			$('#map-list #world .' + continent).addClass('active');
			$('#map-list-footer #' + footer).addClass('active');
		} else {
			$('#map-list #world .' + continent).removeClass('active');
			$('#map-list-footer #' + footer).removeClass('active');
			$(this).removeClass('active');
		}
	});

	var hoverin = function (e) {

		if ($('.city-menu').not(':visible')) {
			var id = $(this).attr('id');
			$('#world .' + id).addClass('hover');
			$(this).addClass('hover').css('z-index', 4);
			$('#map-list-footer #' + id + '-footer').addClass('hover');
		}
	};

	var hoverout = function (e) {
		if ($('.city-menu').not(':visible')) {
			var id = $(this).attr('id');
			$('#world .' + id).removeClass('hover');
			$(this).removeClass('hover').css('z-index', 3);
			$('#map-list-footer #' + id + '-footer').removeClass('hover');
		}
	};

	$('.country-hover').hover(hoverin, hoverout);

	$('.in-option-bar .dropdown').on('mouseover click', function (event) {
		if ($(this).find('.menu').hasClass('hidden')) {
			$(this).find('.menu').removeClass('hidden');
			event.stopPropagation();
		}
	});

	$('body').click(function () {
		$('.in-option-bar .menu').addClass('hidden');
	});

	$('.in-option-bar .dropdown').mouseleave(function () {
		$('.in-option-bar .menu').addClass('hidden');
	});

});
