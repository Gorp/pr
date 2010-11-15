<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH));
        return $moduleLoader;
    }

    protected function _initRouter() {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $route = new Zend_Controller_Router_Route_Regex(
                        '([a-z]{2})/(.*)\.html',
                        array(
                            'action' => 'index',
                            'controller' => 'index',
                            'module' => 'default'
                        ),
                        array(
                            1 => 'lang',
                            2 => 'url',
                        ), '%s/%s.html');
         $router->addRoute('menu', $route);

         return $router;
    }

}

