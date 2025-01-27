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
      protected static $_templateArray = array();

      public static  function cron15() {
          log::add('nissan_leaf_connect', 'debug', 'in cron');
          self::update_nissan();
          log::add('nissan_leaf_connect', 'debug', 'end cron');
          }
  
      public function refresh() {
          log::add('nissan_leaf_connect', 'debug', 'in refresh');
          self::update_nissan();
          log::add('nissan_leaf_connect', 'debug', 'end refresh');
          }
         
      public function update_nissan() {
          log::add('nissan_leaf_connect', 'debug', 'is update_nissan');
          $eqLogics = eqLogic::byType('nissan_leaf_connect');
             foreach ($eqLogics as $eqLogic) {
                 $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                    $eqLogic->getConfiguration('password'),
                                                    'Europe/Paris', 
                                                    NissanConnect::COUNTRY_EU, 
                                                    NissanConnect::ENCRYPTION_OPTION_OPENSSL);

                 $nissanConnect->debug = True;

                 $nissanConnect->maxWaitTime = 290;
                 $result = $nissanConnect->getStatus();
                 $debug_printr = print_r($result, true);
                 log::add('nissan_leaf_connect', 'debug', 'after get2'.$debug_printr );
                 $cmd = $eqLogic->getCmd('info', 'BatteryRemainingAmount');
                 if (is_object($cmd)) {
                     $cmd->setCollectDate('');
                     $cmd->event($result->BatteryRemainingAmount );
                 }
                 $cmd = $eqLogic->getCmd('info', 'SOC');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->SOC);
                 }
                 $cmd = $eqLogic->getCmd('info', 'BatteryCapacity');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->BatteryCapacity );
                 }
                 $cmd = $eqLogic->getCmd('info', 'Charging');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->Charging );
                 }
                 $cmd = $eqLogic->getCmd('info', 'ChargingMode');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->ChargingMode );
                 }
                 $cmd = $eqLogic->getCmd('info', 'CruisingRangeAcOn');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->CruisingRangeAcOn );
                 }
                 $cmd = $eqLogic->getCmd('info', 'CruisingRangeAcOff');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->CruisingRangeAcOff );
                 }
                 $cmd = $eqLogic->getCmd('info', 'RemoteACRunning');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->RemoteACRunning );
                 }
                 $cmd = $eqLogic->getCmd('info', 'TimeRequiredToFull200_H');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->TimeRequiredToFull200->Hours + $result->TimeRequiredToFull200->Minutes / 60  );
			}
                 $cmd = $eqLogic->getCmd('info', 'TimeRequiredToFull200');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->TimeRequiredToFull200->Formatted  );
                 }
                 $cmd = $eqLogic->getCmd('info', 'TimeRequiredToFull200_6kW_H');
                 if (is_object($cmd)) {
                     if ( isset ( $result->TimeRequiredToFull200_6kW->Hours  )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->TimeRequiredToFull200_6kW->Hours + $result->TimeRequiredToFull200_6kW->Minutes / 60  );
		         }
                     else
			{
                         $cmd->setCollectDate('');
                         $cmd->event(Null );
			}
                 }
                 $cmd = $eqLogic->getCmd('info', 'TimeRequiredToFull200_6kW');
                 if (is_object($cmd)) {
                     if ( isset ( $result->TimeRequiredToFull200_6kW->Formatted  )) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->TimeRequiredToFull200_6kW->Formatted  );
                         $cmd->setIsVisible(1);
                         $cmd->save();
                        }
                     else
			{
                         $cmd->setCollectDate('');
                         $cmd->event(Null );
                         $cmd->setIsVisible(0);
                         $cmd->save();
			}
                 }

                 $cmd = $eqLogic->getCmd('info', 'PluggedIn');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->PluggedIn );
                 }
                 $cmd = $eqLogic->getCmd('info', 'BatteryRemainingAmountWH');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->BatteryRemainingAmountWH );
                 }
                 $cmd = $eqLogic->getCmd('info', 'LastUpdated');
                 if (is_object($cmd)) {
                         $cmd->setCollectDate('');
                         $cmd->event($result->LastUpdated );
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
     $refresh = $this->getCmd(null, 'refresh');
                if (!is_object($refresh)) {
                        log::add('nissan_leaf_connect', 'debug', 'postSave_refresh_not_extist');
                        $refresh = new nissan_leaf_connectCmd();
                        $refresh->setLogicalId('refresh');
                        $refresh->setIsVisible(1);
                        $refresh->setName(__('Rafraichir', __FILE__));
                }
                $refresh->setType('action');
                $refresh->setSubType('other');
                $refresh->setEqLogic_id($this->getId());
                $refresh->save();
    }

    public function preUpdate() {
      log::add('nissan_leaf_connect', 'debug', 'preUpdate');
        
    }

    public function postUpdate() {
      log::add('nissan_leaf_connect', 'debug', 'postUpdate');
      
      $new_cmd = $this->getCmd(null, 'SOC');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setOrder(1);
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             $new_cmd->setIsHistorized(1);
             }
      $new_cmd->setName(__('Capacité', __FILE__));
      $new_cmd->setLogicalId('SOC');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('%');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->setConfiguration('maxValue', 100);
      $new_cmd->setConfiguration('minValue', 0);
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'CruisingRangeAcOn');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsHistorized(1);
             $new_cmd->setOrder(2);
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             }
      $new_cmd->setName(__('Auton. avec AC', __FILE__));
      $new_cmd->setLogicalId('CruisingRangeAcOn');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Km');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->setConfiguration('maxValue', 300);
      $new_cmd->setConfiguration('minValue', 0);
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'CruisingRangeAcOff');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsHistorized(1);
             $new_cmd->setOrder(3);
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             }
      $new_cmd->setName(__('Auton. sans AC', __FILE__));
      $new_cmd->setLogicalId('CruisingRangeAcOff');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Km');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->setConfiguration('maxValue', 300);
      $new_cmd->setConfiguration('minValue', 0);
      $new_cmd->save();


      $new_cmd = $this->getCmd(null, 'PluggedIn');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setOrder(4);
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             }
      $new_cmd->setName(__('Cable', __FILE__));
      $new_cmd->setLogicalId('PluggedIn');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('binary');
      $new_cmd->setOrder(4);
      $new_cmd->setDisplay('generic_type','GENERIC_INFO');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'Charging');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setDisplay('generic_type','ENERGY_STATE');
             $new_cmd->setOrder(5);
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
             $new_cmd->setDisplay('generic_type','HEATING_STATE');
             $new_cmd->setOrder(6);
             }
      $new_cmd->setName(__('Chauffage', __FILE__));
      $new_cmd->setLogicalId('RemoteACRunning');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('binary');
      $new_cmd->save();
      $new_cmd = $this->getCmd(null, 'ChargingMode');

      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setOrder(7);
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             }
      $new_cmd->setName(__('Mode Charge', __FILE__));
      $new_cmd->setLogicalId('ChargingMode');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('string');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'BatteryRemainingAmountWH');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(0);
             $new_cmd->setIsHistorized(1);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(9);
             }
      $new_cmd->setName(__('Capacité Restante WH', __FILE__));
      $new_cmd->setLogicalId('BatteryRemainingAmountWH');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Wh');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->setConfiguration('maxValue', 220000);
      $new_cmd->setConfiguration('minValue', 0);
      $new_cmd->setIsHistorized(1);
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'BatteryRemainingAmount');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(0);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(10);
             }
      $new_cmd->setName(__('Capacité Restante', __FILE__));
      $new_cmd->setLogicalId('BatteryRemainingAmount');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('Wh');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'BatteryCapacity');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(0);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(10);
             }
      $new_cmd->setName(__('Capacité Globale', __FILE__));
      $new_cmd->setLogicalId('BatteryCapacity');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setUnite('kWh');
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'TimeRequiredToFull200_H');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(0);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(11);
             }
      $new_cmd->setName(__('Recharge Heure 3kW', __FILE__));
      $new_cmd->setLogicalId('TimeRequiredToFull200_H');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();

      $new_cmd = $this->getCmd(null, 'TimeRequiredToFull200_6kW_H');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(0);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(12);
             }
      $new_cmd->setName(__('Recharge Heure 6kW', __FILE__));
      $new_cmd->setLogicalId('TimeRequiredToFull200_6kW_H');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('numeric');
      $new_cmd->save();
 
      $new_cmd = $this->getCmd(null, 'TimeRequiredToFull200');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(1);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(13);
             }
      $new_cmd->setName(__('Recharge 3kW', __FILE__));
      $new_cmd->setLogicalId('TimeRequiredToFull200');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('string');
      $new_cmd->save();
 
      $new_cmd = $this->getCmd(null, 'TimeRequiredToFull200_6kW');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setIsVisible(1);
             $new_cmd->setDisplay('generic_type','DONT');
             $new_cmd->setOrder(14);
             }
      $new_cmd->setName(__('Recharge 6kW', __FILE__));
      $new_cmd->setLogicalId('TimeRequiredToFull200_6kW');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setType('info');
      $new_cmd->setSubType('string');
      $new_cmd->save();
 
      $new_cmd = $this->getCmd(null, 'startClimateControl');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setOrder(15);
             $new_cmd->setDisplay('generic_type','HEATING_ON');
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
             $new_cmd->setDisplay('generic_type','HEATING_OFF');
             $new_cmd->setOrder(16);
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
             $new_cmd->setDisplay('generic_type','ENERGY_ON');
             $new_cmd->setOrder(17);
             }
      $new_cmd->setName(__('Charge ON', __FILE__));
      $new_cmd->setLogicalId('startCharge');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setSubType('other');
      $new_cmd->setType('action');
      $new_cmd->save();

      # stupid command for mobile apps 
      $new_cmd = $this->getCmd(null, 'stopCharge');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setDisplay('generic_type','ENERGY_OFF');
             $new_cmd->setIsVisible(0);
             $new_cmd->setOrder(180);
             }
      $new_cmd->setName(__('Charge Off', __FILE__));
      $new_cmd->setLogicalId('stopCharge');
      $new_cmd->setEqLogic_id($this->getId());
      $new_cmd->setSubType('other');
      $new_cmd->setType('action');
      $new_cmd->save();
      
      $new_cmd = $this->getCmd(null, 'LastUpdated');
      if (!is_object($new_cmd)) {
             $new_cmd = new nissan_leaf_connectCmd();
             $new_cmd->setDisplay('generic_type','GENERIC_INFO');
             $new_cmd->setOrder(190);
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

       public function toHtml($_version = 'dashboard') {
                $replace = $this->preToHtml($_version);
                if (!is_array($replace)) {
                        return $replace;
                }
                $debug_printr = print_r($replace, true);
                log::add('nissan_leaf_connect', 'debug', 'replace toHtml'.$debug_printr );

                $version = jeedom::versionAlias($_version);
                $cmd_html = '';
                foreach ($this->getCmd(null, null, true) as $cmd) {
                        if ($cmd->getLogicalId() == 'refresh') {
                                continue;
                        }
                        if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
                                #$cmd_html .= '<br/>';
                        }
                        $cmd_html .= $cmd->toHtml($_version, '', $replace['#cmd-background-color#']);
                        if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
                                #$cmd_html .= '<br/>';
                        }
                }
                $replace['#cmd#'] = $cmd_html;
                if (!isset(self::$_templateArray[$version])) {
                        self::$_templateArray[$version] = getTemplate('core', $version, 'eqLogic');
                }
                return template_replace($replace, self::$_templateArray[$version]);
                cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
                return $html;
        }
}

