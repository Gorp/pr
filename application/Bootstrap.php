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
                        '([a-z]{2})/(.*)-(\d+)\.html',
                        array(
                            'action' => 'page',
                            'controller' => 'index',
                            'module' => 'default'
                        ),
                        array(
                            1 => 'lang',
                            2 => 'title',
                            3 => 'idpage'
                        ), '%s/%s-%d.html');
         $router->addRoute('page', $route);

        $route2 = new Zend_Controller_Router_Route_Regex(
                        '([a-z]{2})',
                        array(
                            'action' => 'index',
                            'controller' => 'index',
                            'module' => 'default'
                        ),
                        array(
                            1 => 'lang',
                        ), '%s');
         $router->addRoute('mainpage', $route2);
         return $router;
    }
}

