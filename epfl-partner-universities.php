<?php
/*
Plugin Name:  EPFL Partner Universities
Description:  Provides a shortcode to display all partner universities
Version:      1.0.0
Author:       Rosa Maggi
License: Copyright (c) 2021 Ecole Polytechnique Federale de Lausanne, Switzerland
*/

function epfl_partner_universities_process_shortcode()
{
    ob_start();
    wp_enqueue_style( 'epfl_partner_universities_style', plugin_dir_url(__FILE__).'css/styles.css', [], '2.1');
    ?><script><?php require_once("js/script.js");?></script><?php
    include('page_list.php');

    $hostname = "https://isa.epfl.ch/";
    $partnersUrl = $hostname . "services/mobilite/partners";

    $partners = call_service($partnersUrl);
    if ($partners['httpCode'] === 200) {
        getPartners($partners['response']);
        initPlacesFilter($hostname);
    }else{
        show_error_message($partnersUrl, $partners['httpCode']);
    }
    return ob_get_clean();
}

function call_service($url): array
{
    $result = array();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result['response'] = curl_exec($curl);
    $result['httpCode']  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $result;
}

function show_error_message($url,$error){
    $message = $url . ' - ' . $error;
    echo "<script type='text/javascript'>alert('$message');</script>";
}

function initPlacesFilter($hostname){
    $placesUrl = $hostname . "services/mobilite/places";
    $places = call_service($placesUrl);
    if ($places['httpCode'] === 200) {
        $placesJson = $places['response'];

       ?>
        <script>
            placesJson = <?php echo $placesJson; ?>;

            /* Create the region menu */
            var rmenu = $('#inRegionsFilter');
            placesJson.forEach(function(geo){
                var name = (lang() == 'fr') ? geo.region.name.fr : geo.region.name.en;
                var li = $("<li></li>").attr("class", "dropdown-item");
                var label = $("<label></label>").attr("for", translate(name)+'_id').text(name);
                var input =$("<input></input>")
                    .attr("id", translate(name)+'_id')
                    .attr("type", "checkbox")
                    .attr("class", "region-selection")
                    .attr("name","region")
                    .attr("value", translate(name));
                li.append(input);
                li.append(label);
                rmenu.append(li);
            });

            /* Create the country menu */
            var cmenu = $('#inCountriesFilter')
            placesJson.forEach(function(geo){
                var regionName = (lang() == 'fr') ? geo.region.name.fr : geo.region.name.en;
                var cdiv = $('<div />').attr("class", "country-selector").attr("id", translate(regionName));
                cdiv.append($('<li></li>').attr("class", "dropdown-item region-group")
                    .append($("<label></label>").text(regionName)));
                geo.region.countries.forEach(function(a){
                    var name = (lang() == 'fr') ? a.country.name.fr : a.country.name.en;
                    var li = $("<li></li>").attr("class", "dropdown-item");
                    var label = $("<label></label>").attr("for", translate(name)).text(name);
                    var input = $("<input></input>")
                        .attr("id", translate(name))
                        .attr("type", "checkbox")
                        .attr("class", "country-selection")
                        .attr("name","country")
                        .attr("value", translate(name));
                    li.append(input);
                    li.append(label);
                    cmenu.append(cdiv.append(li));
                });
            });

            // /* Create the city menu */
            var tmenu = $('#inCitiesFilter')
            placesJson.forEach(function(geo){
                var regionName = (lang() == 'fr') ? geo.region.name.fr : geo.region.name.en;
                var rdiv = $('<div />').attr("class", "country-selector").attr("id", translate(regionName));
                rdiv.append($('<li></li>').attr("class", "dropdown-item region-group")
                    .append($("<label></label>").text(regionName)));
                geo.region.countries.forEach(function(a){
                    var countryName = (lang() == 'fr') ? a.country.name.fr : a.country.name.en;
                    var cdiv = $('<div />').attr("class", "city-selector").attr("id", translate(countryName));
                    cdiv.append($('<li></li>').attr("class", "dropdown-item country-group")
                        .append($("<label></label>").text(countryName)));
                    a.country.towns.forEach(function(town) {
                        var name = (lang() == 'fr') ? town.fr : town.en;
                        var li = $("<li></li>").attr("class", "dropdown-item");
                        var label = $("<label></label>").attr("for", translate(name)+'_id').text(name);
                        var input = $("<input></input>")
                            .attr("type", "checkbox")
                            .attr("class", "city-selection")
                            .attr("name","city")
                            .attr("value", translate(name));
                        li.append(input);
                        li.append(label);
                        cdiv.append(li);
                    });
                    rdiv.append(cdiv);
                });
                tmenu.append(rdiv);
            });

        </script>
        <?php
    }else{
        show_error_message($placesUrl, $places['httpCode']);
    }
}

function getPartners($jdata){
    ?>
        <script>
            partnersData = <?php echo $jdata; ?>;
            function groupBy(xs, f) {
                return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
            }
            regionKey = (a) => (lang() == 'fr') ? translate(a.region.name.fr) : translate(a.region.name.en);
            const regionMap = groupBy(partnersData, regionKey);

            Object.keys(regionMap).forEach((rkey) => {
                countryKey = (a) => (lang() == 'fr') ? translate(a.country.name.fr) : translate(a.country.name.en);
                const countryMap = groupBy(regionMap[rkey], countryKey);
                Object.keys(countryMap).forEach((ckey) => {
                    const partners = countryMap[ckey];

                    var rel = $("<div></div>").attr("class", "enterprise" + ' ' + rkey + ' ' + ckey).attr("style", "background-color: White");
                    var row1 = $("<div></div>").attr("class", "row justify-content-between country-header");
                    var country = (lang() == 'fr') ? partners[0].country.name.fr : partners[0].country.name.en;
                    row1.append($("<h4></h4>").attr("class", "col-4").text(country));
                    rel.append(row1);

                    var row2 = $("<div></div>").attr("class", "row");
                    var table = $("<table></table>").attr("class", "table");

                    var thead = $("<thead></thead>");
                    var theader = $("<tr></tr>").attr("class", "first-line");
                    theader.append($("<th></th>").attr("style", "width: 15%").text("City"));
                    theader.append($("<th></th>").attr("style", "width: 35%").text("University"));
                    thead.append(theader);
                    table.append(thead);

                    var tbody = $("<tbody></tbody>");
                    partners.map((partner) => {
                        var rowClasses = '';
                        var row = $("<tr></tr>").attr("class", rowClasses);
                        var town = (lang() == 'fr') ? partner.town.fr : partner.town.en;
                        row.append($("<td></td>").attr("class", "align-baseline city").text(town));
                        var university = $("<td></td>").attr("class", "align-baseline name");
                        university.append($("<a></<a>").attr("href", partner.school.url).attr("target", "_blank").attr("class","link-pretty")
                            .append($("<span></span>").text(partner.school.name.fr)));
                        if( partner.school.parent.name ) {
                            university.append($("<a></<a>").attr("href", partner.school.parent.url).attr("target", "_blank").attr("class","link-pretty")
                                .append($("<span></span>").text(' ('+partner.school.parent.name.fr+')')));
                        }
                        row.append(university);
                        tbody.append(row);
                    });
                    table.append(tbody);
                    row2.append($("<div></div>").attr("class", "col-12").append(table));
                    rel.append(row2);
                    $('#in-table-content').append(rel);
                });
            });
        </script>
    <?php
}

add_action( 'init', function() {
    add_shortcode('epfl_partner_universities', 'epfl_partner_universities_process_shortcode');
});