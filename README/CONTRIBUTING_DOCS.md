# Documentation Contribution Guide

This guide defines how project documentation must be created and maintained.

## Scope

Applies to all project markdown documentation files.

## Required Rules

1. Keep only one root markdown index at project root: `README.md`.
2. Store all other project docs under `README/`.
3. Use uppercase snake-case filenames for new docs, for example:
   - `README/FEATURE_X_GUIDE.md`
   - `README/FEATURE_X_STATUS.md`
4. Update root `README.md` whenever adding, renaming, or removing docs.
5. Run documentation lint before commit.

## Naming Convention

Use one of these patterns:

- `README/<FEATURE>_GUIDE.md`
- `README/<FEATURE>_STATUS.md`
- `README/<FEATURE>_CHECKLIST.md`
- `README/<FEATURE>_IMPLEMENTATION.md`
- `README/<FEATURE>_SUMMARY.md`

## Authoring Checklist

Before creating a PR:

- [ ] File is inside `README/`
- [ ] Filename follows convention
- [ ] Root `README.md` includes link to this file
- [ ] Outdated/replaced docs are removed or redirected
- [ ] `php artisan readme:lint` passes

## Lint Command

Use this command:

```bash
php artisan readme:lint
```

The linter verifies:

- No project markdown files exist outside `README/` (except root `README.md`)
- Every `README/*.md` file is referenced in root `README.md`
- Every `README/*.md` path referenced in root `README.md` exists

## Recommended Section Order

Inside each documentation file:

1. Purpose
2. Current Status
3. Setup / Usage
4. Operational Commands
5. Troubleshooting
6. Next Steps

## Deprecating Docs

When replacing a document:

1. Remove or archive old file in `README/`
2. Update links in root `README.md`
3. Add a short note in the replacement file describing what changed

## Ownership

All contributors who modify features are responsible for updating related docs in `README/`.

