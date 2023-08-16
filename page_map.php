<div class="container">
    <div id="map-list">
        <div id="map-list-header"></div>
        <div id="map">
            <div id="world" class="fr">
                <div class="continents africa"></div>
                <div class="continents europe"></div>
                <div class="continents amerique-du-nord"></div>
                <div class="continents amerique-du-sud"></div>
                <div class="continents asie"></div>
                <div class="continents oceanie"></div>
            </div>
        </div>
        <div id="map-list-footer" class="row">
            <h4 id="amerique-du-nord-footer"><?php echo $labels['ameriqueNord']; ?></h4>
            <h4 id="amerique-du-sud-footer"><?php echo $labels['ameriqueSud']; ?></h4>
            <h4 id="europe-footer"><?php echo $labels['europe']; ?></h4>
            <h4 id="africa-footer"><?php echo $labels['afrique']; ?></h4>
            <h4 id="asie-footer"><?php echo $labels['asie']; ?></h4>
            <h4 id="oceanie-footer"><?php echo $labels['oceanie']; ?></h4>
        </div>
    </div>
    <div id="enterprise-list" class="container">
        <div class="row in-option-bar filter-bar">
            <div id="inSectionsFilter" class="col-sm-2 dropdown form-group hidden"></div>
            <div id="inRegionsFilter" class="col-sm-2 dropdown form-group hidden"></div>
            <div id="inCountriesFilter" class="col-sm-2 dropdown form-group hidden"></div>
            <div id="inCitiesFilter" class="col-sm-2 dropdown form-group hidden"></div>
            <div class="col-sm-2"><button class="btn btn-secondary btn-sm showall" title="Tout afficher"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-browse"></use></svg><span class="label"><?php echo $labels['showAll']; ?></span></button></div>
            <div class="col-sm-2"><button id="showmap" class="btn btn-secondary btn-sm"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-planet"></use></svg><span class="label"><?php echo $labels['showMap']; ?></span></button></div>
        </div>

        <hr class="bold">

        <div class="row">
            <div id="table-content" class="col-sm-12"></div>
        </div>
    </div>
</div>