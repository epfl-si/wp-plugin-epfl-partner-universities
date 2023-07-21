<div id="in-enterprise-list">
    <div class="row in-option-bar in-filter-bar">
        <div class="col-sm-2 dropdown form-group hidden">
            <button type="button" class="btn btn-secondary ms-choice" data-toggle="dropdown"><?php echo $regionFilterText; ?></button>
            <ul id="inRegionsFilter" class="menu dropdown-menu hidden">
                <li class="dropdown-item"><label><a href="#" class="show-all regions"><?php echo $allRegionsText; ?></a></label></li>
            </ul>
        </div>
        <div class="col-sm-2 dropdown form-group hidden">
            <button type="button" class="btn btn-secondary ms-choice" data-toggle="dropdown"><?php echo $countryFilterText; ?></button>
            <ul id="inCountriesFilter" class="menu dropdown-menu hidden" >
                <li class="dropdown-item"><label><a href="#" class="show-all countries"><?php echo $allCountriesText; ?></a></label></li>
            </ul>
        </div>
        <div class="col-sm-2 dropdown form-group hidden">
            <button type="button" class="btn btn-secondary ms-choice" data-toggle="dropdown"><?php echo $townFilterText; ?></button>
            <ul id="inCitiesFilter" class="menu dropdown-menu hidden">
                <li class="dropdown-item"><label><a href="#" class="show-all cities"><?php echo $allCitiesText; ?></a></label></li>
            </ul>
        </div>
        <div class="col-sm-2 showall">
            <button class="btn btn-secondary btn-sm" title="Tout afficher">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-browse"></use>
                </svg>
                <span class="label"><?php echo $showAll; ?></span>
            </button>
        </div>
    </div>
</div>
<div id="in-table-content">

</div>