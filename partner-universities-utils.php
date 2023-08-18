<?php

class Utils{

    public string $hostname = "https://isa.epfl.ch/";
    public bool $map = false;

    /**
     * @param $url
     * @description method to call services
     * @return array with response object and response code
     */
    public function call_service($url): array
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

    /**
     * @param $url
     * @param $error
     * @description method to show the error message in the page after calling web service
     * @return void
     */
    public function show_error_message($url,$error, $labels): void
    {
        //$message = $url . ' - ' . $error;
        echo '<div class="error-container">';
        echo '<h1>Oops, something went wrong!</h1>';
        echo '<p>' . $labels['errorMessage'] . '</p>';
        echo '</div>';
    }

    /**
     * @param $utils
     * @param $language
     * @description initialization of the filter
     * @return void
     */
    public function initPlacesFilter($labels): void
    {
        $placesUrl = $this->hostname . "services/mobilite/places";
        $places = $this->call_service($placesUrl);
        if ($places['httpCode'] === 200) {
            $placesJson = $places['response'];
            $this->createRegionMenu($labels, $placesJson);
            $this->createCountryMenu($labels, $placesJson);
            $this->createCityMenu($labels, $placesJson);
        }else{
            $this->show_error_message($placesUrl, $places['httpCode'],$labels);
        }
    }


    /**
     * @param $labels
     * @param $placesJson
     * @param $map
     * @return void
     * @description method to create the region dropdown menu
     */
    public function createRegionMenu($labels, $placesJson): void
    {
        ?>
            <script>
                var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
                var placesJson = <?php echo $placesJson; ?>;
                var map = <?php echo $this->map ? 'true' : 'false'; ?>;
                if(map){
                    var allRegionsText = <?= json_encode($labels['allRegionsText'], JSON_UNESCAPED_UNICODE); ?>;
                    var regionFilterText = <?= json_encode($labels['regionFilterText'], JSON_UNESCAPED_UNICODE); ?>;
                    var rel = $('#inRegionsFilter');
                    rel.empty();
                    var rb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(regionFilterText);
                    var rmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
                    rmenu.append($("<li></li>").attr("class", "dropdown-item")
                        .append($("<label></label>")
                            .append($("<a></a>").attr("href", "#").attr("class", "show-all regions").text(allRegionsText))));
                }else {
                    var rmenu = $('#inRegionsFilter');
                }
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
                if(map){
                    rel.append(rb);
                    rel.append(rmenu);
                }
            </script>
        <?php
    }

    /**
     * @param $labels
     * @param $placesJson
     * @param $map
     * @return void
     * @description method to create the country dropdown menu
     */
    public function createCountryMenu($labels, $placesJson): void
    {
        ?>
        <script>
            var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
            var placesJson = <?php echo $placesJson; ?>;
            var map = <?php echo $this->map ? 'true' : 'false'; ?>;
            if(map){
                var countryFilterText = <?= json_encode($labels['countryFilterText'], JSON_UNESCAPED_UNICODE); ?>;
                var allCountriesText = <?= json_encode($labels['allCountriesText'], JSON_UNESCAPED_UNICODE); ?>;
                var cel = $('#inCountriesFilter')
                cel.empty();
                var cb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(countryFilterText);
                var cmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
                cmenu.append($("<li></li>").attr("class", "dropdown-item")
                    .append($("<label></label>")
                        .append($("<a></a>").attr("href", "#").attr("class", "show-all countries").text(allCountriesText))));
            }else{
                var cmenu = $('#inCountriesFilter');
            }
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
            if(map){
                cel.append(cb);
                cel.append(cmenu);
            }
        </script>
        <?php
    }

    /**
     * @param $labels
     * @param $placesJson
     * @param $map
     * @return void
     * @description method to create the city dropdown menu
     */
    public function createCityMenu($labels, $placesJson): void
    {
        ?>
        <script>
            var lang = <?= json_encode($labels['language'], JSON_UNESCAPED_UNICODE); ?>;
            var placesJson = <?php echo $placesJson; ?>;
            var map = <?php echo $this->map ? 'true' : 'false'; ?>;
            if(map){
                var townFilterText = <?= json_encode($labels['townFilterText'], JSON_UNESCAPED_UNICODE); ?>;
                var allCitiesText = <?= json_encode($labels['allCitiesText'], JSON_UNESCAPED_UNICODE); ?>;
                var tel = $('#inCitiesFilter')
                tel.empty();
                var tb = $("<button></button>").attr("type", "button").attr("class", "btn btn-secondary ms-choice").attr("data-toggle", "dropdown").text(townFilterText);
                var tmenu = $("<ul></ul>").attr("class", "menu dropdown-menu");
                tmenu.append($("<li></li>").attr("class", "dropdown-item")
                    .append($("<label></label>")
                        .append($("<a></a>").attr("href", "#").attr("class", "show-all cities").text(allCitiesText))));
            }else{
                var tmenu = $('#inCitiesFilter');
            }
            placesJson.forEach(function(geo){
                var regionName = (lang === 'fr') ? geo.region.name.fr : geo.region.name.en;
                var rdiv = $('<div />').attr("class", "country-selector").attr("id", translate(regionName));
                rdiv.append($('<li></li>').attr("class", "dropdown-item region-group")
                    .append($("<label></label>").text(regionName)));
                geo.region.countries.forEach(function(a){
                    var countryName = (lang === 'fr') ? a.country.name.fr : a.country.name.en;
                    var cdiv = $('<div />').attr("class", "city-selector").attr("id", translate(countryName));
                    cdiv.append($('<li></li>').attr("class", "dropdown-item country-group")
                        .append($("<label></label>").text(countryName)));
                    a.country.towns.forEach(function(town) {
                        var name = (lang === 'fr') ? town.fr : town.en;
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
            if(map){
                tel.append(tb);
                tel.append(tmenu);
            }
        </script>
        <?php
    }
}