<?php

class PartnerUniversitiesTraduction
{

	public function translateLabels($lang): array
	{
		$labels = [];
		if ($lang == 'FR') {
			$labels['language'] = 'fr';
		} else {
			$labels['language'] = 'en';
		}
		return $labels;
	}
}
