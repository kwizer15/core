<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

global $JEEDOM_INTERNAL_CONFIG;
$JEEDOM_INTERNAL_CONFIG = array(
	'eqLogic' => array(
		'category' => array(
			'heating' => array('name' => new Trad( 'Chauffage', __FILE__), 'icon' => 'fas fa-fire'),
			'security' => array('name' => new Trad( 'Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'energy' => array('name' => new Trad( 'Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'light' => array('name' => new Trad( 'Lumière', __FILE__), 'icon' => 'far fa-lightbulb'),
			'opening' => array('name' => new Trad( 'Ouvrant', __FILE__), 'icon' => 'fas fa-door-open'),
			'automatism' => array('name' => new Trad( 'Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'multimedia' => array('name' => new Trad( 'Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'default' => array('name' => new Trad( 'Autre', __FILE__), 'icon' => 'far fa-circle'),
		),
		'displayType' => array(
			'dashboard' => array('name' => 'Dashboard'),
			'mobile' => array('name' => 'Mobile'),
		),
	),
	'interact' => array(
		'test' => array(
			'>' => array('superieur', '>', 'plus de', 'depasse'),
			'<' => array('inferieur', '<', 'moins de', 'descends en dessous'),
			'=' => array('egale', '=', 'vaut'),
			'!=' => array('different'),
		),
	),
	'plugin' => array(
		'category' => array(
			'security' => array('name' => new Trad( 'Sécurité', __FILE__), 'icon' => 'fas fa-lock'),
			'automation protocol' => array('name' => new Trad( 'Protocole domotique', __FILE__), 'icon' => 'fas fa-rss'),
			'home automation protocol' => array('name' => new Trad( 'Passerelle domotique', __FILE__), 'icon' => 'fas fa-asterisk'),
			'programming' => array('name' => new Trad( 'Programmation', __FILE__), 'icon' => 'fas fa-code'),
			'organization' => array('name' => new Trad( 'Organisation', __FILE__), 'icon' => 'far fa-calendar-alt', 'alias' => array('travel', 'finance')),
			'weather' => array('name' => new Trad( 'Météo', __FILE__), 'icon' => 'far fa-sun'),
			'communication' => array('name' => new Trad( 'Communication', __FILE__), 'icon' => 'fas fa-comment'),
			'devicecommunication' => array('name' => new Trad( 'Objets connectés', __FILE__), 'icon' => 'fas fa-language'),
			'multimedia' => array('name' => new Trad( 'Multimédia', __FILE__), 'icon' => 'fas fa-sliders-h'),
			'wellness' => array('name' => new Trad( 'Confort', __FILE__), 'icon' => 'far fa-user'),
			'monitoring' => array('name' => new Trad( 'Monitoring', __FILE__), 'icon' => 'fas fa-tachometer-alt'),
			'health' => array('name' => new Trad( 'Santé', __FILE__), 'icon' => 'icon loisir-runner5'),
			'nature' => array('name' => new Trad( 'Nature', __FILE__), 'icon' => 'icon nature-leaf32'),
			'automatisation' => array('name' => new Trad( 'Automatisme', __FILE__), 'icon' => 'fas fa-magic'),
			'energy' => array('name' => new Trad( 'Energie', __FILE__), 'icon' => 'fas fa-bolt'),
			'other' => array('name' => new Trad( 'Autre', __FILE__), 'icon' => 'fas fa-bars'),
		),
	),
	'messageChannel' => array(
		'alerting' => array('name' => new Trad( 'Alerte des commandes', __FILE__), 'icon' => '<i class="far fa-bell"></i>'),
		'alertingReturnBack' => array('name' => new Trad( 'Retour à l\'état normal des commandes', __FILE__), 'icon' => '<i class="fas fa-check"></i>')
	),
	'alerts' => array(
		'timeout' => array('name' => new Trad( 'Timeout', __FILE__), 'icon' => 'far fa-clock', 'level' => 6, 'check' => false, 'color' => 'var(--al-danger-color)'),
		'batterywarning' => array('name' => new Trad( 'Batterie en Warning', __FILE__), 'icon' => 'fas fa-battery-quarter', 'level' => 2, 'check' => false, 'color' => 'var(--al-warning-color)'),
		'batterydanger' => array('name' => new Trad( 'Batterie en Danger', __FILE__), 'icon' => 'fas fa-battery-empty', 'level' => 3, 'check' => false, 'color' => 'var(--al-danger-color)'),
		'warning' => array('name' => new Trad( 'Warning', __FILE__), 'icon' => 'fas fa-bell', 'level' => 4, 'check' => true, 'color' => 'var(--al-warning-color)'),
		'danger' => array('name' => new Trad( 'Danger', __FILE__), 'icon' => 'fas fa-exclamation', 'level' => 5, 'check' => true, 'color' => 'var(--al-danger-color)'),
	),
	'cmd' => array(
		'widgets' => array(
			'action' => array(
				'other' => array(
					'toggle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'toggleLine' => array('template' => 'tmpliconline', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'circle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'fas fa-circle\'></i>', '#_icon_off_#' => '<i class=\'far fa-circle\'></i>')),
					'fan' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-ventilo\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'garage' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-garage-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-garage-ouvert\'></i>')),
					'lock' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_orange icon jeedom-lock-ouvert\'></i>')),
					'prise' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'sprinkle' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_blue icon nature-watering1\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeToggle' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow fas fa-toggle-on\'></i>', '#_icon_off_#' => '<i class=\'fas fa-toggle-off\'></i>')),
					'timeLight' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'timeCircle' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'fas fa-circle\'></i>', '#_icon_off_#' => '<i class=\'fas fa-circle-thin\'></i>')),
					'timeFan' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-ventilo\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeGarage' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-garage-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-garage-ouvert\'></i>')),
					'timeLock' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lock-ouvert\'></i>')),
					'timePrise' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'timeSprinkle' => array(
						'template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_blue icon nature-watering1\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')
					),
				),
				'slider' => array(
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'timeLight' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
				)
			),
			'info' => array(
				'binary' => array(
					'icon' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red fas fa-times\'></i>')),
					'line' => array('template' => 'tmpliconline', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red fas fa-times\'></i>')),
					'alert' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-alerte2\'></i>')),
					'door' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-porte-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-porte-ouverte\'></i>')),
					'heat' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_red icon jeedom-feu\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'light' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_yellow icon jeedom-lumiere-on\'></i>', '#_icon_off_#' => '<i class=\'icon jeedom-lumiere-off\'></i>')),
					'lock' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-lock-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-lock-ouvert\'></i>')),
					'presence' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-mouvement\'></i>')),
					'prise' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon jeedom-prise\'></i>', '#_icon_off_#' => '<i class=\'fas fa-times\'></i>')),
					'window' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green icon jeedom-fenetre-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-fenetre-ouverte\'></i>')),
					'flood' => array('template' => 'tmplicon', 'replace' => array('#_icon_on_#' => '<i class=\'icon_green fas fa-tint-slash\'></i>', '#_icon_off_#' => '<i class=\'icon_blue fas fa-tint\'></i>')),
					'timeDoor' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-porte-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-porte-ouverte\'></i>')),
					'timePresence' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-mouvement\'></i>')),
					'timeWindow' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green icon jeedom-fenetre-ferme\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-fenetre-ouverte\'></i>')),
					'timeAlert' => array('template' => 'tmplicon', 'replace' => array('#_time_widget_#' => '1', '#_icon_on_#' => '<i class=\'icon_green fas fa-check\'></i>', '#_icon_off_#' => '<i class=\'icon_red icon jeedom-alerte2\'></i>')),
				),
				'numeric' => array(
					'heatPiloteWire' => array(
						'template' => 'tmplmultistate',
						'test' => array(
							array('operation' => '#value# == 3', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# == 2', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
							array('operation' => '#value# == 1', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# == 0', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>')
						)
					),
					'timeHeatPiloteWire' => array(
						'template' => 'tmplmultistate',
						'replace' => array('#_time_widget_#' => '1'),
						'test' => array(
							array('operation' => '#value# == 3', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# == 2', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
							array('operation' => '#value# == 1', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# == 0', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>')
						)
					),
					'heatPiloteWireQubino' => array(
						'template' => 'tmplmultistate',
						'test' => array(
							array('operation' => '#value# >= 51 && #value# <= 255', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>'),
							array('operation' => '#value# >= 41 && #value# <= 50', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-1</sup>'),
							array('operation' => '#value# >= 31 && #value# <= 40', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-2</sup>'),
							array('operation' => '#value# >= 21 && #value# <= 30', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# >= 11 && #value# <= 20', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# >= 0 && #value# <= 10', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
						)
					),
					'timeHeatPiloteWireQubino' => array(
						'template' => 'tmplmultistate',
						'replace' => array('#_time_widget_#' => '1'),
						'test' => array(
							array('operation' => '#value# >= 51 && #value# <= 255', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i>'),
							array('operation' => '#value# >= 41 && #value# <= 50', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-1</sup>'),
							array('operation' => '#value# >= 31 && #value# <= 40', 'state_light' => '<i class=\'icon jeedom-pilote-conf\'></i><sup style=\'font-size: 0.3em; margin-left: 1px\'>-2</sup>'),
							array('operation' => '#value# >= 21 && #value# <= 30', 'state_light' => '<i class=\'icon jeedom-pilote-eco\'></i>'),
							array('operation' => '#value# >= 11 && #value# <= 20', 'state_light' => '<i class=\'icon jeedom-pilote-hg\'></i>'),
							array('operation' => '#value# >= 0 && #value# <= 10', 'state_light' => '<i class=\'icon jeedom-pilote-off\'></i>'),
						)
					)
				)
			)
		),
		'generic_type' => array(
			'TOGGLE' => array(
				'name' => new Trad( 'Toggle', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ONLINE' => array(
				'name' => new Trad( 'Connecté', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LIGHT_TOGGLE' => array(
				'name' => new Trad( 'Lumière Toggle', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LIGHT_STATE' => array(
				'name' => new Trad( 'Lumière Etat', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'LIGHT_ON' => array(
				'name' => new Trad( 'Lumière Bouton On', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_OFF' => array(
				'name' => new Trad( 'Lumière Bouton Off', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LIGHT_SLIDER' => array(
				'name' => new Trad( 'Lumière Slider', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'LIGHT_BRIGHTNESS' => array(
				'name' => new Trad( 'Lumière Luminosité', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'LIGHT_COLOR' => array(
				'name' => new Trad( 'Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'LIGHT_SET_COLOR' => array(
				'name' => new Trad( 'Lumière Couleur', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'color'), 'subtype' => array('color')
			),
			'LIGHT_MODE' => array(
				'name' => new Trad( 'Lumière Mode', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'LIGHT_STATE_BOOL' => array(
				'name' => new Trad( 'Lumière Etat (Binaire)', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LIGHT_COLOR_TEMP' => array(
				'name' => new Trad( 'Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'LIGHT_SET_COLOR_TEMP' => array(
				'name' => new Trad( 'Lumière Température Couleur', __FILE__), 'familyid' => 'Light', 'family' => new Trad( 'Lumière', __FILE__),
				'type' => 'Action'
			),
			'ENERGY_STATE' => array(
				'name' => new Trad( 'Prise Etat', __FILE__), 'familyid' => 'Outlet', 'family' => new Trad( 'Prise', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'binary')
			),
			'ENERGY_ON' => array(
				'name' => new Trad( 'Prise Bouton On', __FILE__), 'familyid' => 'Outlet', 'family' => new Trad( 'Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_OFF' => array(
				'name' => new Trad( 'Prise Bouton Off', __FILE__), 'familyid' => 'Outlet', 'family' => new Trad( 'Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ENERGY_SLIDER' => array(
				'name' => new Trad( 'Prise Slider', __FILE__), 'familyid' => 'Outlet', 'family' => new Trad( 'Prise', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider')
			),
			'FLAP_STATE' => array(
				'name' => new Trad( 'Volet Etat', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_UP' => array(
				'name' => new Trad( 'Volet Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_DOWN' => array(
				'name' => new Trad( 'Volet Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_STOP' => array(
				'name' => new Trad( 'Volet Bouton Stop', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action'
			),
			'FLAP_SLIDER' => array(
				'name' => new Trad( 'Volet Bouton Slider', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'slider'), 'subtype' => array('slider')
			),
			'FLAP_BSO_STATE' => array(
				'name' => new Trad( 'Volet BSO Etat', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'FLAP_BSO_UP' => array(
				'name' => new Trad( 'Volet BSO Bouton Monter', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'FLAP_BSO_DOWN' => array(
				'name' => new Trad( 'Volet BSO Bouton Descendre', __FILE__), 'familyid' => 'Shutter', 'family' => new Trad( 'Volet', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_ON' => array(
				'name' => new Trad( 'Chauffage fil pilote Bouton ON', __FILE__), 'familyid' => 'Heating', 'family' => new Trad( 'Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_OFF' => array(
				'name' => new Trad( 'Chauffage fil pilote Bouton OFF', __FILE__), 'familyid' => 'Heating', 'family' => new Trad( 'Chauffage', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'HEATING_STATE' => array(
				'name' => new Trad( 'Chauffage fil pilote Etat', __FILE__), 'familyid' => 'Heating', 'family' => new Trad( 'Chauffage', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'HEATING_OTHER' => array(
				'name' => new Trad( 'Chauffage fil pilote Bouton', __FILE__), 'familyid' => 'Heating', 'family' => new Trad( 'Chauffage', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'LOCK_STATE' => array(
				'name' => new Trad( 'Serrure Etat', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'LOCK_OPEN' => array(
				'name' => new Trad( 'Serrure Bouton Ouvrir', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'LOCK_CLOSE' => array(
				'name' => new Trad( 'Serrure Bouton Fermer', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_OPEN' => array(
				'name' => new Trad( 'Portail ou garage bouton d\'ouverture', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_CLOSE' => array(
				'name' => new Trad( 'Portail ou garage bouton de fermeture', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'GB_TOGGLE' => array(
				'name' => new Trad( 'Portail ou garage bouton toggle', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'BARRIER_STATE' => array(
				'name' => new Trad( 'Portail (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'GARAGE_STATE' => array(
				'name' => new Trad( 'Garage (ouvrant) Etat', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING' => array(
				'name' => new Trad( 'Porte', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'OPENING_WINDOW' => array(
				'name' => new Trad( 'Fenêtre', __FILE__), 'familyid' => 'Opening', 'family' => new Trad( 'Ouvrant', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_STATE' => array(
				'name' => new Trad( 'Thermostat Etat', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE' => array(
				'name' => new Trad( 'Thermostat Température ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_SETPOINT' => array(
				'name' => new Trad( 'Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_SETPOINT' => array(
				'name' => new Trad( 'Thermostat consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_MODE' => array(
				'name' => new Trad( 'Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'THERMOSTAT_MODE' => array(
				'name' => new Trad( 'Thermostat Mode', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_SET_LOCK' => array(
				'name' => new Trad( 'Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_SET_UNLOCK' => array(
				'name' => new Trad( 'Thermostat Déverrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'THERMOSTAT_LOCK' => array(
				'name' => new Trad( 'Thermostat Verrouillage', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'THERMOSTAT_TEMPERATURE_OUTDOOR' => array(
				'name' => new Trad( 'Thermostat Température Exterieur', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_STATE_NAME' => array(
				'name' => new Trad( 'Thermostat Etat (HUMAIN)', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'THERMOSTAT_HUMIDITY' => array(
				'name' => new Trad( 'Thermostat humidité ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'THERMOSTAT_SET_MAX_TEMP' => array(
				'name' => new Trad( 'Thermostat maximum consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_SET_MIN_TEMP' => array(
				'name' => new Trad( 'Thermostat minimum consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'THERMOSTAT_HUMIDITY' => array(
				'name' => new Trad( 'Thermostat humidité ambiante', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'HUMIDITY_SETPOINT' => array(
				'name' => new Trad( 'Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Info', 'subtype' => array('slider')
			),
			'HUMIDITY_SET_SETPOINT' => array(
				'name' => new Trad( 'Humidité consigne', __FILE__), 'familyid' => 'Thermostat', 'family' => new Trad( 'Thermostat', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'CAMERA_UP' => array(
				'name' => new Trad( 'Mouvement caméra vers le haut', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DOWN' => array(
				'name' => new Trad( 'Mouvement caméra vers le bas', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_LEFT' => array(
				'name' => new Trad( 'Mouvement caméra vers la gauche', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_RIGHT' => array(
				'name' => new Trad( 'Mouvement caméra vers la droite', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_ZOOM' => array(
				'name' => new Trad( 'Zoom caméra vers l\'avant', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_DEZOOM' => array(
				'name' => new Trad( 'Zoom caméra vers l\'arrière', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_STOP' => array(
				'name' => new Trad( 'Stop caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_PRESET' => array(
				'name' => new Trad( 'Preset caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'CAMERA_URL' => array(
				'name' => new Trad( 'URL caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'CAMERA_RECORD_STATE' => array(
				'name' => new Trad( 'État enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'CAMERA_RECORD' => array(
				'name' => new Trad( 'Enregistrement caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action'
			),
			'CAMERA_TAKE' => array(
				'name' => new Trad( 'Snapshot caméra', __FILE__), 'familyid' => 'Camera', 'family' => new Trad( 'Caméra', __FILE__),
				'type' => 'Action'
			),
			'MODE_STATE' => array(
				'name' => new Trad( 'Mode Etat', __FILE__), 'familyid' => 'Mode', 'family' => new Trad( 'Mode', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MODE_SET_STATE' => array(
				'name' => new Trad( 'Changer Mode', __FILE__), 'familyid' => 'Mode', 'family' => new Trad( 'Mode', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'SIREN_STATE' => array(
				'name' => new Trad( 'Sirène Etat', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SIREN_OFF' => array(
				'name' => new Trad( 'Sirène Bouton Off', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'SIREN_ON' => array(
				'name' => new Trad( 'Sirène Bouton On', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Action', 'summary' => array('subtype' => 'other'), 'subtype' => array('other')
			),
			'ALARM_STATE' => array(
				'name' => new Trad( 'Alarme Etat', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'string')
			),
			'ALARM_MODE' => array(
				'name' => new Trad( 'Alarme mode', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'ALARM_ENABLE_STATE' => array(
				'name' => new Trad( 'Alarme Etat activée', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'ALARM_ARMED' => array(
				'name' => new Trad( 'Alarme armée', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_RELEASED' => array(
				'name' => new Trad( 'Alarme libérée', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'ALARM_SET_MODE' => array(
				'name' => new Trad( 'Alarme Mode', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Action', 'subtype' => array('other','select')
			),
			'FLOOD' => array(
				'name' => new Trad( 'Inondation', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SABOTAGE' => array(
				'name' => new Trad( 'Sabotage', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SHOCK' => array(
				'name' => new Trad( 'Choc', __FILE__), 'familyid' => 'Security', 'family' => new Trad( 'Sécurité', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'WEATHER_TEMPERATURE' => array(
				'name' => new Trad( 'Météo Température', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_HUMIDITY' => array(
				'name' => new Trad( 'Météo Humidité', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_PRESSURE' => array(
				'name' => new Trad( 'Météo Pression', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_SPEED' => array(
				'name' => new Trad( 'Météo vitesse du vent', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_WIND_DIRECTION' => array(
				'name' => new Trad( 'Météo direction du vent', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNSET' => array(
				'name' => new Trad( 'Météo coucher de soleil', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_SUNRISE' => array(
				'name' => new Trad( 'Météo lever de soleil', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MIN' => array(
				'name' => new Trad( 'Météo Température min', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX' => array(
				'name' => new Trad( 'Météo Température max', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION' => array(
				'name' => new Trad( 'Météo condition', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'WEATHER_CONDITION_ID' => array(
				'name' => new Trad( 'Météo condition (id)', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_1' => array(
				'name' => new Trad( 'Météo Température min j+1', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_1' => array(
				'name' => new Trad( 'Météo Température max j+1', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_1' => array(
				'name' => new Trad( 'Météo condition j+1', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_1' => array(
				'name' => new Trad( 'Météo condition (id) j+1', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_2' => array(
				'name' => new Trad( 'Météo Température min j+2', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_2' => array(
				'name' => new Trad( 'Météo condition j+1 max j+2', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_2' => array(
				'name' => new Trad( 'Météo condition j+2', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_2' => array(
				'name' => new Trad( 'Météo condition (id) j+2', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_3' => array(
				'name' => new Trad( 'Météo Température min j+3', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_3' => array(
				'name' => new Trad( 'Météo Température max j+3', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_3' => array(
				'name' => new Trad( 'Météo condition j+3', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_3' => array(
				'name' => new Trad( 'Météo condition (id) j+3', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'WEATHER_TEMPERATURE_MIN_4' => array(
				'name' => new Trad( 'Météo Température min j+4', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_TEMPERATURE_MAX_4' => array(
				'name' => new Trad( 'Météo Température max j+4', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WEATHER_CONDITION_4' => array(
				'name' => new Trad( 'Météo condition j+4', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('string'), 'calcul' => 'text'
			),
			'WEATHER_CONDITION_ID_4' => array(
				'name' => new Trad( 'Météo condition (id) j+4', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'text'
			),
			'RAIN_CURRENT' => array(
				'name' => new Trad( 'Pluie (mm/h)', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'RAIN_TOTAL' => array(
				'name' => new Trad( 'Pluie (accumulation)', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_SPEED' => array(
				'name' => new Trad( 'Vent (vitesse)', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WIND_DIRECTION' => array(
				'name' => new Trad( 'Vent (direction)', __FILE__), 'familyid' => 'Weather', 'family' => new Trad( 'Météo', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'POWER' => array(
				'name' => new Trad( 'Puissance Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'CONSUMPTION' => array(
				'name' => new Trad( 'Consommation Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'DAILY_CONSUMPTION' => array(
				'name' => new Trad( 'Consommation Electrique Journalière', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'PRODUCTION' => array(
				'name' => new Trad( 'Production Electrique', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'DAILY_PRODUCTION' => array(
				'name' => new Trad( 'Production Electrique Journalière', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric')
			),
			'VOLTAGE' => array(
				'name' => new Trad( 'Tension', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'REBOOT' => array(
				'name' => new Trad( 'Redémarrage', __FILE__), 'familyid' => 'Electricity', 'family' => new Trad( 'Electricité', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TEMPERATURE' => array(
				'name' => new Trad( 'Température', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'AIR_QUALITY' => array(
				'name' => new Trad( 'Qualité de l\'air', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BRIGHTNESS' => array(
				'name' => new Trad( 'Luminosité', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESENCE' => array(
				'name' => new Trad( 'Présence', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'SMOKE' => array(
				'name' => new Trad( 'Détection de fumée', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'HUMIDITY' => array(
				'name' => new Trad( 'Humidité', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'UV' => array(
				'name' => new Trad( 'UV', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO2' => array(
				'name' => new Trad( 'CO2 (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'CO' => array(
				'name' => new Trad( 'CO (ppm)', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'NOISE' => array(
				'name' => new Trad( 'Son (dB)', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'PRESSURE' => array(
				'name' => new Trad( 'Pression', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'WATER_LEAK' => array(
				'name' => new Trad( 'Fuite d\'eau', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info'
			),
			'FILTER_CLEAN_STATE' => array(
				'name' => new Trad( 'Etat du filtre', __FILE__), 'familyid' => 'Environment', 'family' => new Trad( 'Environnement', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'DEPTH' => array(
				'name' => new Trad( 'Profondeur', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DISTANCE' => array(
				'name' => new Trad( 'Distance', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BUTTON' => array(
				'name' => new Trad( 'Bouton', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric')
			),
			'GENERIC_INFO' => array(
				'name' => ' ' . new Trad( 'Générique', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'Info'
			),
			'GENERIC_ACTION' => array(
				'name' => ' ' . new Trad( 'Générique', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'Action'
			),
			'DONT' => array(
				'name' => new Trad( 'Ne pas tenir compte de cette commande', __FILE__), 'familyid' => 'Generic', 'family' => new Trad( 'Generic', __FILE__),
				'type' => 'All'
			),
			'BATTERY' => array(
				'name' => new Trad( 'Batterie', __FILE__), 'familyid' => 'Battery', 'family' => new Trad( 'Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'BATTERY_CHARGING' => array(
				'name' => new Trad( 'Batterie en charge', __FILE__), 'familyid' => 'Battery', 'family' => new Trad( 'Batterie', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'VOLUME' => array(
				'name' => new Trad( 'Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'MEDIA_STATUS' => array(
				'name' => new Trad( 'Status', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ALBUM' => array(
				'name' => new Trad( 'Album', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_ARTIST' => array(
				'name' => new Trad( 'Artiste', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_TITLE' => array(
				'name' => new Trad( 'Titre', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'MEDIA_POWER' => array(
				'name' => new Trad( 'Power', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('string')
			),
			'SET_VOLUME' => array(
				'name' => new Trad( 'Volume', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'CHANNEL' => array(
				'name' => new Trad( 'Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric', 'string'), 'calcul' => 'text'
			),
			'SET_CHANNEL' => array(
				'name' => new Trad( 'Chaine', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other', 'slider')
			),
			'MEDIA_PAUSE' => array(
				'name' => new Trad( 'Pause', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_RESUME' => array(
				'name' => new Trad( 'Lecture', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STOP' => array(
				'name' => new Trad( 'Stop', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_NEXT' => array(
				'name' => new Trad( 'Suivant', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_PREVIOUS' => array(
				'name' => new Trad( 'Précedent', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_ON' => array(
				'name' => new Trad( 'On', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_OFF' => array(
				'name' => new Trad( 'Off', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_STATE' => array(
				'name' => new Trad( 'Etat', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'MEDIA_MUTE' => array(
				'name' => new Trad( 'Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'MEDIA_UNMUTE' => array(
				'name' => new Trad( 'Non Muet', __FILE__), 'familyid' => 'Multimedia', 'family' => new Trad( 'Multimédia', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'FAN_SPEED' => array(
				'name' => new Trad( 'Vitesse ventilateur', __FILE__), 'familyid' => 'Fan', 'family' => new Trad( 'Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'FAN_SPEED_STATE' => array(
				'name' => new Trad( 'Vitesse ventilateur Etat', __FILE__), 'familyid' => 'Fan', 'family' => new Trad( 'Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'ROTATION' => array(
				'name' => new Trad( 'Rotation', __FILE__), 'familyid' => 'Fan', 'family' => new Trad( 'Ventilateur', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'ROTATION_STATE' => array(
				'name' => new Trad( 'Rotation Etat', __FILE__), 'familyid' => 'Fan', 'family' => new Trad( 'Ventilateur', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'DOCK' => array(
				'name' => new Trad( 'Retour base', __FILE__), 'familyid' => 'Robot', 'family' => new Trad( 'Robot', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'DOCK_STATE' => array(
				'name' => new Trad( 'Base Etat', __FILE__), 'familyid' => 'Robot', 'family' => new Trad( 'Robot', __FILE__),
				'type' => 'Info', 'subtype' => array('binary')
			),
			'TIMER' => array(
				'name' => new Trad( 'Minuteur Etat', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('numeric'), 'calcul' => 'avg'
			),
			'SET_TIMER' => array(
				'name' => new Trad( 'Minuteur', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('slider')
			),
			'TIMER_STATE' => array(
				'name' => new Trad( 'Minuteur Etat (pause ou non)', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Info', 'subtype' => array('binary', 'numeric'), 'calcul' => 'avg'
			),
			'TIMER_PAUSE' => array(
				'name' => new Trad( 'Minuteur pause', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
			'TIMER_RESUME' => array(
				'name' => new Trad( 'Minuteur reprendre', __FILE__), 'familyid' => 'Other', 'family' => new Trad( 'Autre', __FILE__),
				'type' => 'Action', 'subtype' => array('other')
			),
		),
		'type' => array(
			'info' => array(
				'name' => new Trad( 'Info', __FILE__),
				'subtype' => array(
					'numeric' => array(
						'name' => new Trad( 'Numérique', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => true),
						'isHistorized' => array('visible' => true, 'timelineOnly' => false, 'canBeSmooth' => true),
						'display' => array(
							'invertBinary' => array('visible' => true, 'parentVisible' => true),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'binary' => array(
						'name' => new Trad( 'Binaire', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => true, 'timelineOnly' => false, 'canBeSmooth' => false),
						'display' => array(
							'invertBinary' => array('visible' => true, 'parentVisible' => true),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'string' => array(
						'name' => new Trad( 'Autre', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => true),
						'isHistorized' => array('visible' => true, 'timelineOnly' => true, 'canBeSmooth' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
				),
			),
			'action' => array(
				'name' => new Trad( 'Action', __FILE__),
				'subtype' => array(
					'other' => array(
						'name' => new Trad( 'Défaut', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'slider' => array(
						'name' => new Trad( 'Curseur', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => true),
							'maxValue' => array('visible' => true),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'message' => array(
						'name' => new Trad( 'Message', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'color' => array(
						'name' => new Trad( 'Couleur', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => false)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
					'select' => array(
						'name' => new Trad( 'Liste', __FILE__),
						'configuration' => array(
							'minValue' => array('visible' => false),
							'maxValue' => array('visible' => false),
							'listValue' => array('visible' => true)
						),
						'unite' => array('visible' => false),
						'isHistorized' => array('visible' => false),
						'display' => array(
							'invertBinary' => array('visible' => false),
							'icon' => array('visible' => true, 'parentVisible' => true),
						),
					),
				),
			),
		),
	),
);
$GLOBALS['JEEDOM_SCLOG_TEXT'] = array(
	'startManual' 			=> array('txt' => new Trad( 'Scénario lancé manuellement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnEvent'		=> array('txt' => new Trad( 'Scénario exécuté automatiquement sur événement venant de :', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),
	'startOnEvent'			=> array('txt' => new Trad( 'Scénario exécuté sur événement', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startAutoOnShedule'	=> array('txt' => new Trad( 'Scénario exécuté automatiquement sur programmation', __FILE__), 'replace' => '<label class="success">::</label>'),
	'finishOk' 				=> array('txt' => new Trad( 'Fin correcte du scénario', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduledOn'			=> array('txt' => ' ' . new Trad( 'programmée à :', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),
	'startByScenario'		=> array('txt' => new Trad( 'Lancement provoqué par le scénario  :', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),
	'startCausedBy'			=> array('txt' => new Trad( 'Lancement provoqué', __FILE__), 'replace' => '<label class="success">::</label>'),
	'startSubTask' 			=> array('txt' => new Trad( '************Lancement sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'endSubTask' 			=> array('txt' => new Trad( '************FIN sous tâche**************', __FILE__), 'replace' => '<label class="success">::</label>'),
	'sheduleNow'			=> array('txt' => ' ' . new Trad( 'lancement immédiat', __FILE__) . ' ', 'replace' => '<label class="success">::</label>'),

	'execAction'			=> array('txt' => '- ' . new Trad( 'Exécution du sous-élément de type [action] :', __FILE__) . ' ', 'replace' => '<label class="info">::</label>'),
	'execCondition'			=> array('txt' => '- ' . new Trad( 'Exécution du sous-élément de type [condition] :', __FILE__) . ' ', 'replace' => '<label class="info">::</label>'),

	'execCmd'				=> array('txt' => new Trad( 'Exécution de la commande', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'execCode'				=> array('txt' => new Trad( 'Exécution d\'un bloc code', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'launchScenario'		=> array('txt' => new Trad( 'Lancement du scénario :', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'launchScenarioSync'	=> array('txt' => new Trad( 'Lancement du scénario en mode synchrone', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'start'					=> array('txt' => '-- ' . new Trad( 'Début :', __FILE__), 'replace' => '<strong>::</strong>'),
	'task'					=> array('txt' => new Trad( 'Tâche :', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'event'					=> array('txt' => new Trad( 'Changement de', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),
	'setTag'				=> array('txt' => new Trad( 'Mise à jour du tag', __FILE__) . ' ', 'replace' => '<label class="warning">::</label>'),

	'stopTimeout'			=> array('txt' => new Trad( 'Arrêt du scénario car il a dépassé son temps de timeout :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'disableNoSubtask'		=> array('txt' => new Trad( 'Scénario désactivé non lancement de la sous tâche', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'disableEqNoExecCmd'	=> array('txt' => new Trad( 'Equipement désactivé - impossible d\'exécuter la commande :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'toStartUnfound'		=> array('txt' => new Trad( 'Eléments à lancer non trouvé', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'invalideShedule'		=> array('txt' => new Trad( ', heure programmée invalide :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'noCmdFoundFor'			=> array('txt' => new Trad( '[Erreur] Aucune commande trouvée pour', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'unfoundCmd'			=> array('txt' => new Trad( 'Commande introuvable', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundCmdCheckId'		=> array('txt' => new Trad( 'Commande introuvable - Vérifiez l\'id', __FILE__), 'replace' => '<label class="danger">::</label>'),
	'unfoundEq'				=> array('txt' => new Trad( 'Action sur l\'équipement impossible. Equipement introuvable - Vérifiez l\'id :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'unfoundScenario'		=> array('txt' => new Trad( 'Action sur scénario impossible. Scénario introuvable - Vérifiez l\'id :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'disableScenario'		=> array('txt' => new Trad( 'Impossible d\'exécuter le scénario :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'invalidExpr'			=> array('txt' => new Trad( 'Expression non valide :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
	'invalidDuration'		=> array('txt' => new Trad( 'Aucune durée trouvée pour l\'action sleep ou la durée n\'est pas valide :', __FILE__) . ' ', 'replace' => '<label class="danger">::</label>'),
);
