# Modules Nowhere

A Joomla 6 system plugin that allows you to exclude selected modules and module positions from individual article pages, depending on the menu item used to access the article.

[Modules Nowhere](https://brian.teeman.net/joomla/896-modules-nowhere) was created to provide this functionality without having to edit template files or create template overrides.

<!-- Add screenshot here -->

## Configuration

Add a rule for each Category Blog or Category List menu item that should have different module exclusions. Each rule contains:

- **Menu Item** – select the published frontend Category Blog or Category List menu item that the rule applies to.
- **Exclude Modules** – select one or more individual published modules to exclude.
- **Exclude Module Positions** – add one or more rows and select the module positions to exclude. Every module assigned to a selected position is excluded.

A rule can exclude individual modules, module positions, or both. You do not need to configure both types of exclusion.

## How It Works

Rules are matched using the menu item ID (`Itemid`) carried by the article request.

The plugin only acts on individual Joomla articles opened through a Category Blog or Category List menu item. It does not apply exclusions to the category listing page itself.

Each menu item has its own independent rule. This means that the same article can display different modules depending on the menu item used to reach it. For example, if an article is accessible through two different category menu items, each menu item can have a different set of exclusions.

Rules are not inherited from parent menu items, and the plugin does not make decisions based on the article's category.

## Rule Order

Each menu item should appear only once in the rules. Rules are matched by menu item ID, so the order of the rules does not affect which rule is selected.

## Fallback

If the article was not opened through a supported Category Blog or Category List menu item, or if no rule exists for that menu item, the plugin leaves Joomla's normal module list unchanged.

If a rule has no modules or positions selected, it has no exclusions to apply.

## Scope

The plugin affects frontend individual article pages only.

It does not affect:

- Category Blog or Category List pages themselves.
- Articles accessed through a Single Article menu item or another unsupported menu item type.
- Administrator pages or administrator modules.
- Other Joomla components or views.

The plugin removes modules from Joomla's module list before they are rendered. It does not hide module output with CSS, JavaScript, or output buffering, and it does not require template changes.

## Requirements

- Joomla 6.
- A Joomla system plugin enabled on the site.

