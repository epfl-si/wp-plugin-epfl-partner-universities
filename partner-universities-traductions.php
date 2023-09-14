<?php

class PartnerUniversitiesTraduction
{

	public function translateLabels($lang): array
	{
		$labels = [];
		if ($lang == 'FR') {
			$labels['language'] = 'fr';
			$labels['regionFilterText'] = "Région";
			$labels['countryFilterText'] = "Pays";
			$labels['townFilterText'] = "Ville";
			$labels['allRegionsText'] = 'Toutes les regions';
			$labels['allCountriesText'] = 'Tous les pays';
			$labels['allCitiesText'] = 'Toutes les villes';
			$labels['showAll'] = 'Tout afficher';
			$labels['cityLabel'] = 'Ville';
			$labels['universityLabel'] = 'Université';
			$labels['showMap'] = 'Voir la carte';
			$labels['selectMapText'] = 'Sélectionnez votre section';
			$labels['selectFilterText'] = 'Toutes les sections';
			$labels['sectionText'] = 'Section';
			$labels['remText'] = 'Remarques';
			$labels['ameriqueNord'] = 'Amérique-du-Nord';
			$labels['ameriqueSud'] = 'Amérique-du-Sud';
			$labels['europe'] = 'Europe';
			$labels['afrique'] = 'Afrique';
			$labels['asie'] = 'Asie';
			$labels['oceanie'] = 'Océanie';
			$labels['emailContact'] = 'E-mail de la personne de contact à l\'EPFL';
			$labels['placeDisponibles'] = 'Nombre de places disponibles total';
			$labels['universityInformation'] = 'Plus d\'informations sur le site de l\'université partenaire';
			$labels['fichePDF'] = 'Fiche de l\'université partenaire (PDF)';
			$labels['afriqueId'] = 'afrique';
			$labels['europeId'] = 'europe';
			$labels['ameriqueNordId'] = 'amerique-du-nord';
			$labels['ameriqueSudId'] = 'amerique-du-sud';
			$labels['asieId'] = 'asie';
			$labels['oceanieId'] = 'oceanie';
			$labels['errorMessage'] = 'Cette page n\'est actuellement pas disponible - N’hésitez pas à nous en informer au <a href="mailto:1234@epfl.ch">1234@epfl.ch</a>.';
			$labels['OUTMessage'] = 'Pour toute question spécifique concernant ces destinations d’échange pour la 3e année Bachelor, vous pouvez écrire à <a href="mailto:sac-exchange-out@epfl.ch">sac-exchange-out@epfl.ch</a>. Veuillez indiquer dans le titre de votre courriel le ou les pays concernés par vos questions.';
		} else {
			$labels['language'] = 'en';
			$labels['regionFilterText'] = "Region";
			$labels['countryFilterText'] = "Country";
			$labels['townFilterText'] = "City";
			$labels['allRegionsText'] = 'All regions';
			$labels['allCountriesText'] = 'All countries';
			$labels['allCitiesText'] = 'All cities';
			$labels['showAll'] = 'Show all';
			$labels['cityLabel'] = 'City';
			$labels['universityLabel'] = 'University';
			$labels['showMap'] = 'View Map';
			$labels['selectMapText '] = 'Please choose a section';
			$labels['selectFilterText'] = 'All sections';
			$labels['sectionText'] = 'Sections';
			$labels['remText'] = 'Remarks';
			$labels['ameriqueNord'] = 'North-America';
			$labels['ameriqueSud'] = 'South-America';
			$labels['europe'] = 'Europe';
			$labels['afrique'] = 'Africa';
			$labels['asie'] = 'Asia';
			$labels['oceanie'] = 'Oceania';
			$labels['emailContact'] = 'E-mail of the contact person at EPFL';
			$labels['placeDisponibles'] = 'Number of total available slots';
			$labels['universityInformation'] = 'More information on the website of the partner university';
			$labels['fichePDF'] = 'Fact sheet of the partner university (PDF)';
			$labels['afriqueId'] = 'africa';
			$labels['europeId'] = 'europe';
			$labels['ameriqueNordId'] = 'north-america';
			$labels['ameriqueSudId'] = 'south-america';
			$labels['asieId'] = 'asia';
			$labels['oceanieId'] = 'oceania';
			$labels['errorMessage'] = 'This page is temporarily unavailable – let us know at <a href="mailto:1234@epfl.ch">1234@epfl.ch</a>.';
			$labels['OUTMessage'] = 'If you have any specific questions about these exchange destinations for the 3rd year Bachelor\'s programme, please write to <a href="mailto:sac-exchange-out@epfl.ch">sac-exchange-out@epfl.ch</a>. Please indicate in the title of your e-mail the country or countries you are enquiring about.';
		}
		return $labels;
	}
}
