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
        'language' => ''
    ], $atts);

    /*include all required classes*/
    require_once('partner-universities-utils.php');
    require_once('partner-universities-traductions.php');
    ?><script><?php require_once("js/script.js");?></script><?php
    wp_enqueue_style( 'epfl_partner_universities_style', plugin_dir_url(__FILE__).'css/styles.css', [], '2.1');

    /*initialization of utility classes*/
    $utils = new Utils();
    $traductions = new Traduction();

    /*prepare the page*/
    $labels = $traductions->translateLabels($atts['language']);
    include('page_list.php');

    /*web services calls*/
    $partnersUrl = $utils->hostname . "services/mobilite/partners";
    $partners = $utils->call_service($partnersUrl);
    if ($partners['httpCode'] === 200) {
        getPartners($partners['response'], $labels);
        $utils->map = false;
        $utils->initPlacesFilter($labels);
    }else{
        $utils->show_error_message($partnersUrl, $partners['httpCode'],$labels);
    }
    return ob_get_clean();
}

/**
 * @param $jdata
 * @param $language
 * @param $cityLabel
 * @param $universityLabel
 * @description web service call for partners list and list définition
 * @return void
 */
function getPartners($jdata, $labels){
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
                        var row = $("<tr></tr>").attr("class", rowClasses + " cityKey" + ' ' +  townKey);
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