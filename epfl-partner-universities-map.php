<?php
/*
Plugin Name:  EPFL Partner Universities Map
Description:  Provides a shortcode to display all partner universities
Version:      1.0.0
Author:       Rosa Maggi (Renato Kempter (renato.kempter@gmail.com) 2013 - Tim Brigginshaw EPFL (tim.brigginshaw@epfl.ch) 2018)
License: Copyright (c) 2021 Ecole Polytechnique Federale de Lausanne, Switzerland
*/

function epfl_partner_universities_map_process_shortcode($atts)
{
    ob_start();
    $atts = shortcode_atts([
        'language' => ''
    ], $atts);
    if ($atts['language'] == 'FR') {
        $language = 'fr';
        $regionFilterText = "Région";
        $countryFilterText = "Pays";
        $townFilterText = "Ville";
        $allRegionsText = 'Toutes les regions';
        $allCountriesText = 'Tous les pays';
        $allCitiesText = 'Toutes les villes';
        $showAll = 'Tout afficher';
        $cityLabel = 'Ville';
        $universityLabel = 'Université';
        $selectMapText = 'Sélectionnez votre section';
        $selectFilterText = 'Toutes les sections';
        $cityText = 'Ville';
        $uniText = 'Université';
        $sectionText = 'Section';
        $remText = 'Remarques';
    } else {
        $language = 'en';
        $regionFilterText = "Region";
        $countryFilterText = "Country";
        $townFilterText = "City";
        $allRegionsText = 'All regions';
        $allCountriesText = 'All countries';
        $allCitiesText = 'All cities';
        $showAll = 'Show all';
        $cityLabel = 'City';
        $universityLabel = 'University';
        $selectMapText = 'Please choose a section';
        $selectFilterText = 'All sections';
        $cityText = 'City';
        $uniText = 'University';
        $sectionText = 'Sections';
        $remText = 'Remarks';
    }

    wp_enqueue_style( 'epfl_partner_universities_style', plugin_dir_url(__FILE__).'css/styles.css', [], '2.1');
    ?><script><?php require_once("js/script.js");?></script><?php
    require_once('partner-universities-utils.php');
    include('page_map.php');

    $utils = new Utils();
    $mobiliteUrl = $utils->hostname . "services/mobilite/search";
    $partners = $utils->call_service($mobiliteUrl);
    if ($partners['httpCode'] === 200) {
        getRegions($partners['response']);
        getPartners($partners['response'], $language, $cityLabel, $universityLabel, $cityText, $uniText, $sectionText, $remText);
        initSectionsFilter($utils->hostname, $language, $selectFilterText);
        initPlacesFilter($utils->hostname, $language, $allRegionsText, $regionFilterText, $countryFilterText, $allCountriesText, $townFilterText, $allCitiesText);
    }else{
        $utils->show_error_message($mobiliteUrl, $partners['httpCode']);
    }
    return ob_get_clean();
}

