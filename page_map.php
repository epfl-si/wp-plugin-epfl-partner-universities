<div class="container">
	<div id="enterprise-list" class="container">
		<div class="alert alert-info fade show" role="alert">
			<p><?php  _e('OUTMessageParticularities','epfl_partner_universities'); ?></p>
		</div>
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
		</div>

		<hr class="bold">

		<div class="row">
			<div id="table-content" class="col-sm-12"></div>
		</div>
	</div>
</div>
