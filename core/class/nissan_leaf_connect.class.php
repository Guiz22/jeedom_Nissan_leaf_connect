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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rdparty/nissan-connect-php/NissanConnect.class.php';

class nissan_leaf_connect extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */
      public static function cron15() {
          log::add('nissan_leaf_connect', 'debug', 'cron_chauf_on');
          $eqLogics = eqLogic::byType('nissan_leaf_connect');
             foreach ($eqLogics as $eqLogic) {
                 $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                    $eqLogic->getConfiguration('password'),
                                                    'Europe/Paris', 
                                                    NissanConnect::COUNTRY_EU, 
                                                    NissanConnect::ENCRYPTION_OPTION_MCRYPT);

                 $nissanConnect->debug = True;

                 $nissanConnect->maxWaitTime = 290;
                 $result = $nissanConnect->getStatus();
                 $debug_printr = print_r($result, true);
                 log::add('nissan_leaf_connect', 'debug', 'after get2'.$debug_printr );
                 $cmd = $eqLogic->getCmd('info', 'BatteryRemainingAmount');
                 if (is_object($cmd)) {
                     if ( isset ( $result->BatteryRemainingAmount )) {
                     $cmd->setCollectDate('');
                     $cmd->event($result->BatteryRemainingAmount / 10 );
		     }   
                 }
                 $cmd = $eqLogic->getCmd('info', 'LastUpdated');
                 if (is_object($cmd)) {
                     if ( isset ( $result->LastUpdated )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->LastUpdated );
		    }
                 }
                 $cmd = $eqLogic->getCmd('info', 'percentRemaining');
                 if (is_object($cmd)) {
                     if ( isset ( $result->BatteryCapacity )) {
                         $cmd->setCollectDate('');
                         $cmd->event( $result->BatteryRemainingAmount / $result->BatteryCapacity * 100 );
		    }
                 }
                 $cmd = $eqLogic->getCmd('info', 'BatteryCapacity');
                 if (is_object($cmd)) {
                     if ( isset ( $result->BatteryCapacity )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->BatteryCapacity / 10 );
		    }
                 }
                 $cmd = $eqLogic->getCmd('info', 'Charging');
                 if (is_object($cmd)) {
                     if ( isset ( $result->Charging )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->Charging );
		     }
                 }
                 $cmd = $eqLogic->getCmd('info', 'ChargingMode');
                 if (is_object($cmd)) {
                     if ( isset ( $result->ChargingMode )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->ChargingMode );
		     }
                 }
                 $cmd = $eqLogic->getCmd('info', 'CruisingRangeAcOn');
                 if (is_object($cmd)) {
                     if ( isset ( $result->CruisingRangeAcOn )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->CruisingRangeAcOn );
		     }
                 }
                 $cmd = $eqLogic->getCmd('info', 'CruisingRangeAcOff');
                 if (is_object($cmd)) {
                     if ( isset ( $result->CruisingRangeAcOff )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->CruisingRangeAcOff );
		     }
                 }
                 $cmd = $eqLogic->getCmd('info', 'PluggedIn');
                 if (is_object($cmd)) {
                     if ( isset ( $result->PluggedIn )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->PluggedIn );
		         }
                 }
                 $cmd = $eqLogic->getCmd('info', 'BatteryRemainingAmountWH');
                 if (is_object($cmd)) {
                     if ( isset ( $result->BatteryRemainingAmountWH )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->BatteryRemainingAmountWH );
		         }
                 }
          } # end for eqLogic
      }

