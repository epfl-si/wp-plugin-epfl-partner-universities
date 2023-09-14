<?php
/*
Plugin Name:  EPFL Partner Universities
Description:  Provides a shortcode to display all partner universities
Version:      1.0.0
Author:       Rosa Maggi (Renato Kempter (renato.kempter@gmail.com) 2013 - Tim Brigginshaw EPFL (tim.brigginshaw@epfl.ch) 2018)
License: Copyright (c) 2021 Ecole Polytechnique Federale de Lausanne, Switzerland
*/

$labels = [];

function epfl_partner_universities_process_shortcode($atts)
{
    ob_start();
    $atts = shortcode_atts([
        'language' => '',
        'exchange' => ''
    ], $atts);

    /*include all required classes*/
    require_once('partner-universities-utils.php');
    require_once('partner-universities-traductions.php');
    ?>
    <script><?php require_once("js/script.js"); ?></script><?php
    wp_enqueue_style('epfl_partner_universities_style', plugin_dir_url(__FILE__) . 'css/styles.css', [], '2.1');

    /*initialization of utility classes*/
    $utils = new PartnerUniversitiesUtils();
    $translation = new PartnerUniversitiesTraduction();

    /*prepare the page*/
    $labels = $translation->translateLabels($atts['language']);
    $utils->map = $atts['exchange'];
    if ($atts['exchange'] == 'IN') {
        $callUrl = $utils->hostname . "services/mobilite/partners";
    } else {
        $callUrl = $utils->hostname . "services/mobilite/search";
    }

    /*web services calls*/
    $partners = $utils->call_service($callUrl);
    if ($partners['httpCode'] === 200) {
        if ($atts['exchange'] == 'IN') {
            include('page_list.php');
            getPartners($partners['response'], $labels);
        } else {
            include('page_map.php');
            getRegions($partners['response'], $labels["language"]);
            getPartnersMap($utils->hostname, $partners['response'], $labels);
            initSectionsFilter($utils, $labels);
        }
        $utils->initPlacesFilter($labels);
    } else {
        $utils->show_error_message($callUrl, $partners['httpCode'], $labels);
    }
    return ob_get_clean();
}

/**
 * @param $jdata
 * @param $labels
 * @description web service call for partners list and list definition
 * @return void
 */