class nissan_leaf_connectCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
     
     public function execute($_options = array()) {
         log::add('nissan_leaf_connect', 'info', 'in function execute' );
         log::add('nissan_leaf_connect', 'debug', 'Name = '.$this->getLogicalId());
         if ($this->getLogicalId() == 'refresh') {
                        $this->getEqLogic()->refresh();
                }
         elseif ( $this->getLogicalId() == 'startCharge') {
             $eqLogic = $this->getEqLogic();
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_OPENSSL);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->startCharge();
             $cmd = $eqLogic->getCmd(null, 'Charging');
             $cmd->setCollectDate('');
             $cmd->event(1);
             log::add('nissan_leaf_connect', 'debug', 'Start Charge done' );
	 }
         elseif ( $this->getLogicalId() == 'startClimateControl') {
             $eqLogic = $this->getEqLogic();
             $cmd = $eqLogic->getCmd(null, 'RemoteACRunning');
             $cmd->setCollectDate('');
             $cmd->event(1);
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_OPENSSL);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->startClimateControl();
             log::add('nissan_leaf_connect', 'debug', 'start clim done ');
	 }
         elseif ( $this->getLogicalId() == 'stopClimateControl') {
             $eqLogic = $this->getEqLogic();
             $cmd = $eqLogic->getCmd(null, 'RemoteACRunning');
             $cmd->setCollectDate('');
             $cmd->event(0);
             $nissanConnect = new NissanConnect($eqLogic->getConfiguration('username'),
                                                $eqLogic->getConfiguration('password'),
                                                'Europe/Paris',
                                                NissanConnect::COUNTRY_EU,
                                                NissanConnect::ENCRYPTION_OPTION_OPENSSL);

             $nissanConnect->debug = True;
             $nissanConnect->maxWaitTime = 290;
             $nissanConnect->stopClimateControl();
             log::add('nissan_leaf_connect', 'debug', 'stop clim done ');
	 }
     }

    #}
   }

    /*     * **********************Getteur Setteur*************************** */


    /*     * **********************Getteur Setteur*************************** */

?>