/*      public static function cronHourly() {
	

      }
*/
    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDayly() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
      log::add('nissan_leaf_connect', 'debug', 'preInsert');
    
        
    }

    public function postInsert() {
      log::add('nissan_leaf_connect', 'debug', 'postInsert');
        
    }

    public function preSave() {
      log::add('nissan_leaf_connect', 'debug', 'preSave');
        
    }

    public function postSave() {
      log::add('nissan_leaf_connect', 'debug', 'postSave');
        
    }

    public function preUpdate() {
      log::add('nissan_leaf_connect', 'debug', 'preUpdate');
        
    }

    public function postUpdate() {
      log::add('nissan_leaf_connect', 'debug', 'postUpdate');
      
      $new_cmd = $this->getCmd(null, 'startClimateControl');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Chauffage On', __FILE__));
      $new_cmd->setLogicalId('startClimateControl');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setSubType('other');
      $new_cmd->setType('action');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'stopClimateControl');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Chauffage Off', __FILE__));
      $new_cmd->setLogicalId('stopClimateControl');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setSubType('other');
      $new_cmd->setType('action');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'startCharge');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Charge ON', __FILE__));
      $new_cmd->setLogicalId('startCharge');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setSubType('other');
      $new_cmd->setType('action');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'PluggedIn');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Cable', __FILE__));
      $new_cmd->setLogicalId('PluggedIn');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('binary');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'ChargingMode');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Mode Charge', __FILE__));
      $new_cmd->setLogicalId('ChargingMode');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('string');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'Charging');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('En Charge', __FILE__));
      $new_cmd->setLogicalId('Charging');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('binary');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'RemoteACRunning');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Chauffage', __FILE__));
      $new_cmd->setLogicalId('RemoteACRunning');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('binary');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'CruisingRangeAcOn');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Auton. avec AC', __FILE__));
      $new_cmd->setLogicalId('CruisingRangeAcOn');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Km');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'CruisingRangeAcOff');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Auton. sans AC', __FILE__));
      $new_cmd->setLogicalId('CruisingRangeAcOff');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Km');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'percentRemaining');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Capacité', __FILE__));
      $new_cmd->setLogicalId('percentRemaining');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('%');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'BatteryRemainingAmountWH');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Capacité WH', __FILE__));
      $new_cmd->setLogicalId('BatteryRemainingAmountWH');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Wh');
      $new_cmd->setIsVisible(0);
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'BatteryCapacity');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Capacité kWh', __FILE__));
      $new_cmd->setLogicalId('BatteryCapacity');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('kWh');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->setIsVisible(0);
      $new_cmd->save();
 
      $new_cmd = $this->getCmd(null, 'LastUpdated');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             }
      $new_cmd->setName(__('Mise a jour', __FILE__));
      $new_cmd->setLogicalId('LastUpdated');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('string');
      $new_cmd->save();
    }

    public function preRemove() {
      log::add('nissan_leaf_connect', 'debug', 'preRemove');
        
    }

    public function postRemove() {
      log::add('nissan_leaf_connect', 'debug', 'postRemove');
        
    }
    public static function dependancy_info() {
                $return = array();
                $return['log'] = 'nissan_leaf_connect_update';
                $return['progress_file'] = '/tmp/dependancy_nissan_leaf_connect_in_progress';
                if (extension_loaded('mcrypt')) {
                       $return['state'] = 'ok';
		}
                else {
                    $return['state'] = 'nok';
                }
                return $return;
        }
    public static function dependancy_install() {
                log::remove('nissan_leaf_connect_update');
                $cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
                $cmd .= ' >> ' . log::getPathToLog('nissan_leaf_connect_update') . ' 2>&1 &';
                exec($cmd);
        }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
 public function toHtml2($_version = 'dashboard') {
                log::add('nissan_leaf_connect', 'debug', 'toHtml1 ' + $_version);
                $replace = $this->preToHtml($_version);
                #if (!is_array($replace)) {
                #        return $replace;
                #}
                $version = jeedom::versionAlias($_version);
                if ($this->getDisplay('hideOn' . $version) == 1) {
                        return '';
                }
                log::add('nissan_leaf_connect', 'debug', 'toHtml10');

		$html = template_replace($replace, getTemplate('core', $version, 'eqlogic', 'nissan_leaf_connect'));
                cache::set('widgetHtml' . $version . $this->getId(), $html, 0);
                return $html;
}
}

class nissan_leaf_connectCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */


     public function execute($_options = array()) {
         log::add('nissan_leaf_connect', 'debug', 'in function execute' );
         log::add('nissan_leaf_connect', 'debug', 'Name = '.$this->getLogicalId());
         if ( $this->getLogicalId() == 'startCharge') {
             $eqLogic = $this->getEqLogic();
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_MCRYPT);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->startCharge();
             $cmd = $eqLogic->getCmd(null, 'Charging');
             $cmd->setCollectDate('');
             $cmd->event(1);
             log::add('nissan_leaf_connect', 'debug', 'Start Charge done' );
	 }
         if ( $this->getLogicalId() == 'startClimateControl') {
             $eqLogic = $this->getEqLogic();
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_MCRYPT);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->startClimateControl();
             $cmd = $eqLogic->getCmd(null, 'RemoteACRunning');
             $cmd->setCollectDate('');
             $cmd->event(1);
             log::add('nissan_leaf_connect', 'debug', 'start clim done ');
	 }
         if ( $this->getLogicalId() == 'stopClimateControl') {
             $eqLogic = $this->getEqLogic();
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_MCRYPT);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->stopClimateControl();
             $cmd = $eqLogic->getCmd(null, 'RemoteACRunning');
             $cmd->setCollectDate('');
             $cmd->event(0);
             log::add('nissan_leaf_connect', 'debug', 'stop clim done ');
	 }
     }


    }

    /*     * **********************Getteur Setteur*************************** */


    /*     * **********************Getteur Setteur*************************** */

?>
