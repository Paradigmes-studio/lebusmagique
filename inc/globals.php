<?php

// Mimes
global $a_mimes;
$a_mimes = array(
    'application/pdf' => 'PDF',
    'image/png'       => 'PNG',
    'image/jpeg'      => 'JPG',
);

// Days of week
global $a_days_of_week;
$a_days_of_week = array( 0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi' );

// Days
global $a_days;
$a_days = array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15',
                '16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');

// Months
global $a_months;
$a_months = array('01' => 'Janvier','02' => 'Février','03' => 'Mars','04' => 'Avril',
                  '05' => 'Mai','06' => 'Juin','07' => 'Juillet','08' => 'Août',
                  '09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre');

// Short Months
global $a_short_months;
$a_short_months = array('Jan' => 'Janvier', 'Feb' => 'Février', 'Mar' => 'Mars', 'Apr' => 'Avril', 'May' => 'Mai', 'Jun' => 'Juin',
                        'Jui' => 'Juillet', 'Aug' => 'Août', 'Sep' => 'Septembre', 'Oct' => 'Octobre', 'Nov' => 'Novembre', 'Dec' => 'Décembre' );

// Years
global $a_years;
for($i = 2016 ; $i < date('Y')+4 ; $i++){
 $a_years[] = $i;
}

// Dept
global $a_depts;
$a_depts = array(
    '01' => '01 - Ain', '02' => '02 - Aisne', '03' => '03 - Allier', '04' => '04 - Alpes-de-Haute-Provence', '05' => '05 - Hautes-alpes',
    '06' => '06 - Alpes-maritimes', '07' => '07 - Ardèche', '08' => '08 - Ardennes',  '09' => '09 - Ariège',  '10' => '10 - Aube',
    '11' => '11 - Aude', '12' => '12 - Aveyron', '13' => '13 - Bouches-du-Rhône', '14' => '14 - Calvados', '15' => '15 - Cantal',
    '16' => '16 - Charente', '17' => '17 - Charente-maritime', '18' => '18 - Che', '19' => '19 - Corrèze', '2a' => '2a - Corse-du-sud', '2b' => '2b - Haute-Corse',
    '21' => '21 - Côte-d\'Or', '22' => '22 - Côtes-d\'Armor', '23' => '23 - Creuse', '24' => '24 - Dordogne', '25' => '25 - Doubs',
    '26' => '26 - Drôme', '27' => '27 - Eure', '28' => '28 - Eure-et-loire', '29' => '29 - Finistère', '30' => '30 - Gard',
    '31' => '31 - Haute-garonne', '32' => '32 - Gers', '33' => '33 - Gironde', '34' => '34 - Hérault', '35' => '35 - Ille-et-vilaine',
    '36' => '36 - Indre', '37' => '37 - Indre-et-loire', '38' => '38 - Isère', '39' => '39 - Jura',  '40' => '40 - Landes',
    '41' => '41 - Loir-et-cher', '42' => '42 - Loire', '43' => '43 - Haute-loire', '44' => '44 - Loire-atlantique', '45' => '45 - Loiret',
    '46' => '46 - Lot', '47' => '47 - Lot-et-garonne', '48' => '48 - Lozère - Mende', '49' => '49 - Maine-et-loire', '50' => '50 - Manche',
    '51' => '51 - Marne', '52' => '52 - Haute-marne', '53' => '53 - Mayenne', '54' => '54 - Meurthe-et-moselle', '55' => '55 - Meuse',
    '56' => '56 - Morbihan', '57' => '57 - Moselle', '58' => '58 - Nièvre', '59' => '59 - Nord', '60' => '60 - Oise',
    '61' => '61 - Orne',  '62' => '62 - Pas-de-calais', '63' => '63 - Puy-de-dôme', '64' => '64 - Pyrénées-atlantiques', '65' => '65 - Hautes-Pyrénées',
    '66' => '66 - Pyrénées-orientales',  '67' => '67 - Bas-rhin', '68' => '68 - Haut-rhin', '69' => '69 - Rhône', '70' => '70 - Haute-saône',
    '71' => '71 - Saône-et-loire', '72' => '72 - Sarthe', '73' => '73 - Savoie', '74' => '74 - Haute-savoie', '75' => '75 - Paris',
    '76' => '76 - Seine-maritime', '77' => '77 - Seine-et-marne', '78' => '78 - Yvelines', '79' => '79 - Deux-sèvres', '80' => '80 - Somme',
    '81' => '81 - Tarn', '82' => '82 - Tarn-et-garonne', '83' => '83 - Var', '84' => '84 - Vaucluse', '85' => '85 - Vendée',
    '86' => '86 - Vienne', '87' => '87 - Haute-vienne', '88' => '88 - Vosges', '89' => '89 - Yonne', '90' => '90 - Territoire de belfort',
    '91' => '91 - Essonne', '92' => '92 - Hauts-de-seine', '93' => '93 - Seine-Saint-Denis', '94' => '94 - Val-de-marne', '95' => '95 - Val-d\'oise',
    '971' => '971 - Guadeloupe', '972' => '972 - Martinique', '973' => '973 - Guyane', '974' => '974 - La réunion', '976' => '976 - Mayotte'
);

// Regions
global $a_regions;
$a_regions = array(
    1  => 'Auvergne-Rhône-Alpes',
    2  => 'Bourgogne-Franche-Comté',
    3  => 'Bretagne',
    4  => 'Centre-Val de Loire',
    5  => 'Corse',
    6  => 'Grand Est',
    7  => 'Hauts-de-France',
    8  => 'Ile-de-France',
    9  => 'Normandie',
    10 => 'Nouvelle-Aquitaine',
    11 => 'Occitanie',
    12 => 'Pays de la Loire',
    13 => 'Provence-Alpes-Côte d\'Azur',
    /*14 => 'Guadeloupe',
    15 => 'Guyane',
    16 => 'Martinique',
    17 => 'Mayotte',
    18 => 'La Réunion',*/
);
