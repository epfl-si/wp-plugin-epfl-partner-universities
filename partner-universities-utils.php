<?php

class Utils{

    public $hostname = "https://isa.epfl.ch/";

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
    public function show_error_message($url,$error){
        $message = $url . ' - ' . $error;
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    /**
     * @param $language
     * @param $placesJson
     * @description method to create the region dropdown menu
     * @return void
     */
    public function createRegionMenu($language, $placesJson){
        ?>
            <script>
                var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
                var placesJson = <?php echo $placesJson; ?>;
                var rmenu = $('#inRegionsFilter');
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
            </script>
        <?php
    }

    /**
     * @param $language
     * @param $placesJson
     * @description method to create the country dropdown menu
     * @return void
     */
    public function createCountryMenu($language, $placesJson){
        ?>
        <script>
            var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
            var placesJson = <?php echo $placesJson; ?>;
            var cmenu = $('#inCountriesFilter')
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
        </script>
        <?php
    }

    /**
     * @param $language
     * @param $placesJson
     * @description method to create the city dropdown menu
     * @return void
     */
    public function createCityMenu($language, $placesJson){
        ?>
        <script>
            var lang = <?= json_encode($language, JSON_UNESCAPED_UNICODE); ?>;
            var placesJson = <?php echo $placesJson; ?>;
            var tmenu = $('#inCitiesFilter')
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
        </script>
        <?php
    }
}