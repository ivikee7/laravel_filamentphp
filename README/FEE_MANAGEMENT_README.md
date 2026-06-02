# Fee Management System (Phase 1)

This module introduces a complete, dynamic fee-management backend foundation for school billing.

## What is included

- Dynamic fee heads (`fee_heads`)
- Dynamic fee structures and line rules (`fee_structures`, `fee_structure_items`)
- Student-specific fee profile (`student_fee_profiles`)
- Fee invoices and line items (`fee_invoices`, `fee_invoice_items`)
- Payment transactions (`fee_transactions`)
- Fee engine service (`App\Services\FeeManagement\FeeEngine`)
- Batch invoice generation command (`fee:generate-invoices`)
- Automated test coverage (`tests/Feature/FeeManagement/FeeEngineTest.php`)
- Filament Admin UI resources for fee masters, structures, profiles, invoices, transactions
- Filament Student UI resources for my fee invoices + my fee transactions
- Admin fee reports page and printable invoice page
- Dynamic payment gateway registry (`payment_gateways` table + Admin UI)
- Pluggable gateway manager/factory and provider contracts
- Multi-gateway webhook endpoint: `POST /api/payments/webhook/{driver}`

## Dynamic payment gateway architecture

Core files:

- `app/Models/PaymentGateway.php`
- `app/Services/Payments/Contracts/GatewayInterface.php`
- `app/Services/Payments/GatewayManager.php`
- Providers:
  - `CashGateway`
  - `BankTransferGateway`
  - `UpiGateway`
  - `RazorpayGateway`
  - `StripeGateway`

Admin can enable/disable gateways and switch default from:

- `admin/payment-gateways`

Default gateway rows are seeded by:

- `database/seeders/PaymentGatewaySeeder.php`

Run:

```bash
php artisan db:seed --class=PaymentGatewaySeeder
```

Payment options in Admin/Student fee actions are loaded from enabled gateways dynamically.

To add a new provider:

1. Implement `GatewayInterface`
2. Register provider class in `GatewayManager::$drivers`
3. Add config entry in `payment_gateways` UI

## Signature verification notes

- Razorpay driver verifies either:
  - webhook signature (`X-Razorpay-Signature` + raw body + `RAZORPAY_WEBHOOK_SECRET`), or
  - checkout callback signature (`razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature` + `RAZORPAY_KEY_SECRET`).

- Stripe driver verifies webhook signature using:
  - `Stripe-Signature` header,
  - raw request body,
  - `STRIPE_WEBHOOK_SECRET`.

Webhook endpoint:

- `POST /api/payments/webhook/{driver}`

Webhook event logs and idempotency:

- Incoming events are stored in `payment_webhook_events`
- Duplicate payloads are deduplicated by `payload_hash`
- Processing is queued via `ProcessPaymentWebhookEvent` job with retries
- Admin can monitor events at `admin/payment-webhook-events`

## Reconciliation

Manual command:

```bash
php artisan fee:reconcile-transactions
```

Queued mode:

```bash
php artisan fee:reconcile-transactions --queue --status=pending --status=failed
```

Scheduler (already configured):

- `fee:reconcile-transactions --status=pending --status=failed` runs hourly

Example env keys:

```dotenv
FEE_DYNAMIC_GATEWAYS=true
FEE_DEFAULT_GATEWAY=cash

RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
RAZORPAY_WEBHOOK_SECRET=

STRIPE_PUBLISHABLE_KEY=
STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=

UPI_ID=
BANK_TRANSFER_INSTRUCTIONS=
```

## Admin panel routes (auto-discovered)

- `admin/fee-heads`
- `admin/fee-structures`
- `admin/student-fee-profiles`
- `admin/fee-invoices`
- `admin/fee-transactions`
- `admin/fee-reports`
- `admin/fee-invoices/{record}/print`

## Student panel routes (auto-discovered)

- `student/fees`
- `student/fee-transactions`

## Data model overview

1. **FeeHead**: Master fee component (tuition, transport, exam, etc.)
2. **FeeStructure**: Billing plan for class/year/cycle
3. **FeeStructureItem**: Fee head + amount mapping in a structure
4. **StudentFeeProfile**: Assigned structure + scholarship + sibling discount
5. **FeeInvoice**: Generated bill for a period
6. **FeeInvoiceItem**: Line items in each invoice
7. **FeeTransaction**: Payment attempts/success/refund entries

## Configuration

File: `config/fee.php`

- Billing cycles: monthly/quarterly/term/one_time/custom
- Late-fee mode: flat/daily/percentage
- Dynamic payment methods: cash, transfer, card, upi, razorpay, stripe

## Run migrations

```bash
php artisan migrate
```

## Generate invoices

All eligible students:

```bash
php artisan fee:generate-invoices
```

For one student:

```bash
php artisan fee:generate-invoices --student_id=1
```

Custom period:

```bash
php artisan fee:generate-invoices --start=2026-06-01 --end=2026-06-30 --due=2026-07-10
```

## Record payment (service usage)

```php
$engine = app(\App\Services\FeeManagement\FeeEngine::class);
$engine->recordPayment($invoice, 1200, 'upi', [
    'reference' => 'UPI-ABC-123',
]);
```

## Run tests

```bash
php artisan test --filter=FeeEngineTest
```

## Next phase (UI + automation)

- Filament Admin resources for Fee Heads, Structures, Profiles, Invoices, Transactions
- Student panel: dues, statement, download invoice/receipt
- PDF invoice/receipt generation
- Auto late-fee scheduler
- Razorpay/Stripe webhook integration
- Reports: daily collection, dues aging, class-wise dues, ledger

