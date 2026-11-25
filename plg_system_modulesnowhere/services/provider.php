<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.modulesnowhere
 *
 * @copyright   (C) 2026 Brian Teeman
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Plugin\System\ModulesNowhere\Extension\ModulesNowhere;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            $container->lazy(ModulesNowhere::class, static function (Container $container) {
                return new ModulesNowhere(
                    (array) PluginHelper::getPlugin('system', 'modulesnowhere'),
                    Factory::getApplication()
                );
            })
        );
    }
};