function initPlacesFilter($hostname, $language, $allRegionsText, $regionFilterText, $countryFilterText, $allCountriesText, $townFilterText, $allCitiesText){
    $placesUrl = $hostname . "services/mobilite/places";
    $places = call_service($placesUrl);
    if ($places['httpCode'] === 200) {
        $placesJson = $places['response'];
        ?>
        <script>
            var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
            var allRegionsText = <?= json_encode($allRegionsText, JSON_UNESCAPED_UNICODE); ?>;
            var regionFilterText = <?= json_encode($regionFilterText, JSON_UNESCAPED_UNICODE); ?>;
            var countryFilterText = <?= json_encode($countryFilterText, JSON_UNESCAPED_UNICODE); ?>;
            var allCountriesText = <?= json_encode($allCountriesText, JSON_UNESCAPED_UNICODE); ?>;
            var townFilterText = <?= json_encode($townFilterText, JSON_UNESCAPED_UNICODE); ?>;
            var allCitiesText = <?= json_encode($allCitiesText, JSON_UNESCAPED_UNICODE); ?>;

            var placesJson = <?php echo $placesJson; ?>;

            /* Create the region menu */
            var rel = $('#regionsFilter');
            rel.empty();
            var rb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(regionFilterText);
            var rmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
            rmenu.append($("<li></li>").attr("class", "dropdown-item")
                .append($("<label></label>")
                    .append($("<a></a>").attr("href", "#").attr("class", "show-all regions").text(allRegionsText))));

            placesJson.forEach(function(geo){
                var name = (lang == 'fr') ? geo.region.name.fr : geo.region.name.en;
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
            rel.append(rb);
            rel.append(rmenu);


            /* Create the country menu */
            var cel = $('#countriesFilter')
            cel.empty();
            var cb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(countryFilterText);
            var cmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
            cmenu.append($("<li></li>").attr("class", "dropdown-item")
                .append($("<label></label>")
                    .append($("<a></a>").attr("href", "#").attr("class", "show-all countries").text(allCountriesText))));

            placesJson.forEach(function(geo){
                var regionName = (lang == 'fr') ? geo.region.name.fr : geo.region.name.en;
                var cdiv = $('<div />').attr("class", "country-selector").attr("id", translate(regionName));
                cdiv.append($('<li></li>').attr("class", "dropdown-item region-group")
                    .append($("<label></label>").text(regionName)));
                geo.region.countries.forEach(function(a){
                    var name = (lang == 'fr') ? a.country.name.fr : a.country.name.en;
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
            cel.append(cb);
            cel.append(cmenu);


            // /* Create the city menu */
            var tel = $('#citiesFilter')
            tel.empty();
            var tb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(townFilterText);
            var tmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
            tmenu.append($("<li></li>").attr("class", "dropdown-item")
                .append($("<label></label>")
                    .append($("<a></a>").attr("href", "#").attr("class", "show-all cities").text(allCitiesText))));

            placesJson.forEach(function(geo){
                var regionName = (lang == 'fr') ? geo.region.name.fr : geo.region.name.en;
                var rdiv = $('<div />').attr("class", "country-selector").attr("id", translate(regionName));
                rdiv.append($('<li></li>').attr("class", "dropdown-item region-group")
                    .append($("<label></label>").text(regionName)));
                geo.region.countries.forEach(function(a){
                    var countryName = (lang == 'fr') ? a.country.name.fr : a.country.name.en;
                    var cdiv = $('<div />').attr("class", "city-selector").attr("id", translate(countryName));
                    cdiv.append($('<li></li>').attr("class", "dropdown-item country-group")
                        .append($("<label></label>").text(countryName)));
                    a.country.towns.forEach(function(town) {
                        var name = (lang == 'fr') ? town.fr : town.en;
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
            tel.append(tb);
            tel.append(tmenu);

        </script>
        <?php
    }else{
        show_error_message($placesUrl, $places['httpCode']);
    }
}

function getPartners($jdata, $language, $cityLabel, $universityLabel, $cityText, $uniText, $sectionText, $remText){
    ?>
    <script>
        var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
        var cityLabel = <?= json_encode($cityLabel, JSON_UNESCAPED_UNICODE); ?>;
        var universityLabel = <?= json_encode($universityLabel, JSON_UNESCAPED_UNICODE); ?>;
        var cityText = <?= json_encode($cityText, JSON_UNESCAPED_UNICODE); ?>;
        var uniText = <?= json_encode($uniText, JSON_UNESCAPED_UNICODE); ?>;
        var sectionText = <?= json_encode($sectionText, JSON_UNESCAPED_UNICODE); ?>;
        var remText = <?= json_encode($remText, JSON_UNESCAPED_UNICODE); ?>;

        partnersData = <?php echo $jdata; ?>;
        function groupBy(xs, f) {
            return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
        }
        countryKey = (a) => translate((lang == 'fr') ? a.country.name.fr : a.country.name.en);
        const countryMap = groupBy(jdata, countryKey);

        Object.keys(countryMap).sort().forEach((ckey) => {
            const partners = countryMap[ckey];
            const head = partners[0];

            var rel = $("<div></div>").attr("class", "enterprise" + ' ' + translate(head.region.name.fr) + ' ' + translate(head.country.name.fr)).attr("style", "background-color: White");
            var row1 = $("<div></div>").attr("class", "row justify-content-between country-header");
            var country = (lang == 'fr') ? partners[0].country.name.fr : partners[0].country.name.en;
            row1.append($("<h4></h4>").attr("class", "col-4").text(country));

            var contact = $("<div></div>").attr("class", "col-4 float-right contact");
            var a =$("<a></a>").attr("href", "mailto:" + head.contacts[0].email).attr("class", "button email").attr("title", "E-mail de la personne de contact à l'EPFL");
            a.append($("<button></button>").attr("class", "icon-white").text(" "));
            a.append($("<span></span>").attr("class", "label text-right").text(head.contacts[0].name));
            contact.append(a);
            row1.append(contact);
            rel.append(row1);

            var row2 = $("<div></div>").attr("class", "row");
            var table = $("<table></table>").attr("class", "table");

            var thead = $("<thead></thead>");
            var theader = $("<tr></tr>").attr("class", "first-line");
            theader.append($("<th></th>").attr("style", "width: 15%").text(cityText));
            theader.append($("<th></th>").attr("style", "width: 35%").text(uniText));
            theader.append($("<th></th>").attr("style", "width: 25%").text(sectionText));
            theader.append($("<th></th>").attr("style", "width: 3%").append($("<span></span>").attr("class", "icon-people").attr("title", "Nombre de places total disponibles")));
            theader.append($("<th></th>").attr("style", "width: 18%").text(remText));
            theader.append($("<th></th>").attr("style", "width: 2%").append($("<span></span>").attr("class", "icon-information").attr("title", "Informations sur le site de l'université partenaire")));
            theader.append($("<th></th>").attr("style", "width: 2%").append($("<span></span>").attr("class", "icon-pdf").attr("title", "Fiche de l'université partenaire (PDF)")));
            thead.append(theader);
            table.append(thead);

            var tbody = $("<tbody></tbody>");
            partners.map((partner) => {
                var rowClasses = translate(partner.town.fr);

                partner.accords.map((accord) => {
                    accord.sections.map((as) => {
                        rowClasses = rowClasses + ' ' + translate(as.section.code.fr);
                    });
                });

                var row = $("<tr></tr>").attr("class", rowClasses);

                var partnerTown = (lang == 'fr') ? partner.town.fr : partner.town.en || partner.town.fr;
                var partnerSchoolName = (lang == 'fr') ? partner.school.name.fr : partner.school.name.en || partner.school.name.fr;

                row.append($("<td></td>").attr("class", "align-baseline city").text(partnerTown));
                var university = $("<td></td>").attr("class", "align-baseline name");
                university.append($("<a></<a>").attr("href", partner.school.url).attr("target", "_blank").attr("class","link-pretty")
                    .append($("<span></span>").text(partnerSchoolName)));
                if( partner.school.parent.name ) {
                    var partnerSchoolParentName = (lang == 'fr') ? partner.school.parent.name.fr : partner.school.parent.name.en || partner.school.parent.name.fr;
                    university.append($("<a></<a>").attr("href", partner.school.parent.url).attr("target", "_blank").attr("class","link-pretty")
                        .append($("<span></span>").text(' ('+partnerSchoolParentName+')')));
                }
                row.append(university);

                var codeList = $("<ul></ul>");
                var placeList = $("<ul></ul>");

                partner.accords.map((accord) => {
                    if(accord.isCurrent == true) {
                        var codeGroup = $('<li></li>').attr("class", " ").data("order", { f: "", s: "" });
                        var totalPlaces = $('<li></li>').attr("class", " ").data("order", { f: "", s: "" });

                        accord.sections.map((as) => {
                            var code = translate(as.section.code.fr);
                            var codeElem = $('<span></span>').attr("class", code).text(as.section.code.fr);

                            if(as.placesShared == true) {
                                codeGroup.append(codeElem);
                                codeGroup.attr("class", codeGroup.attr("class") + ' ' + code).data("order", { f: codeGroup.data("order").f + as.faculty+code, s: codeGroup.data("order").s });
                                totalPlaces.attr("class", codeGroup.attr("class") + ' ' + code).data("order", { f: codeGroup.data("order").f + as.faculty+code, s: codeGroup.data("order").s }).text(as.placesOut);
                            } else {
                                codeList.append($("<li></li>").attr("class", code).data("order", { f: as.faculty, s: code }).append(codeElem));
                                placeList.append($("<li></li>").attr("class", code).data("order", { f: as.faculty, s: code }).text(as.placesOut));
                            };
                        });
                        if (codeGroup.children().length > 0) {
                            codeList.prepend(codeGroup);
                            placeList.prepend(totalPlaces);
                        }
                    }
                });
                codeList.children().sort(sortSection).appendTo(codeList);
                placeList.children().sort(sortSection).appendTo(placeList);

                row.append($("<td></td>").attr("class", "align-baseline sections").append(codeList));
                row.append($("<td></td>").attr("class", "align-baseline places").append(placeList));
                row.append($("<td></td>").attr("class", "align-baseline comment").text(partner.comments.join(', ')));

                // Exchange
                var exchanges = $("<td></td>").attr("class", "align-baseline exchange")
                partner.exchangeWebSites.map((site) => {
                    exchanges.append($("<a></a>").attr("href", site).attr("class", "information").attr("title", "Informations sur le site de l'université partenaire").attr("target", "_blank")
                        .append($("<button></button>").attr("class", "icon-white").text(" ")));
                });
                row.append(exchanges);

                // Datasheet
                if (partner.datasheet !== null) {
                    row.append($("<td></td>").attr("class", "align-baseline documents")
                        .append($("<a></a>").attr("href", hostname+partner.datasheet.href).attr("class", "pdf").attr("title", "Fiche de l'université partenaire (PDF)").attr("target", "_blank")
                            .append($("<button></button>").attr("class", "icon-white").text(" "))));
                } else {
                    row.append($("<td></td>").attr("class", "align-baseline documents"));
                }
                tbody.append(row);
            });
            table.append(tbody);
            row2.append($("<div></div>").attr("class", "col-12").append(table));
            rel.append(row2);
            el.append(rel);
        });
    </script>
    <?php
}

function getRegions($jdata){
    ?>
        <script>
            var jdata = <?php echo $jdata; ?>;
            var el = $('#map-list').find('#map'))
            var rList = jdata.map(function(d) { return d.region.name.fr });
            var uniqueArray = [...new Set(rList)];

            uniqueArray.map((region)=> {
                var rel = $("<div></div>").attr("id", translate(region)).attr("tabindex", "0").attr("class", "selection-link country-menu country-hover").attr("href", '#'+translate(region)).attr("data-type", "region");

                /* The menu stores the number of countries - used to activate the map colours (but no longer used as a menu) */
                var cm = $("<div></div>").attr("class", "country-menu");
                cm.append($("<span></span>").attr("class", "country-listing").attr("data-type", "country"));
                rel.append(cm);

                el.append(rel);
            });
        </script>
    <?php
}

function initSectionsFilter($hostname, $language, $selectFilterText){
    $sectionsUrl = $hostname . "services/mobilite/sections";
    $sections = call_service($sectionsUrl);
    if ($sections['httpCode'] === 200) {
        $sectionsJson = $sections['response'];
        ?>
        <script>
            var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
            var selectFilterText = <?= json_encode($selectFilterText, JSON_UNESCAPED_UNICODE); ?>;
            var sectionsJson = <?php echo $sectionsJson; ?>;
            var sel = $("#sectionsFilter")

            sel.empty(); // remove old options

            var sb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text("Section");
            var smenu = $("<ul></ul>").attr("class", "dropdown-menu");

            smenu.append($("<li></li>").attr("class", "dropdown-item")
                .append($("<label></label>")
                    .append($("<a></a>").attr("href", "#").attr("class", "show-all").text(selectFilterText))));

            sectionsJson.forEach(function(el){
                var id = el.name.fr;
                var name = (lang == 'fr') ? el.name.fr : el.name.en;
                var code = el.code.fr;
                var li = $("<li></li>").attr("class", "dropdown-item");
                var label = $("<label></label>").attr("for", translate(id)+'_id').text(name);
                var input = $("<input></input>")
                    .attr("id", translate(id)+'_id')
                    .attr("type", "checkbox")
                    .attr("class", "section-selection")
                    .attr("name","section")
                    .attr("value", translate(code));

                li.append(input);
                li.append(label);
                smenu.append(li);
            });
            sel.append(sb);
            sel.append(smenu);
        </script>
        <?php
    }else{
        show_error_message($sectionsUrl, $sections['httpCode']);
    }
}

add_action( 'init', function() {
    add_shortcode('epfl_partner_universities_map', 'epfl_partner_universities_map_process_shortcode');
});