<?php

class Traduction{

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
            $labels['selectMapText ']= 'Please choose a section';
            $labels['selectFilterText'] = 'All sections';
            $labels['sectionText'] = 'Sections';
            $labels['remText'] = 'Remarks';
            $labels['ameriqueNord'] = 'Nord-America';
            $labels['ameriqueSud'] = 'Sud-America';
            $labels['europe'] = 'Europe';
            $labels['afrique'] = 'Africa';
            $labels['asie'] = 'Asia';
            $labels['oceanie'] = 'Oceania';
            $labels['emailContact'] = 'E-mail of the contact person at EPFL';
            $labels['placeDisponibles'] = 'Number of total available slots';
            $labels['universityInformation'] = 'More information on the website of the partner university';
            $labels['fichePDF'] = 'Fact sheet of the partner university (PDF)';
        }
        return $labels;
    }
}
