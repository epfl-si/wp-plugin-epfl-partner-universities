<div class="container">
	<div id="map-list">
		<div id="map-list-header"></div>
		<div id="map">
			<div id="world" class="fr">
				<div class="continents <?php _e('afriqueId','epfl_partner_universities'); ?>"></div>
				<div class="continents <?php _e('europeId','epfl_partner_universities'); ?>"></div>
				<div class="continents <?php _e('ameriqueNordId','epfl_partner_universities'); ?>"></div>
				<div class="continents <?php _e('ameriqueSudId','epfl_partner_universities'); ?>"></div>
				<div class="continents <?php _e('asieId','epfl_partner_universities'); ?>"></div>
				<div class="continents <?php _e('oceanieId','epfl_partner_universities'); ?>"></div>
			</div>
		</div>
		<div id="map-list-footer" class="row">
			<h4 id="<?php _e('ameriqueNordId','epfl_partner_universities'); ?>-footer"><?php _e('ameriqueNord','epfl_partner_universities'); ?></h4>
			<h4 id="<?php _e('ameriqueSudId','epfl_partner_universities') ; ?>-footer"><?php _e('ameriqueSud','epfl_partner_universities'); ?></h4>
			<h4 id="<?php _e('europeId','epfl_partner_universities'); ?>-footer"><?php _e('europe','epfl_partner_universities'); ?></h4>
			<h4 id="<?php _e('afriqueId','epfl_partner_universities'); ?>-footer"><?php _e('afrique','epfl_partner_universities'); ?></h4>
			<h4 id="<?php _e('asieId','epfl_partner_universities'); ?>-footer"><?php _e('asie','epfl_partner_universities'); ?></h4>
			<h4 id="<?php _e('oceanieId','epfl_partner_universities'); ?>-footer"><?php _e('oceanie','epfl_partner_universities'); ?></h4>
		</div>
	</div>
	<div id="enterprise-list" class="container">
		<div class="alert alert-info fade show" role="alert">
			<p><?php  _e('OUTMessage','epfl_partner_universities'); ?></p>
		</div>
		<div class="row in-option-bar filter-bar">
			<div id="inSectionsFilter" class="col-sm-2 dropdown form-group hidden"></div>
			<div id="inRegionsFilter" class="col-sm-2 dropdown form-group hidden"></div>
			<div id="inCountriesFilter" class="col-sm-2 dropdown form-group hidden"></div>
			<div id="inCitiesFilter" class="col-sm-2 dropdown form-group hidden"></div>
			<div class="col-sm-2">
				<button class="btn btn-secondary btn-sm showall" title="Tout afficher">
					<svg class="icon" aria-hidden="true">
						<use xlink:href="#icon-browse"></use>
					</svg>
					<span class="label"><?php _e('showAll','epfl_partner_universities'); ?></span></button>
			</div>
			<div class="col-sm-2">
				<button id="showmap" class="btn btn-secondary btn-sm">
					<svg class="icon" aria-hidden="true">
						<use xlink:href="#icon-planet"></use>
					</svg>
					<span class="label"><?php _e('showMap','epfl_partner_universities'); ?></span></button>
			</div>
		</div>

		<hr class="bold">

		<div class="row">
			<div id="table-content" class="col-sm-12"></div>
		</div>
	</div>
</div>
