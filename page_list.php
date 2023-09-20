<div id="in-enterprise-list" class="container en">
	<div class="row in-option-bar in-filter-bar">
		<div class="col-sm-2 dropdown form-group hidden">
			<button type="button" class="btn btn-secondary ms-choice"
					data-toggle="dropdown"><?php _e('regionFilterText','epfl_partner_universities'); ?></button>
			<ul id="inRegionsFilter" class="menu dropdown-menu hidden">
				<li class="dropdown-item"><label><a href="#"
													class="show-all regions"><?php _e('allRegionsText','epfl_partner_universities'); ?></a></label>
				</li>
			</ul>
		</div>
		<div class="col-sm-2 dropdown form-group hidden">
			<button type="button" class="btn btn-secondary ms-choice"
					data-toggle="dropdown"><?php _e('countryFilterText','epfl_partner_universities'); ?></button>
			<ul id="inCountriesFilter" class="menu dropdown-menu hidden">
				<li class="dropdown-item"><label><a href="#"
													class="show-all countries"><?php _e('allCountriesText','epfl_partner_universities'); ?></a></label>
				</li>
			</ul>
		</div>
		<div class="col-sm-2 dropdown form-group hidden">
			<button type="button" class="btn btn-secondary ms-choice"
					data-toggle="dropdown"><?php _e('townFilterText','epfl_partner_universities'); ?></button>
			<ul id="inCitiesFilter" class="menu dropdown-menu hidden">
				<li class="dropdown-item"><label><a href="#"
													class="show-all cities"><?php _e('allCitiesText','epfl_partner_universities'); ?></a></label>
				</li>
			</ul>
		</div>
		<div class="col-sm-2 showall">
			<button class="btn btn-secondary btn-sm" title="Tout afficher">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-browse"></use>
				</svg>
				<span class="label"><?php _e('showAll','epfl_partner_universities'); ?></span>
			</button>
		</div>
	</div>
</div>
<div id="in-table-content" class="container en">

</div>
