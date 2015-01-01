<?php

/**
 * Dealers Router
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard {

    /**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request) {
        //checking before even try to find out that current module
        //should use this router
        if(!$this->_beforeModuleMatch()) {
            return false;
        }

        $front = $this->getFront();
        $path = trim($request->getPathInfo(), '/');
        $p = explode('/', $path);

        if(count($p) == 0 || !$this->_pluarizeName($p[0])) {
            return false;
        }
        else {
            $module = $this->_pluarizeName($p[0]);
            $modules = $this->getModuleByFrontName($module);
            if($modules === false) {
                return false;
            }
            // checks after we found out that this router should be used for current module
            if(!$this->_afterModuleMatch()) {
                return false;
            }

            // set values only after all the checks are done
            $request->setModuleName($module);
            $request->setControllerName('index');
            $action = $this->_getActionFromPathInfo($p);
            $request->setActionName($action);
            $realModule = 'Zefir_Dealers';
            $request->setControllerModule($realModule);
            $request->setRouteName('dealers');

            // dispatch action
            $request->setDispatched(true);

            /**
             * Set params for the request
             */
            if($action == 'view') {
                $request->setParam('dealer_code', $p[1]);
            }
            else {
                // set parameters from pathinfo
                for($i = 3, $l = sizeof($p); $i < $l; $i += 2) {
                    $request->setParam($p[$i], isset($p[$i + 1]) ? urldecode($p[$i + 1]) : '');
                }
            }

            // instantiate controller class and dispatch action
            $controllerClassName = $this->_validateControllerClassName($realModule, 'index');
            $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());
            $controllerInstance->dispatch($action);

            return true;
        }
    }

    /**
     * Pluraize dealer module name from url.
     * We accept "dealer" as well as "dealers"
     *
     * @param string $value
     * @return string
     */
    protected function _pluarizeName($value) {
        if($value == 'dealers') {
            return $value;
        }
        if($value == 'dealer') {
            return $value . 's';
        }

        return null;
    }

    /**
     * Decide which action use depending on the url structure
     *
     * @param array $path
     * @return string
     */
    protected function _getActionFromPathInfo($path) {

        if(array_key_exists(1, $path) && $path[1] != 'index') {
            /**
             * if second element of path is different than index
             * then this url is to specific dealer i.e. dealer/dealer-code
             */
            return 'view';
        }
        elseif(array_key_exists(2, $path)) {
            /**
             * We have at least three elements which means that the third one is action name
             */
            return $path[2];
        }
        else {
            /**
             * Some bogus url with dealer frontname redirect to index action
             */
            return 'index';
        }
    }
}