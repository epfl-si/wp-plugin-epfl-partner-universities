var filter = {
    region: [],
    country: [],
    section: [],
    city: []
};

function debug(obj) {
    obj.map(function(e,x) {
        $.each(this.attributes, function() {
            // this.attributes is not a plain object, but an array
            // of attribute nodes, which contain both the name and value
            if(this.specified) {
                console.log('--- ', e, this.name, this.value);
            }
        });
    });
}

function translate(str) {
    if (typeof str !== 'undefined') {
        // the variable is defined
        return str.normalize('NFD').toLowerCase().replace(/ /g,"-").replace(/[\u0300-\u036f]/g, "");
    } else {
        return "";
    }
}

function resetType(type) {
    if(type !== undefined)
        filter[type] = [];
    else {
        for(var t in filter) {
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
    if(type !== undefined) {
        console.log("type: "+type);
        console.log("value: "+value);
        var index = filter[type].indexOf(value);
        filter[type].splice(index, 1);
    }
}

function setElement(type, value) {
    // Select element
    $('.'+type+'-selection[value="'+value+'"]').attr('checked', true);

    // filter out choices
    switch(type) {
        case 'region':
            $('.country-selector').not('#'+value).hide();
            break;
        case 'country':
            $('.city-selector').not('#'+value).hide();
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
    $(".enterprise table").filter(function(){
        return $(this).find("tr:visible").length;
    }).find("thead tr").show();
}

function filterRegionAndCountries() {
    var selector = "";

    if(filter['region'].length > 0 && filter['country'].length > 0) {
        for(var i = 0; i < filter['region'].length; i++) {
            for(var j = 0; j < filter['country'].length; j++) {
                selector += "."+filter['region'][i]+"."+filter['country'][j]+",";
            }
        }
        selector = selector.substring(0, selector.length - 1);
    } else if(filter['region'].length > 0) {
        selector = "."+filter['region'].join(',.');
    } else if (filter['country'].length > 0) {
        selector = "."+filter['country'].join(',.');
    } else {
        // everything visible
        return;
    }
    $('.enterprise').hide().siblings(selector).show();
}

function filterCity() {
    if(filter['city'].length > 0) {
        $('.cityKey').hide();
        for(var i = 0; i < filter['city'].length; i++){
            var id = '.' + filter['city'][i];
            $(id).show();
            console.log(id);
        }
        // filter countries that have no city shown
        var enterprise = document.getElementsByClassName('enterprise');
        console.log(enterprise.length);
        for (var i = 0; i < enterprise.length; i++) {
            var allRowsHidden = true;
            var rows = enterprise[i].getElementsByClassName('cityKey')
            for (var j = 0; j < rows.length; j++) {
                console.log(rows[j]);
                if (rows[j].style.display !== 'none') {
                    allRowsHidden = false;
                    break;
                }
            }
            if (allRowsHidden) {
                enterprise[i].style.display = 'none';
            }
        }
    }else{
        $('.cityKey').show();
    }
}

$(document).ready(function() {

    // Reset button
    $('.showall').click(function(e) {
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
    $('.in-option-bar .dropdown').change(function(event) {
        var type = event.target.name;
        var value = event.target.value;

        switch(type) {
            case 'region':
                resetType('country');
                resetType('city');
                break;
            case 'country':
                resetType('city');
                break;
        }

        if($(event.target).is(':checked')) {
            filter[type].push(value);
        } else {
            removeElement(type, value);
        }
        startFilter();
    });

    // Dropdown menu show-all
    $('.in-option-bar .dropdown').click(function(e) {
        if($(e.target).hasClass("show-all")) {
            e.preventDefault();
            $(e.target).parents(".dropdown").eq(0).find(":checked").click();
            // handles allRegions item
            if($(e.target).hasClass("regions")) {
                if(!$('.country-selector').is(':visible')) {
                    $('.country-selector').show();
                }
            }
            // handles all country items
            if($(e.target).hasClass("countries")) {
                if(!$('.city-selector').is(':visible')) {
                    $('.city-selector').show();
                }
            }
        }
    });

    $('#inRegionsFilter').change(function(e) {
        // Reset all country and city selectors
        $('.country-selection').attr('checked', false);
        $('.city-selection').attr('checked', false);
        // hide all countries
        $('.country-selector').hide();
        // show selected country of selected regions
        $('.region-selection').each(function(i) {
            var id = $(this).val();
            if($(this).is(':checked')) {
                $('.country-selector#'+id).show().find('.city-selector').show();
            }
        });
    });

    $('#inCountriesFilter').change(function(e) {
        // Reset all city selectors
        $('.city-selection').attr('checked', false);
        $('.city-selector').hide();
        $('.country-selection').each(function(i) {
            var id = $(this).val();
            if($(this).is(':checked')) {
                $('.city-selector#'+id).show();
            }
        });
    });
});
