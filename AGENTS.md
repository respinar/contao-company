# AGENTS.md — Respinar Company Bundle

> Hand-off guide for future coding agents working on this project.

---

## 1. Project Overview

- **Project root**: `/home/hamid/dev-contao/Contao-Bundles/contao-company`
- **Package name**: `respinar/contao-company`
- **Purpose**: A Contao 5.3+ backend bundle that groups the functionality of merged sub-bundles under a single `company` backend module:
  - Projects (`respinar/contao-projects`)
  - Clients (`respinar/contao-clients`)
  - Testimonials (`respinar/contao-testimonials`)
  - Categories (`respinar/contao-categories`)
  - Locations (`respinar/contao-locations`)

- **No DCS/module/content elements live here directly** — this bundle acts as a `company` group for other bundles to attach backend and frontend modules to.
- **Backend icon**: `public/icons/company.svg`, styled with `public/css/backend.css`.

---

## 2. Architecture & Patterns

### Namespaces
Everything must live inside `Respinar\CompanyBundle\`.
Do **not** use the old bundle namespaces:

| ❌ Old (removed) | ✅ Current |
|----------------|------------|
| `Respinar\ProjectsBundle\...` | `Respinar\CompanyBundle\...` |
| `Respinar\ClientsBundle\...` | `Respinar\CompanyBundle\...` |
| `Respinar\TestimonialsBundle\...` | `Respinar\CompanyBundle\...` |

### List Controller Pattern
All list controllers (e.g. `ProjectListController`, `ClientListController`, `TestimonialListController`) follow this exact pattern:

1. Call `Model::findPublishedByPids(...)` directly (no custom Repository for listing).
2. Check against `null`, then `foreach` over the `Collection`.
3. Pass each **Model object** to `$this->renderer->render($model, $contentModel)`.
4. Set the rendered-array on the Twig template: `$template->set('projects', $items)`.
5. Template extends `@Contao/content_element/_base.html.twig` and uses `{{ projects|join|raw }}` (or `clients`, `testimonials`, etc.).

### Renderer Pattern
All renderers extend nothing and are `final`:

```php
final class XxxRenderer
{
    // accept Model object only
    public function render(XxxModel $model, ContentModel $contentModel): string
    {
        $template = new FrontendTemplate($contentModel->xxx_template ?: 'xxx_default');
        $template->setData($model->row());
        // … additional derived fields …
        return $template->parse();
    }
}
```

Do **not** accept `array` in renderers. Always call `$model->row()` for `setData()`.
Access model data with **property access** (`$model->field`), not array access (`$model['field']`).

### Model Pattern
Published-finder static methods are named `findPublishedByPids` (or `findPublishedByPid` for single-parent tables).

They:
- Accept `array $arrPids`, `?bool $blnFeatured`, plus optional `int $intLimit`, etc.
- Build `$arrColumns` with proper published/start/stop time checks using `Date::floorToMinute()`.
- Return `Collection<Model>|null`.

If category filtering is needed (e.g. `TestimonialModel`), do it **inside** the model method after the DB query and return a rebuilt `Collection`.

### DCA SQL Definitions
Always use the **modern Doctrine DBAL array format** (never raw SQL strings):

| Old | Modern |
|-----|--------|
| `'sql' => 'int(10) unsigned NOT NULL auto_increment'` | `['type' => 'integer', 'unsigned' => true, 'autoincrement' => true]` |
| `'sql' => "varchar(255) NOT NULL default ''"` | `['type' => 'string', 'length' => 255, 'default' => '']` |
| `'sql' => 'text NULL'` | `['type' => 'text', 'length' => AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull' => false]` |
| `'sql' => 'binary(16) NULL'` | `['type' => 'binary', 'length' => 16, 'notnull' => false]` |

Import `Doctrine\DBAL\Platforms\AbstractMySQLPlatform` when using the length constants.

---

## 3. What We Have Done (Completed)

### Merged bundles into one
- Moved all DCAs, models, controllers, renderers, templates, and translations into `respinar/contao-company`.
- Fixed all old external bundle namespaces to `Respinar\CompanyBundle\`.

### Backend group & icon
- Registered `$GLOBALS['BE_MOD']['company']` group.
- Created `InitializeSystemListener` to position the group after `content` and load the SVG icon CSS.

### XLIFF translations
- Converted all PHP translation files to XLIFF:
  - `tl_company_category`, `modules`, `tl_company_location`, `tl_company_testimonial`, `tl_company_testimonial_archive`, `tl_company_client`, `tl_company_client_group`, `tl_company_project`, `tl_company_project_archive`, `tl_content`

### Modern SQL conversion
- Converted all DCA field SQL definitions from raw strings to Doctrine DBAL array format across:
  - `tl_company_category`
  - `tl_company_client`
  - `tl_company_client_group`
  - `tl_company_location`
  - `tl_company_testimonial`
  - `tl_company_testimonial_archive`
  - `tl_company_project`
  - `tl_company_project_archive`
  - `tl_content`

### Added missing model finders
- `ClientModel::findPublishedByPid()`
- `ProjectModel::findPublishedByPids()`
- `TestimonialModel::findPublishedByPids()` (also handles category filtering)

### Refactored list controllers
- `ClientListController` → uses `ClientModel::findPublishedByPid()`
- `ProjectListController` → uses `ProjectModel::findPublishedByPids()`
- `TestimonialListController` → uses `TestimonialModel::findPublishedByPids()` (removed Repository dependency)

### Added ECS & Rector
- `ecs.php` with Contao set
- `rector.php` with PHP 8.3, Symfony 6.4, quality, dead-code sets
- Ran `vendor/bin/ecs check --fix` across the whole codebase and committed formatting fixes.

### Runtime fixes
- Fixed critical `$this->parser` bug in `TestimonialListController`.
- Fixed `TestimonialRenderer` array-access crash → use property access (`$model->featured`).
- Fixed `Unknown column 'sorting'` crash in testimonial ordering (table has no `sorting` column; default changed to `date DESC`).

---

## 4. What Still Needs to be Done

### Controllers
- [ ] `TestimonialReaderController` still uses `TestimonialRepository` directly and does **not** use `TestimonialRenderer`. For consistency, it should follow the `ProjectReaderController` pattern:
  - Find the model by alias via `TestimonialModel::findOneBy('alias', $alias)`
  - Pass the model to `TestimonialRenderer::renderTestimonial()`
  - Or at minimum accept `TestimonialModel` rather than a raw array.

### DCA cleanup
- [ ] `featured` and `published` fields in **client** and **project** DCAs still use `char(1)` string SQL in some places — verify all are converted to `['type' => 'boolean', 'default' => false]`.
- [ ] `tl_company_testimonial_archive.php` — verify all SQL is fully modern (some `int(10)` strings may remain).
- [ ] `tl_user.php` / `tl_user_group.php` — these only add permissions fields; confirm no raw SQL strings remain.

### Templates
- [ ] `testimonial_reader.html.twig` — make sure it extends `_base.html.twig` and matches the reader pattern.
- [ ] `project_reader.html.twig` — confirm it uses the correct template variable names.

### Translations
- [ ] Some DCA labels inline `label => &$GLOBALS['TL_LANG']...` references may still exist — these should be removed so XLIFF is the single source of truth.
- [ ] German (`de`) XLIFF translations do not exist yet.
- [ ] `tl_company_testimonial.xlf` ID format was corrected to `.0`/`.1` suffixes; verify no old `*.help` IDs remain.

### Code style / quality
- [ ] Run `vendor/bin/rector process` to apply automated refactors.
- [ ] Verify `ClientListController` uses `$model->numberOfItems` correctly (already wired, but double-check behaviour when `0`).
- [ ] Some controllers use `array()` syntax in DCAs while others use `[]` — ECS converted most, but spot-check remaining DCAs.

### Repository decision
- [ ] `TestimonialRepository` is now only used by `TestimonialReaderController`. Consider whether to keep it or fold its logic into the model/renderer.

---

## 5. Rules & Constraints

1. **Do NOT commit without explicit user permission.** The user must say "commit" or "push" before you create a commit.
2. **Always run `php -l` on modified PHP files** before showing them as done.
3. **Always run `vendor/bin/ecs check --fix` on modified files** before asking to commit.
4. **Never delete the license header** in existing files:
   ```
   /*
    * This file is part of Contao Company Bundle.
    *
    * (c) Hamid Peywasti
    *
    * @license MIT
    */
   ```
5. **The active branch is `dev/testimonials`**. Do not switch to `main` without explicit instruction.
6. **PHP 8.3+ with strict types** (`declare(strict_types=1);`).
7. **Contao 5.7+** patterns only (Twig fragments, `FragmentTemplate`, `#[AsContentElement]`, modern DCA SQL).

---

## 6. Quick Commands

```bash
# Syntax check
php -l src/.../SomeFile.php

# ECS check & fix
php vendor/bin/ecs check --fix
php vendor/bin/ecs check src/Path/File.php --fix

# Rector dry-run
php vendor/bin/rector process --dry-run

# Git status
git status

# Show commits on this branch ahead of main
git log --oneline dev/testimonials ^main
```

---

*Last updated: 2026-08-03*
