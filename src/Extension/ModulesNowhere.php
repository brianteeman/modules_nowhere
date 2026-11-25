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
     * Constructor.
     */
    public function __construct(array $config, CMSApplicationInterface $app)
    {
        parent::__construct($config);

        $this->setApplication($app);
    }

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
        $app = $this->getApplication();

        // This plugin only changes the frontend module list.
        if (!$app->isClient('site')) {
            return;
        }

        $input = $app->getInput();

        // Only process individual Joomla articles.
        if ($input->getCmd('option') !== 'com_content' || $input->getCmd('view') !== 'article') {
            return;
        }

        $itemId = $input->getInt('Itemid');

        if (!$itemId) {
            return;
        }

        // Resolve the menu item using Joomla's menu API.
        $menuItem = $app->getMenu()->getItem($itemId);

        if (!$menuItem || !$this->isSupportedMenuItem($menuItem)) {
            return;
        }

        // Rules are keyed by menu item ID. The administrator form will
        // populate this parameter once its configuration fields are added.
        $rules = $this->params->get('rules', []);

        if (!is_array($rules) || !isset($rules[$itemId]) || !is_array($rules[$itemId])) {
            return;
        }

        $rule = $rules[$itemId];

        $excludedModules   = array_map('intval', (array) ($rule['modules'] ?? []));
        $excludedPositions = array_map('strval', (array) ($rule['positions'] ?? []));

        if (!$excludedModules && !$excludedPositions) {
            return;
        }

        $modules = $event->getModules();

        $modules = array_values(array_filter(
            $modules,
            static function ($module) use ($excludedModules, $excludedPositions): bool {
                if (isset($module->id) && in_array((int) $module->id, $excludedModules, true)) {
                    return false;
                }

                if (isset($module->position) && in_array((string) $module->position, $excludedPositions, true)) {
                    return false;
                }

                return true;
            }
        ));

        $event->updateModules($modules);
    }

    /**
     * Check whether a menu item links to a supported com_content listing.
     *
     * Component menu items have the general type "component". Their actual
     * view and layout are identified by the query parameters in the link.
     *
     * @param   object  $menuItem  The resolved menu item.
     *
     * @return  bool
     */
    private function isSupportedMenuItem(object $menuItem): bool
    {
        if (($menuItem->type ?? '') !== 'component' || empty($menuItem->link)) {
            return false;
        }

        $query = parse_url((string) $menuItem->link, PHP_URL_QUERY);

        if (!$query) {
            return false;
        }

        parse_str($query, $parameters);

        if (($parameters['option'] ?? '') !== 'com_content') {
            return false;
        }

        // Category Blog.
        if (($parameters['view'] ?? '') === 'category' && ($parameters['layout'] ?? '') === 'blog') {
            return true;
        }

        // List All Categories (Category List menu item).
        return ($parameters['view'] ?? '') === 'categories';
    }
}
