# School ERP (Laravel + Filament)

This application is a school ERP built with Laravel and Filament, including:

- Admin and Student panels
- Student profile, attendance, courses, and exam workflows
- Dynamic fee management system
- Multi-gateway payment architecture (cash, bank transfer, UPI, Razorpay, Stripe)
- Webhook processing, idempotency, and reconciliation tooling

## Quick Start

```bash
php artisan migrate
php artisan db:seed
php artisan optimize:clear
php artisan serve
```

## Documentation Index

All project documentation is stored in the `README/` directory.

Core docs:

- `README/SETUP_STATUS.md`
- `README/MIGRATIONS_GUIDE.md`
- `README/FEE_MANAGEMENT_README.md`
- `README/CONTRIBUTING_DOCS.md`

Website docs:

- `README/WEBSITE_GUIDE.md`
- `README/WEBSITE_IMPLEMENTATION.md`
- `README/WEBSITE_CHECKLIST.md`
- `README/WEBSITE_FINAL_STATUS.md`

Student schemas docs:

- `README/README_SCHEMAS.md`
- `README/STUDENT_SCHEMAS_GUIDE.md`
- `README/SCHEMAS_QUICK_REFERENCE.md`
- `README/SCHEMAS_IMPLEMENTATION_SUMMARY.md`
- `README/SCHEMAS_INDEX.md`

## Documentation Rules (Important)

To keep documentation organized:

1. Keep only this index file at root: `README.md`
2. Store all new markdown docs inside `README/`
3. Use uppercase snake-case names for new docs, for example:
   - `README/FEATURE_X_GUIDE.md`
   - `README/FEATURE_X_STATUS.md`
4. Whenever a new `.md` file is added, update this `README.md` index

## Operational Commands

Fee generation:

```bash
php artisan fee:generate-invoices
php artisan fee:generate-invoices --student_id=1 --start=2026-06-01 --end=2026-06-30 --due=2026-07-10
```

Fee reconciliation:

```bash
php artisan fee:reconcile-transactions
php artisan fee:reconcile-transactions --queue --status=pending --status=failed
```

README policy lint:

```bash
php artisan readme:lint
```

## Panels

- Admin panel: `/admin`
- Student panel: `/student`

## Notes

- Payment gateway webhook endpoint: `POST /api/payments/webhook/{driver}`
- Gateway management UI: `/admin/payment-gateways`
- Webhook event monitor: `/admin/payment-webhook-events`
