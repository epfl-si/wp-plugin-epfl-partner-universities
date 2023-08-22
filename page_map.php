<div class="container">
    <div id="map-list">
        <div id="map-list-header"></div>
        <div id="map">
            <div id="world" class="fr">
                <div class="continents <?php echo $labels['afriqueId']; ?>"></div>
                <div class="continents <?php echo $labels['europeId']; ?>"></div>
                <div class="continents <?php echo $labels['ameriqueNordId']; ?>"></div>
                <div class="continents <?php echo $labels['ameriqueSudId']; ?>"></div>
                <div class="continents <?php echo $labels['asieId']; ?>"></div>
                <div class="continents <?php echo $labels['oceanieId']; ?>"></div>
            </div>
        </div>
        <div id="map-list-footer" class="row">
            <h4 id="<?php echo $labels['ameriqueNordId']."-footer"; ?>"><?php echo $labels['ameriqueNord']; ?></h4>
            <h4 id="<?php echo $labels['ameriqueSudId']."-footer"; ?>"><?php echo $labels['ameriqueSud']; ?></h4>
            <h4 id="<?php echo $labels['europeId']."-footer"; ?>"><?php echo $labels['europe']; ?></h4>
            <h4 id="<?php echo $labels['afriqueId']."-footer"; ?>"><?php echo $labels['afrique']; ?></h4>
            <h4 id="<?php echo $labels['asieId']."-footer"; ?>"><?php echo $labels['asie']; ?></h4>
            <h4 id="<?php echo $labels['oceanieId']."-footer"; ?>"><?php echo $labels['oceanie']; ?></h4>
        </div>
    </div>
    <div id="enterprise-list" class="container">
        <div class="alert alert-info fade show" role="alert">
            <p><?php echo $labels['OUTMessage']; ?></p>
        </div>
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