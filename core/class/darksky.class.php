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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class darksky extends eqLogic {

    public static $_widgetPossibility = array('custom' => true);

    public static function cron5($_eqLogic_id = null) {
        if ($_eqLogic_id == null) {
            $eqLogics = self::byType('darksky', true);
        } else {
            $eqLogics = array(self::byId($_eqLogic_id));
        }
        foreach ($eqLogics as $darksky) {
            if (null !== ($darksky->getConfiguration('geoloc', ''))) {
                $darksky->getInformations('5m');
            } else {
                log::add('darksky', 'error', 'geoloc non saisie');
            }
        }
    }

    public static function cronHourly($_eqLogic_id = null) {
        if ($_eqLogic_id == null) {
            $eqLogics = self::byType('darksky', true);
        } else {
            $eqLogics = array(self::byId($_eqLogic_id));
        }
        foreach ($eqLogics as $darksky) {
            if (null !== ($darksky->getConfiguration('geoloc', ''))) {
                $darksky->getInformations('hourly');
            } else {
                log::add('darksky', 'error', 'geoloc non saisie');
            }
        }
        if (date('G')  == 3) {
            foreach ($eqLogics as $darksky) {
                if (null !== ($darksky->getConfiguration('geoloc', ''))) {
                    $darksky->getInformations('daily');
                } else {
                    log::add('darksky', 'error', 'geoloc non saisie');
                }
            }
        }
    }

    public static function start() {
        foreach (self::byType('darksky', true) as $darksky) {
            if (null !== ($darksky->getConfiguration('geoloc', ''))) {
                $darksky->getInformations('daily');
                $darksky->getInformations('hourly');
                $darksky->getInformations('5m');
            } else {
                log::add('darksky', 'error', 'geoloc non saisie');
            }
        }
    }

    public function preUpdate() {
        if ($this->getConfiguration('geoloc') == '') {
            throw new Exception(__('La géolocalisation ne peut etre vide',__FILE__));
        }
        if ($this->getConfiguration('apikey') == '') {
            throw new Exception(__('La clef API ne peut etre vide',__FILE__));
        }
    }

    public function postUpdate() {
        //info actual
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensity');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensity');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( 'mm/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipType');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipType');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperature');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperature');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperature');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperature');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPoint');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPoint');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidity');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidity');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeed');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeed');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCover');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCover');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressure');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressure');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozone');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozone');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        //info H+1
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensityh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( 'mm/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbabilityh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipTypeh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPointh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidityh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeedh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearingh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0h1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCoverh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressureh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozoneh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV H+1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndexh1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h1');
        $darkskyCmd->save();

        //status H+2
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensityh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbabilityh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipTypeh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPointh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidityh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeedh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearingh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0h2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCoverh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressureh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozoneh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV H+2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndexh2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h2');
        $darkskyCmd->save();

        //status H+3
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensityh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( 'mm/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbabilityh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipTypeh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPointh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidityh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeedh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearingh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0h3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCoverh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressureh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozoneh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV H+3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndexh3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h3');
        $darkskyCmd->save();

        //status H+4
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensityh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( 'mm/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbabilityh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipTypeh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPointh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidityh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeedh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearingh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0h4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCoverh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressureh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozoneh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV H+4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndexh4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h4');
        $darkskyCmd->save();

        //status H+5
        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipIntensityh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Intensité de Précipitation H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipIntensityh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( 'mkm/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbabilityh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Probabilité de Précipitation H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbabilityh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipTypeh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Type de Précipitation H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipTypeh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Apparente H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'dewPointh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Point de Rosée H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('dewPointh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'humidityh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Humidité H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('humidityh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeedh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Vitesse du Vent H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windSpeedh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( 'km/h' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearingh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Direction du Vent H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearingh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'windBearing0h5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Provenance du Vent H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('windBearing0h5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '°' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'cloudCoverh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Couverture Nuageuse H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('cloudCoverh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( '%' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'pressureh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Pression H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('pressureh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( 'hPa' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'ozoneh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Ozone H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('ozoneh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->setUnite( 'DU' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndexh5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV H+5', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndexh5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','h5');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'sunriseTime');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Lever du Soleil', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('sunriseTime');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'sunsetTime');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Coucher du Soleil', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('sunsetTime');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryweek');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition semaine', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryweek');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconweek');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone semaine', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconweek');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summaryhours');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition prochaines heures', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summaryhours');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'iconhours');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone prochaines heures', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('iconhours');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureMin');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum Apparente', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureMin');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'apparentTemperatureMax');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum Apparente', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('apparentTemperatureMax');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMin_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMax_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Propbalitié Pluie Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_1');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone Jour', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon_1');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMin_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMax_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV Jour +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Propbalitié Pluie Jour +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition Jour +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_2');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone Jour +1', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon_2');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMin_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMax_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV Jour +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Propbalitié Pluie Jour +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition Jour +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_3');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone Jour +2', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon_3');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMin_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMax_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV Jour +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Propbalitié Pluie Jour +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition Jour +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_4');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone Jour +3', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon_4');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMin_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Minimum +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMin_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'temperatureMax_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Température Maximum +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('temperatureMax_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->setUnite( '°C' );
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'uvIndex_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Index UV Jour +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('uvIndex_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'precipProbability_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Propbalitié Pluie Jour +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('precipProbability_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('numeric');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'summary_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Condition Jour +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('summary_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'icon_5');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Icone Jour +4', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('icon_5');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','daily');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'alert');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Alertes', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('alert');
            $darkskyCmd->setType('info');
            $darkskyCmd->setSubType('string');
        }
        $darkskyCmd->setConfiguration('category','actual');
        $darkskyCmd->save();

        $darkskyCmd = darkskyCmd::byEqLogicIdAndLogicalId($this->getId(),'refresh');
        if (!is_object($darkskyCmd)) {
            $darkskyCmd = new darkskyCmd();
            $darkskyCmd->setName(__('Rafraichir', __FILE__));
            $darkskyCmd->setEqLogic_id($this->getId());
            $darkskyCmd->setLogicalId('refresh');
            $darkskyCmd->setType('action');
            $darkskyCmd->setSubType('other');
            $darkskyCmd->save();
        }
        if (null !== ($this->getConfiguration('geoloc', '')) && $this->getConfiguration('geoloc', '') != 'none') {
            darksky::getInformations();
        } else {
            log::add('darksky', 'error', 'geoloc non saisie');
        }
    }


    public function getInformations($frequence = 'all') {
        if ($this->getConfiguration('geoloc', 'none') == 'none') {
            return;
        }
        $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
        $apikey = $this->getConfiguration('apikey', '');
        $lang = explode('_',config::byKey('language'));
        $url = 'https://api.darksky.net/forecast/' . $apikey .'/' . $geolocval . '?units=ca&lang=' . $lang[0] . '&solar=1';
        log::add('darksky', 'debug', $url);
        $json_string = file_get_contents($url);
        if ($json_string === false) {
            log::add('darksky', 'debug', 'Problème de chargement API');
            return;
        }
        $parsed_json = json_decode($json_string, true);
        //log::add('darksky', 'debug', print_r($json_string, true));
        //log::add('darksky', 'debug', print_r($parsed_json, true));
        //log::add('darksky', 'debug', print_r($parsed_json['currently'], true));
        if ($frequence == 'daily' || $frequence == 'all') {
            foreach ($parsed_json['daily']['data'][0] as $key => $value) {
                if ($key == 'sunsetTime' || $key == 'sunriseTime') {
                    $value = date('Hi',$value);
                    $this->checkAndUpdateCmd($key, $value);
                }
            }
        }

        if ($frequence == 'hourly' || $frequence == 'all') {
            foreach ($parsed_json['daily']['data'][0] as $key => $value) {
                if ($key == 'apparentTemperatureMax' || $key == 'apparentTemperatureMin' || $key == 'temperatureMax' || $key == 'temperatureMin') {
                    $this->checkAndUpdateCmd($key, $value);
                }
            }
            //daily
            $i = 0;
            while ($i < 5) {
                $j = $i +1;
                foreach ($parsed_json['daily']['data'][$i] as $key => $value) {
                    if ($key == 'temperatureMax' || $key == 'temperatureMin' || $key == 'summary' || $key == 'icon' || $key == 'uvIndex' || $key == 'precipProbability') {
                        $this->checkAndUpdateCmd($key . '_' . $j, $value);
                    }
                }
                $i++;
            }
        }

        if ($frequence == '5m' || $frequence == 'all') {
            //hourly
            $i = 1;
            while ($i < 6) {
                foreach ($parsed_json['hourly']['data'][$i] as $key => $value) {
                    if ($key != 'solar') {
                        if ($key == 'windBearing') {
                            $this->checkAndUpdateCmd('windBearing0h' . $i, $value);
                            if ($value > 179) {
                                $value = $value -180;
                            } else {
                                $value = $value + 180;
                            }
                        }
                        if ($key == 'humidity' || $key == 'cloudCover') {
                            $value = $value * 100;
                        }
                        $this->checkAndUpdateCmd($key . 'h' . $i, $value);
                    }
                }
                $i++;
            }
            foreach ($parsed_json['currently'] as $key => $value) {
                //log::add('darksky', 'debug', $key . ' ' . $value);
                if ($key != 'time' && $key != 'solar') {
                    if ($key == 'windBearing') {
                        $this->checkAndUpdateCmd('windBearing0', $value);
                        if ($value > 179) {
                            $value = $value -180;
                        } else {
                            $value = $value + 180;
                        }
                    }
                    if ($key == 'humidity' || $key == 'cloudCover') {
                        $value = $value * 100;
                    }
                    $this->checkAndUpdateCmd($key, $value);
                }
            }

            if (!empty($parsed_json['alert'])) {
                $title = '';
                foreach ($parsed_json['alert'] as $key => $value) {
                    if ($key == 'title') {
                        $title .= ', ' . $value;
                    }
                }
                $this->checkAndUpdateCmd('alert', $value);
            }
        }

        if ($frequence == 'hourly' || $frequence == 'all') {
            $this->checkAndUpdateCmd('summaryhours', $parsed_json['hourly']['summary']);
            $this->checkAndUpdateCmd('iconhours', $parsed_json['hourly']['icon']);
            $this->checkAndUpdateCmd('summaryweek', $parsed_json['daily']['summary']);
            $this->checkAndUpdateCmd('iconweek', $parsed_json['daily']['icon']);
        }

        $this->refreshWidget();
    }

    public function loadingData($eqlogic) {
        $return = array();
        $darksky = darksky::byId($eqlogic);
        $geolocval = geotravCmd::byEqLogicIdAndLogicalId($this->getConfiguration('geoloc'),'location:coordinate')->execCmd();
        $apikey = $darksky->getConfiguration('apikey', '');
        $lang = explode('_',config::byKey('language'));
        $url = 'https://api.darksky.net/forecast/' . $apikey .'/' . trim($geolocval) . '?units=ca&lang=' . $lang[0] . '&solar=1';
        log::add('darksky', 'debug', $url);
        $json_string = file_get_contents($url);
        $parsed_json = json_decode($json_string, true);
        //log::add('darksky', 'debug', print_r($json_string, true));
        //log::add('darksky', 'debug', print_r($parsed_json, true));
        //log::add('darksky', 'debug', print_r($parsed_json['currently'], true));

        foreach ($parsed_json['hourly']['data'] as $value) {
            $return['previsions']['time'][] = $value['time'] . '000';
            $return['previsions']['temperature'][] = $value['temperature'];
            $return['previsions']['precipIntensity'][] = $value['precipIntensity'];
            $return['previsions']['windSpeed'][] = $value['windSpeed'];
            $return['previsions']['pressure'][] = $value['pressure'];
            $return['previsions']['uvIndex'][] = $value['uvIndex'];
        }

        $return['status'] = array(
            'summary' => $parsed_json['currently']['summary'],
            'icon' => $parsed_json['currently']['icon'],
            'temperature' => $parsed_json['currently']['temperature'] . '°C',
            'apparentTemperature' => '(' . $parsed_json['currently']['apparentTemperature'] . '°C)',
            'humidity' => $parsed_json['currently']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['currently']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['currently']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['currently']['windBearing'] > 179 ? $parsed_json['currently']['windBearing'] -180 : $windBearing_status = $parsed_json['currently']['windBearing'] + 180,
            'cloudCover' => $parsed_json['currently']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['currently']['pressure'] . 'hPa',
            'ozone' => $parsed_json['currently']['ozone'] . 'DU',
            'uvIndex' => $parsed_json['currently']['uvIndex'],
        );

        $return['hour'] = array(
            'summary' => $parsed_json['hourly']['data']['1']['summary'],
            'icon' => $parsed_json['hourly']['data']['1']['icon'],
            'temperature' => $parsed_json['hourly']['data']['1']['temperature'] . '°C',
            'apparentTemperature' => '(' . $parsed_json['hourly']['data']['1']['apparentTemperature'] . '°C)',
            'humidity' => $parsed_json['hourly']['data']['1']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['hourly']['data']['1']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['hourly']['data']['1']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['hourly']['data']['1']['windBearing'] > 179 ? $parsed_json['hourly']['data']['0']['windBearing'] -180 : $windBearing_status = $parsed_json['hourly']['data']['0']['windBearing'] + 180,
            'cloudCover' => $parsed_json['hourly']['data']['1']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['hourly']['data']['1']['pressure'] . 'hPa',
            'ozone' => $parsed_json['hourly']['data']['1']['ozone'] . 'DU',
            'uvIndex' => $parsed_json['hourly']['data']['1']['uvIndex'],
        );

        $return['day0'] = array(
            'summary' => $parsed_json['daily']['data']['0']['summary'],
            'icon' => $parsed_json['daily']['data']['0']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['0']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['0']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['0']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['0']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['0']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['0']['windBearing'] > 179 ? $parsed_json['daily']['data']['0']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['0']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['0']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['0']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['0']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['0']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['0']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['0']['uvIndex'],
        );

        $return['day1'] = array(
            'summary' => $parsed_json['daily']['data']['1']['summary'],
            'icon' => $parsed_json['daily']['data']['1']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['1']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['1']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['1']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['1']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['1']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['1']['windBearing'] > 179 ? $parsed_json['daily']['data']['1']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['1']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['1']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['1']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['1']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['1']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['1']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['1']['uvIndex'],
        );

        $return['day2'] = array(
            'summary' => $parsed_json['daily']['data']['2']['summary'],
            'icon' => $parsed_json['daily']['data']['2']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['2']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['2']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['2']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['2']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['2']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['2']['windBearing'] > 179 ? $parsed_json['daily']['data']['2']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['2']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['2']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['2']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['2']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['2']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['2']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['2']['uvIndex'],
        );

        $return['day3'] = array(
            'summary' => $parsed_json['daily']['data']['3']['summary'],
            'icon' => $parsed_json['daily']['data']['3']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['3']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['3']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['3']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['3']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['3']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['3']['windBearing'] > 179 ? $parsed_json['daily']['data']['3']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['3']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['3']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['3']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['3']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['3']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['3']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['3']['uvIndex'],
        );

        $return['day4'] = array(
            'summary' => $parsed_json['daily']['data']['4']['summary'],
            'icon' => $parsed_json['daily']['data']['4']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['4']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['4']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['4']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['4']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['4']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['4']['windBearing'] > 179 ? $parsed_json['daily']['data']['4']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['4']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['4']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['4']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['4']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['4']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['4']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['4']['uvIndex'],
        );

        $return['day5'] = array(
            'summary' => $parsed_json['daily']['data']['5']['summary'],
            'icon' => $parsed_json['daily']['data']['5']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['5']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['5']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['5']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['5']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['5']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['5']['windBearing'] > 179 ? $parsed_json['daily']['data']['5']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['5']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['5']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['5']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['5']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['5']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['5']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['5']['uvIndex'],
        );

        $return['day6'] = array(
            'summary' => $parsed_json['daily']['data']['6']['summary'],
            'icon' => $parsed_json['daily']['data']['6']['icon'],
            'temperatureMin' => $parsed_json['daily']['data']['6']['temperatureMin'] . '°C',
            'temperatureMax' => $parsed_json['daily']['data']['6']['temperatureMax'] . '°C',
            'humidity' => $parsed_json['daily']['data']['6']['humidity']*100 . '%',
            'precipProbability' => $parsed_json['daily']['data']['6']['precipProbability']*100 . '%',
            'windSpeed' => $parsed_json['daily']['data']['6']['windSpeed'] . 'km/h',
            'windBearing' => $parsed_json['daily']['data']['6']['windBearing'] > 179 ? $parsed_json['daily']['data']['6']['windBearing'] -180 : $windBearing_status = $parsed_json['daily']['data']['6']['windBearing'] + 180,
            'cloudCover' => $parsed_json['daily']['data']['6']['cloudCover']*100 . '%',
            'pressure' => $parsed_json['daily']['data']['6']['pressure'] . 'hPa',
            'ozone' => $parsed_json['daily']['data']['6']['ozone'] . 'DU',
            'sunriseTime' => date('H:i',$parsed_json['daily']['data']['6']['sunriseTime']),
            'sunsetTime' => date('H:i',$parsed_json['daily']['data']['6']['sunsetTime']),
            'uvIndex' => $parsed_json['daily']['data']['6']['uvIndex'],
        );

        return $return;
    }

    public function getGeoloc($_infos = '') {
        $return = array();
        foreach (eqLogic::byType('geoloc') as $geoloc) {
            foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
                if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
                    $return[$geoinfo->getId()] = array(
                        'value' => $geoinfo->getName(),
                    );
                }
            }
        }
        return $return;
    }

    public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);
        if ($this->getDisplay('hideOn' . $version) == 1) {
            return '';
        }

        $html_forecast = '';

        if ($_version != 'mobile' || $this->getConfiguration('fullMobileDisplay', 0) == 1) {
            $forcast_template = getTemplate('core', $version, 'forecast', 'darksky');
            for ($i = 0; $i < 5; $i++) {
                $replace['#day#'] = date_fr(date('l', strtotime('+' . $i . ' days')));

                $j = $i + 1;
                $temperature_min = $this->getCmd(null, 'temperatureMin_' . $j);
                $replace['#low_temperature#'] = is_object($temperature_min) ? round($temperature_min->execCmd()) : '';

                $temperature_max = $this->getCmd(null, 'temperatureMax_' . $j);
                $replace['#hight_temperature#'] = is_object($temperature_max) ? round($temperature_max->execCmd()) : '';
                $replace['#tempid#'] = is_object($temperature_max) ? $temperature_max->getId() : '';

                $icone = $this->getCmd(null, 'icon_' . $j);
                $replace['#icone#'] = is_object($icone) ? $icone->getId() : '';

                $html_forecast .= template_replace($replace, $forcast_template);
            }
        }

        $replace['#forecast#'] = $html_forecast;
        $replace['#city#'] = $this->getName();

        $temperature = $this->getCmd(null, 'temperature');
        $replace['#temperature#'] = is_object($temperature) ? round($temperature->execCmd()) : '';
        $replace['#tempid#'] = is_object($temperature) ? $temperature->getId() : '';

        $conditionday = $this->getCmd(null, 'summaryhours');
        $replace['#conditionday#'] = is_object($conditionday) ? $conditionday->execCmd() : '';
        $replace['#conditiondayid#'] = is_object($conditionday) ? $conditionday->getId() : '';

        $humidity = $this->getCmd(null, 'humidity');
        $replace['#humidity#'] = is_object($humidity) ? $humidity->execCmd() : '';

        $uvindex = $this->getCmd(null, 'uvIndex');
        $replace['#uvi#'] = is_object($uvindex) ? $uvindex->execCmd() : '';

        $pressure = $this->getCmd(null, 'pressure');
        $replace['#pressure#'] = is_object($pressure) ? $pressure->execCmd() : '';
        $replace['#pressureid#'] = is_object($pressure) ? $pressure->getId() : '';

        $wind_speed = $this->getCmd(null, 'windSpeed');
        $replace['#windspeed#'] = is_object($wind_speed) ? $wind_speed->execCmd() : '';
        $replace['#windid#'] = is_object($wind_speed) ? $wind_speed->getId() : '';

        $sunrise = $this->getCmd(null, 'sunriseTime');
        $replace['#sunrise#'] = is_object($sunrise) ? substr_replace($sunrise->execCmd(),':',-2,0) : '';
        $replace['#sunriseid#'] = is_object($sunrise) ? $sunrise->getId() : '';

        $sunset = $this->getCmd(null, 'sunsetTime');
        $replace['#sunset#'] = is_object($sunset) ? substr_replace($sunset->execCmd(),':',-2,0) : '';
        $replace['#sunsetid#'] = is_object($sunset) ? $sunset->getId() : '';

        $wind_direction = $this->getCmd(null, 'windBearing');
        $replace['#wind_direction#'] = is_object($wind_direction) ? $wind_direction->execCmd() : 0;

        $refresh = $this->getCmd(null, 'refresh');
        $replace['#refresh_id#'] = is_object($refresh) ? $refresh->getId() : '';

        $condition = $this->getCmd(null, 'summary');
        $icone = $this->getCmd(null, 'icon');
        if (is_object($condition)) {
            $replace['#iconeid#'] = $icone->getId();
            $replace['#condition#'] = $condition->execCmd();
            $replace['#conditionid#'] = $condition->getId();
            $replace['#collectDate#'] = $condition->getCollectDate();
        } else {
            $replace['#icone#'] = '';
            $replace['#condition#'] = '';
            $replace['#collectDate#'] = '';
        }

        $icone = $this->getCmd(null, 'icon');
        $replace['#icone#'] = is_object($icone) ? $icone->execCmd() : '';

        $icone1 = $this->getCmd(null, 'icon_1');
        $replace['#icone1#'] = is_object($icone1) ? $icone1->execCmd() : '';
        $replace['#iconeid1#'] = is_object($icone1) ? $icone1->getId() : '';

        $icone2 = $this->getCmd(null, 'icon_2');
        $replace['#icone2#'] = is_object($icone2) ? $icone2->execCmd() : '';
        $replace['#iconeid2#'] = is_object($icone2) ? $icone2->getId() : '';

        $icone3 = $this->getCmd(null, 'icon_3');
        $replace['#icone3#'] = is_object($icone3) ? $icone3->execCmd() : '';
        $replace['#iconeid3#'] = is_object($icone3) ? $icone3->getId() : '';

        $icone4 = $this->getCmd(null, 'icon_4');
        $replace['#icone4#'] = is_object($icone4) ? $icone4->execCmd() : '';
        $replace['#iconeid4#'] = is_object($icone4) ? $icone4->getId() : '';

        $icone5 = $this->getCmd(null, 'icon_5');
        $replace['#icone5#'] = is_object($icone5) ? $icone5->execCmd() : '';
        $replace['#iconeid5#'] = is_object($icone5) ? $icone5->getId() : '';

        $parameters = $this->getDisplay('parameters');
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $replace['#' . $key . '#'] = $value;
            }
        }

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'current', 'darksky')));
    }

}

class darkskyCmd extends cmd {

    public function execute($_options = null) {
        if ($this->getLogicalId() == 'refresh') {
            $eqLogic = $this->getEqLogic();
            $eqLogic->getInformations();
        } else {
            return $this->getConfiguration('value');
        }
    }

}

?>
