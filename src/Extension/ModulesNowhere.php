<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.modulesnowhere
 *
 * @copyright   (C) 2026 Brian Teeman
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\ModulesNowhere\Extension;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Event\Module\AfterCleanModuleListEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

/**
 * Modules Nowhere system plugin.
 */
final class ModulesNowhere extends CMSPlugin implements SubscriberInterface
{
    /**
     * Subscribe to the event fired after Joomla has cleaned the module list.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterCleanModuleList' => 'onAfterCleanModuleList',
        ];
    }

    /**
     * Filter modules before Joomla renders them.
     */
    public function onAfterCleanModuleList(AfterCleanModuleListEvent $event): void
    {
        // Implementation will be added after the menu-item matching and
        // administrator configuration have been finalised.
    }
}