function getPartners($jdata, $labels)
{
    ?>
    <script>
        var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
        var cityLabel = <?= json_encode($labels['cityLabel'], JSON_UNESCAPED_UNICODE); ?>;
        var universityLabel = <?= json_encode($labels['universityLabel'], JSON_UNESCAPED_UNICODE); ?>;

        partnersData = <?php echo $jdata; ?>;

        function groupBy(xs, f) {
            return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
        }

        regionKey = (a) => (lang == 'fr') ? translate(a.region.name.fr) : translate(a.region.name.en);
        const regionMap = groupBy(partnersData, regionKey);

        Object.keys(regionMap).forEach((rkey) => {
            countryKey = (a) => (lang == 'fr') ? translate(a.country.name.fr) : translate(a.country.name.en);
            const countryMap = groupBy(regionMap[rkey], countryKey);
            Object.keys(countryMap).forEach((ckey) => {
                const partners = countryMap[ckey];

                var rel = $("<div></div>").attr("class", "enterprise" + ' ' + rkey + ' ' + ckey).attr("style", "background-color: White");
                var row1 = $("<div></div>").attr("class", "row justify-content-between country-header");
                var country = (lang == 'fr') ? partners[0].country.name.fr : partners[0].country.name.en;
                row1.append($("<h4></h4>").attr("class", "col-4").text(country));
                rel.append(row1);

                var row2 = $("<div></div>").attr("class", "row");
                var table = $("<table></table>").attr("class", "table");

                var thead = $("<thead></thead>");
                var theader = $("<tr></tr>").attr("class", "first-line");
                theader.append($("<th></th>").attr("style", "width: 15%").text(cityLabel));
                theader.append($("<th></th>").attr("style", "width: 35%").text(universityLabel));
                thead.append(theader);
                table.append(thead);

                var tbody = $("<tbody></tbody>");
                partners.map((partner) => {
                    var rowClasses = '';
                    var townKey = (lang == 'fr') ? translate(partner.town.fr) : translate(partner.town.en);
                    var town = (lang == 'fr') ? partner.town.fr : partner.town.en;
                    var row = $("<tr></tr>").attr("class", rowClasses + " cityKey" + ' ' + townKey);
                    row.append($("<td></td>").attr("class", "align-baseline city").text(town));
                    var university = $("<td></td>").attr("class", "align-baseline name");
                    university.append($("<a></<a>").attr("href", partner.school.url).attr("target", "_blank").attr("class", "link-pretty")
                        .append($("<span></span>").text(partner.school.name.fr)));
                    if (partner.school.parent.name) {
                        university.append($("<a></<a>").attr("href", partner.school.parent.url).attr("target", "_blank").attr("class", "link-pretty")
                            .append($("<span></span>").text(' (' + partner.school.parent.name.fr + ')')));
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

/**
 * @param $jdata
 * @param $language
 * @param $cityLabel
 * @param $universityLabel
 * @description web service call for partners list and list définition
 * @return void
 */
function getPartnersMap($hostname, $jdata, $labels): void
{
    ?>
    <script>
        var hostname = <?= json_encode($hostname, JSON_UNESCAPED_UNICODE); ?>;
        var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
        var cityLabel = <?= json_encode($labels['cityLabel'], JSON_UNESCAPED_UNICODE); ?>;
        var universityLabel = <?= json_encode($labels['universityLabel'], JSON_UNESCAPED_UNICODE); ?>;
        var sectionText = <?= json_encode($labels['sectionText'], JSON_UNESCAPED_UNICODE); ?>;
        var remText = <?= json_encode($labels['remText'], JSON_UNESCAPED_UNICODE); ?>;
        var emailContact = <?= json_encode($labels['emailContact'], JSON_UNESCAPED_UNICODE); ?>;
        var placeDisponibles = <?= json_encode($labels['placeDisponibles'], JSON_UNESCAPED_UNICODE); ?>;
        var universityInformation = <?= json_encode($labels['universityInformation'], JSON_UNESCAPED_UNICODE); ?>;
        var fichePDF = <?= json_encode($labels['fichePDF'], JSON_UNESCAPED_UNICODE); ?>;

        partnersData = <?php echo $jdata; ?>;

        function groupBy(xs, f) {
            return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
        }

        countryKey = (a) => translate((lang == 'fr') ? a.country.name.fr : a.country.name.en);
        const countryMap = groupBy(partnersData, countryKey);

        var el = $('#table-content');
        Object.keys(countryMap).sort().forEach((ckey) => {
            const partners = countryMap[ckey];
            const head = partners[0];

            var rKey = (lang == 'fr') ? translate(head.region.name.fr) : translate(head.region.name.en);
            var cKey = (lang == 'fr') ? translate(head.country.name.fr) : translate(head.country.name.en);


            var rel = $("<div></div>").attr("class", "enterprise" + ' ' + rKey + ' ' + cKey).attr("style", "background-color: White");
            var row1 = $("<div></div>").attr("class", "row justify-content-between country-header");
            var country = (lang == 'fr') ? partners[0].country.name.fr : partners[0].country.name.en;
            row1.append($("<h4></h4>").attr("class", "col-4").text(country));

            // var contact = $("<div></div>").attr("class", "col-4 float-right contact");
            // var a =$("<a></a>").attr("href", "mailto:" + head.contacts[0].email).attr("class", "button email").attr("title", emailContact);
            // a.append($("<button></button>").attr("class", "icon-white").text(" "));
            // a.append($("<span></span>").attr("class", "label text-right").text(head.contacts[0].name));
            // contact.append(a);
            //row1.append(contact);
            rel.append(row1);

            var row2 = $("<div></div>").attr("class", "row");
            var table = $("<table></table>").attr("class", "table");

            var thead = $("<thead></thead>");
            var theader = $("<tr></tr>").attr("class", "first-line");
            theader.append($("<th></th>").attr("style", "width: 15%").text(cityLabel));
            theader.append($("<th></th>").attr("style", "width: 35%").text(universityLabel));
            theader.append($("<th></th>").attr("style", "width: 25%").text(sectionText));
            theader.append($("<th></th>").attr("style", "width: 3%").append($("<span></span>").attr("class", "icon-people").attr("title", placeDisponibles)));
            theader.append($("<th></th>").attr("style", "width: 18%").text(remText));
            theader.append($("<th></th>").attr("style", "width: 2%").append($("<span></span>").attr("class", "icon-information").attr("title", universityInformation)));
            theader.append($("<th></th>").attr("style", "width: 2%").append($("<span></span>").attr("class", "icon-pdf").attr("title", fichePDF)));
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

                var townKey = (lang == 'fr') ? translate(partner.town.fr) : translate(partner.town.en);
                var row = $("<tr></tr>").attr("class", rowClasses + " cityKey" + ' ' + townKey);

                var partnerTown = (lang == 'fr') ? partner.town.fr : partner.town.en || partner.town.fr;
                var partnerSchoolName = (lang == 'fr') ? partner.school.name.fr : partner.school.name.en || partner.school.name.fr;

                row.append($("<td></td>").attr("class", "align-baseline city").text(partnerTown));
                var university = $("<td></td>").attr("class", "align-baseline name");
                university.append($("<a></<a>").attr("href", partner.school.url).attr("target", "_blank").attr("class", "link-pretty")
                    .append($("<span></span>").text(partnerSchoolName)));
                if (partner.school.parent.name) {
                    var partnerSchoolParentName = (lang == 'fr') ? partner.school.parent.name.fr : partner.school.parent.name.en || partner.school.parent.name.fr;
                    university.append($("<a></<a>").attr("href", partner.school.parent.url).attr("target", "_blank").attr("class", "link-pretty")
                        .append($("<span></span>").text(' (' + partnerSchoolParentName + ')')));
                }
                row.append(university);

                var codeList = $("<ul></ul>");
                var placeList = $("<ul></ul>");

                partner.accords.map((accord) => {
                    if (accord.isCurrent == true) {
                        var codeGroup = $('<li></li>').attr("class", " ").data("order", {f: "", s: ""});
                        var totalPlaces = $('<li></li>').attr("class", " ").data("order", {f: "", s: ""});

                        accord.sections.map((as) => {
                            var code = translate(as.section.code.fr);
                            var codeElem = $('<span></span>').attr("class", code).text(as.section.code.fr);

                            if (as.placesShared == true) {
                                codeGroup.append(codeElem);
                                codeGroup.attr("class", codeGroup.attr("class") + ' ' + code).data("order", {
                                    f: codeGroup.data("order").f + as.faculty + code,
                                    s: codeGroup.data("order").s
                                });
                                totalPlaces.attr("class", codeGroup.attr("class") + ' ' + code).data("order", {
                                    f: codeGroup.data("order").f + as.faculty + code,
                                    s: codeGroup.data("order").s
                                }).text(as.placesOut);
                            } else {
                                codeList.append($("<li></li>").attr("class", code).data("order", {
                                    f: as.faculty,
                                    s: code
                                }).append(codeElem));
                                placeList.append($("<li></li>").attr("class", code).data("order", {
                                    f: as.faculty,
                                    s: code
                                }).text(as.placesOut));
                            }

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
                    exchanges.append($("<a></a>").attr("href", site).attr("class", "information").attr("title", universityInformation).attr("target", "_blank")
                        .append($("<button></button>").attr("class", "icon-white").text(" ")));
                });
                row.append(exchanges);

                // Datasheet
                if (partner.datasheet !== null) {
                    row.append($("<td></td>").attr("class", "align-baseline documents")
                        .append($("<a></a>").attr("href", hostname + partner.datasheet.href).attr("class", "pdf").attr("title", fichePDF).attr("target", "_blank")
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

/**
 * @param $jdata
 * @description method to create the map page
 * @return void
 */
function getRegions($jdata, $lang): void
{
    ?>
    <script>
        var lang = <?= json_encode($lang, JSON_UNESCAPED_UNICODE); ?>;
        var jdata = <?php echo $jdata; ?>;
        var el = $('#map-list').find('#map')
        var rList = jdata.map(function (d) {
            return lang == 'fr' ? d.region.name.fr : d.region.name.en
        });
        var uniqueArray = [...new Set(rList)];

        uniqueArray.map((region) => {
            var rel = $("<div></div>").attr("id", translate(region)).attr("tabindex", "0").attr("class", "selection-link country-menu country-hover").attr("href", '#' + translate(region)).attr("data-type", "region");

            /* The menu stores the number of countries - used to activate the map colours (but no longer used as a menu) */
            var cm = $("<div></div>").attr("class", "country-menu");
            cm.append($("<span></span>").attr("class", "country-listing").attr("data-type", "country"));
            rel.append(cm);

            el.append(rel);
        });
    </script>
    <?php
}

/**
 * @param $utils
 * @param $labels
 * @description method to create the section filter
 * @return void
 */
function initSectionsFilter($utils, $labels): void
{
    $sectionsUrl = $utils->hostname . "services/mobilite/sections";
    $sections = $utils->call_service($sectionsUrl);
    if ($sections['httpCode'] === 200) {
        $sectionsJson = $sections['response'];
        ?>
        <script>
            var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
            var selectFilterText = <?= json_encode($labels['selectFilterText'], JSON_UNESCAPED_UNICODE); ?>;
            var sectionsJson = <?php echo $sectionsJson; ?>;
            var sel = $("#inSectionsFilter")

            sel.empty(); // remove old options

            var sb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text("Section");
            var smenu = $("<ul></ul>").attr("class", "dropdown-menu");

            smenu.append($("<li></li>").attr("class", "dropdown-item")
                .append($("<label></label>")
                    .append($("<a></a>").attr("href", "#").attr("class", "show-all").text(selectFilterText))));

            sectionsJson.forEach(function (el) {
                var id = el.name.fr;
                var name = (lang == 'fr') ? el.name.fr : el.name.en;
                var code = el.code.fr;
                var li = $("<li></li>").attr("class", "dropdown-item");
                var label = $("<label></label>").attr("for", translate(id) + '_id').text(name);
                var input = $("<input></input>")
                    .attr("id", translate(id) + '_id')
                    .attr("type", "checkbox")
                    .attr("class", "section-selection")
                    .attr("name", "section")
                    .attr("value", translate(code));

                li.append(input);
                li.append(label);
                smenu.append(li);
            });
            sel.append(sb);
            sel.append(smenu);
        </script>
        <?php
    } else {
        show_error_message($sectionsUrl, $sections['httpCode'], $labels);
    }
}

add_action('init', function () {
    add_shortcode('epfl_partner_universities', 'epfl_partner_universities_process_shortcode');
});
