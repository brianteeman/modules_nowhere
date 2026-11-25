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

## Update Server

The plugin registers its update site in the installation manifest. Joomla checks the update metadata in [update.xml](update.xml), which points to the installable ZIP attached to the latest [GitHub Release](https://github.com/brianteeman/modules_nowhere/releases).

The ZIP attached to a release is the Joomla installation package. The automatically generated GitHub source archives are not installable plugin packages.

## Building a New Release

Releases are built and published by the GitHub Actions workflow [Build and publish plugin release](.github/workflows/release.yml).

For each new release:

1. Make and test the changes on a development branch.
2. Merge the changes into `main`.
3. Update the extension version in both places:
   - `plg_system_modulesnowhere/modulesnowhere.xml` — the plugin manifest's `<version>` value.
   - `update.xml` — the `<version>` value inside the update entry.
4. Commit and push those version changes to `main`.
5. Create a Git tag using the same version, prefixed with `v`. For example, for version `1.1.0`:
   ```bash
   git checkout main
   git pull
   git tag v1.1.0
   git push origin v1.1.0
   ```
6. GitHub Actions runs automatically when the tag is pushed. It checks out the tagged source, builds `plg_system_modulesnowhere.zip`, and attaches the package to the matching GitHub Release. If the release does not already exist, the workflow creates it with a title in the format **Modules Nowhere Plugin v1.1.0**, using the actual tag name in place of `v1.1.0`.

### Manual release steps

No manual ZIP creation or upload is normally required. Check the workflow run under the repository's **Actions** tab and confirm it completes successfully. Then check the matching release under **Releases** and verify that it contains `plg_system_modulesnowhere.zip`.

If a GitHub Release was created manually before the workflow ran, the workflow uploads the package to that existing release and replaces an asset with the same filename if necessary.

To run the workflow manually (for example, to retry packaging an existing tag), open **Actions → Build and publish plugin release → Run workflow**, enter the tag (such as `v1.1.0`), and start the run. The tag must already exist and point to the source you want to package.

After publishing, Joomla can discover the new version through its extension update check. The update XML uses the latest GitHub Release download URL, so keep the manifest version, update XML version, and release tag in sync.
