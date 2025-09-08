# AGENTS.md — EliteSaaS / CafeOS Modules Catalog

> **الغرض**: هذا الملف هو المرجع الرسمي لجميع الموديولات (Agents) في منصة **EliteSaaS / CafeOS**. يوضح الوصف، الميزات المتقدمة، المسئوليات، الاعتمادات (Dependencies)، لوحات التحكم/البورتالات، التفاصيل التقنية، الجداول الأساسية، الأحداث (Events/Topics)، وتدفقات العمل. كل الموديولات **متعددة المستأجرين** (Multi‑Tenancy) بعزل صفّي باستخدام `tenant_id` مع قابلية التفعيل/الإيقاف لكل Tenant.

---

## 0) نظرة عامة

* **النطاق**: ضيافة (مطاعم/كافيهات)، توظيف، عقارات، التجارة الإلكترونية، سلاسل الإمداد.
* **الهيكل المعماري**: قلب Monolith معياري + Extensions/Plugins عبر **Module Registry** + أحداث/طوابير.
* **الأهداف**: أمان، أداء، قابلية توسع، تخصيص لكل Tenant، سهولة التكامل.
* **التقنيات**: Laravel 12، PHP 8.3+، MySQL 8، Redis، Reverb WebSockets، Inertia/Vue 3، Pinia، Vite، Filament (لوحة السوبر أدمن).
* **الأداء**: Caching طبقي (per‑tenant keys)، فهارس مركبة، Jobs/Queues، تجميع تقارير عبر Views.

---

## 1) Core & Governance

### \[CORE] Core

**الوصف**: الأساس متعدد المستأجرين: المستخدمون، الأدوار، تهيئة المستأجر، حافلة الأحداث الداخلية.

* **ميزات متقدمة**: Onboarding تلقائي، RBAC ديناميكي، إعدادات Tenant في JSON، سياسات وصول سياقية.
* **المسئوليات**: إدارة Tenants/Users/Roles، فرض العزل، تسجيل الموديولات الفعّالة.
* **Dependencies**: —
* **لوحات**: Super Admin Overview، Tenant Setup Wizard.
* **جداول**: `tenants(id,name,domain,config_json)`, `users(id,tenant_id,email,password)`, `tenant_modules(tenant_id,module,enabled)`.
* **Events/Topics**: `tenant.created`, `tenant.activated`, `user.invited`.
* **تدفق عمل**: طلب تسجيل → إنشاء Tenant → Seed أدوار/مستخدمين → تفعيل الموديولات → إشعار ترحيبي.

### \[GOV] Super Admin (God Mode)

**الوصف**: تحكم شامل في النظام والمستأجرين.

* **ميزات**: تبديل الموديولات لكل Tenant مع فحص تبعيات، إعدادات عامة (لغات/عملات)، إيقاف/تعليق Tenant مع أرشفة.
* **Dependencies**: Core, Security, Billing, Reporting.
* **لوحات**: Super Admin Hub (جداول + مخططات + بحث).
* **جداول**: `system_settings(key,value,scope)`، السجل التدقيقي.
* **Events**: `module.toggled`, `tenant.suspended`.

### \[GOV] Orchestrator

**الوصف**: توجيه الأحداث بين الموديولات مع إعادة المحاولة وDLQ.

* **ميزات**: Retry/Backoff، Dead‑Letter، Telemetry.
* **Dependencies**: Core.
* **لوحات**: مدمج في Super Admin.
* **Events**: يستهلك/ينشر جميع مواضيع الدومين (`order.created`… إلخ).

### \[SEC] Security

**الوصف**: المصادقة، التفويض، كشف الشذوذ، سجل تدقيقي غير قابل للتلاعب.

* **ميزات**: MFA (TOTP/SMS)، IP Allowlist، معدلات Throttling، توقيع جلسات.
* **Dependencies**: Core, Notifications.
* **لوحات**: Security Panel.
* **جداول**: `sessions`, `audit_logs(action,user_id,tenant_id,meta)`.
* **Events**: `auth.mfa_challenge`, `audit.logged`.

### \[GOV] Compliance

**الوصف**: ضرائب، فواتير PDF/QR، تقارير تدقيق.

* **ميزات**: قواعد ضرائب متعددة المناطق، تتبع الإصدارات، أختام زمنية.
* **Dependencies**: Core, Billing, Pricing.
* **لوحات**: Compliance Reports.
* **جداول**: `tax_rules`, `invoices`, `invoice_items`.
* **Events**: `invoice.issued`, `invoice.paid`.

### \[GOV] Integrations

**الوصف**: موائمات (Adapters) لبوابات الدفع، SMS، البريد، خرائط، شحن.

* **ميزات**: Webhooks مُوقّعة، تدوير مفاتيح، سجلات فشل مع إعادة المحاولة.
* **Dependencies**: Core, Security.
* **لوحات**: Integration Manager.
* **جداول**: `integration_configs(service,tenant_id,config_json)`, `webhook_logs`.
* **Events**: `webhook.received`, `provider.error`.

### \[GOV] Feature Flags & Branding

**الوصف**: أعلام خصائص لكل Tenant وتمات (Themes).

* **ميزات**: Toggles لكل موديول/ميزة، سمات CSS متغيرة ديناميكيًا.
* **Dependencies**: Core.
* **جداول**: `feature_flags(tenant_id,key,enabled)`, `themes(tenant_id,vars_json)`.

---

## 2) Domain‑Agnostic Business

### \[FIN] Billing & Subscription

**الوصف**: اشتراكات وخطط وفوترة ومدفوعات.

* **ميزات**: Proration، تعدد العملات، إشعارات تخلف.
* **Dependencies**: Core, Integrations, Compliance, Pricing.
* **لوحات**: Billing Dashboard.
* **جداول**: `plans`, `subscriptions`, `invoices`, `payments`.
* **Events**: `subscription.created`, `payment.failed`.

### \[PRC] Pricing

**الوصف**: قواعد تسعير ديناميكية.

* **ميزات**: خصومات زمنية/حجمية، A/B Pricing، Overrides لكل فرع.
* **Dependencies**: Core, Billing.
* **جداول**: `price_rules(tenant_id,scope,condition,formula)`.
* **Events**: `price.rule_applied`.

### \[ANL] Reporting & Analytics

**الوصف**: مؤشرات وتقارير مُخصّصة وتصدير.

* **ميزات**: KPIs لحظية، Drill‑down، جداول زمنية.
* **Dependencies**: جميع الموديولات كمصادر.
* **لوحات**: Analytics Hub.
* **جداول**: Views/Materialized Views.
* **Events**: `report.requested`.

### \[CRM] CRM

**الوصف**: ملفات العملاء، التقسيم، الحملات.

* **ميزات**: Segmentation سلوكي، أتمتة حملات، 360° Profile.
* **Dependencies**: Core, Integrations, Notifications.
* **لوحات**: CRM Console.
* **جداول**: `customers`, `segments`, `campaigns`, `activities`.
* **Events**: `customer.created`, `campaign.sent`.

### \[SCM] Inventory & Supply Chain

**الوصف**: مخزون، وصفات، موردون، مشتريات، تحويلات.

* **ميزات**: Multi‑warehouse، Batch/Lot، BOM/Recipes، مستويات إعادة الطلب.
* **Dependencies**: Core, Pricing, Reporting.
* **لوحات**: Inventory Manager، Supplier Portal.
* **جداول**: `items`, `uoms`, `recipes`, `stocks`, `warehouses`, `suppliers`, `purchase_orders`, `transfers`.
* **Events**: `stock.low`, `po.created`, `po.received`.

### \[HR] Employees & HR Lite

**الوصف**: الموظفون، الورديات، الحضور/الانصراف، إعدادات رواتب أساسية.

* **ميزات**: جدولة ورديات، تتبّع ساعات، مراجعات أداء.
* **Dependencies**: Core, Billing, Reporting.
* **لوحات**: HR Dashboard، Employee Self‑Service.
* **جداول**: `employees`, `shifts`, `attendances`, `payroll_runs`.
* **Events**: `shift.assigned`, `attendance.logged`.

### \[SUP] Support & Feedback

**الوصف**: تذاكر دعم، رضا العملاء، مراجعات.

* **ميزات**: SLA، قنوات متعددة، استطلاعات متفرعة.
* **Dependencies**: CRM, Integrations.
* **لوحات**: Support Desk.
* **جداول**: `tickets`, `ticket_replies`, `surveys`.
* **Events**: `ticket.opened`, `ticket.resolved`.

### \[NTF] Notifications

**الوصف**: قنوات الإشعارات (Email/SMS/Push/In‑App).

* **ميزات**: قوالب، جدولة، Tracking.
* **Dependencies**: Core, Integrations.
* **جداول**: `notification_templates`, `notification_logs`.
* **Events**: `notify.queued`, `notify.sent`.

### \[SCH] Calendar & Scheduling

**الوصف**: مواعيد، حجوزات داخلية، مهام دورية.

* **Dependencies**: Core.
* **جداول**: `events`, `reminders`.

### \[DOC] Documents & Media

**الوصف**: إدارة ملفات وسجلات، صلاحيات صفية.

* **Dependencies**: Core, Security.
* **جداول**: `media`, `documents`.

### \[SRCH] Search

**الوصف**: فهرسة نصية (Meilisearch/Elastic) مع نطاق Tenant.

* **Dependencies**: Core.
* **لوحات**: إعداد الفهرسة.

---

## 3) Domain‑Specific Suites

### \[HSP] Hospitality Suite (مطاعم/كافيهات)

**يشمل**: POS، KDS، إدارة المنيو/الأصناف، الطاولات، الحجوزات، الطلب أونلاين، الدليفري، الشيفتات، الكاش.

* **POS**: بيع، فواتير، خصومات، زِد/إكس Reports، Offline Sync.
* **KDS**: تحديث لحظي عبر WebSockets (Reverb)، شاشات أقسام.
* **Menu & Catalog**: أصناف/Modifers/UOM/Recipes.
* **Tables & Reservations**: تخطيط قاعة، حجز، دمج/نقل طاولات.
* **Online Ordering & Delivery**: قنوات خارجية، تتبع حالة، توجيه سائقين.
* **Dependencies**: Inventory, CRM, Pricing, Billing, Notifications.
* **لوحات**: POS Terminal، Kitchen Display، Floor Manager.
* **جداول**: `orders`, `order_items`, `kds_tickets`, `tables`, `reservations`, `deliveries`.
* **Events**: `order.created`, `order.paid`, `kds.ticket_routed`, `delivery.assigned`.

### \[ECM] E‑Commerce

* **ميزات**: متعدد البائعين، عربات مشتركة، شحن وتتبع.
* **Dependencies**: Inventory, Billing, CRM.
* **لوحات**: Vendor/Buyer Portals.
* **جداول**: `products`, `carts`, `cart_items`, `shipments`.

### \[REA] Real Estate & Leasing

* **ميزات**: بحث جغرافي، عقود إلكترونية، تحصيل دوري.
* **Dependencies**: CRM, Billing, Documents.
* **لوحات**: Owner/Tenant Portals.
* **جداول**: `properties`, `leases`, `rent_invoices`.

### \[HRX] Recruitment / Jobs

* **ميزات**: نشر وظائف، تتبع مرشحين، خط أنابيب توظيف.
* **Dependencies**: HR, CRM, Documents.
* **لوحات**: Hiring Manager, Candidate Portal.
* **جداول**: `jobs`, `applications`, `stages`.

---

## 4) Advanced Extensions

* **Forecasting/ML**: توقع الطلب/المخزون (SMA/EMA/ETS).
* **Advanced Pricing**: Happy Hour، Elasticity، Bundles.
* **Fraud & Risk**: قواعد ريسك للمدفوعات/الطلبات.
* **Edge/Offline Sync**: عقد طرفية للـ POS.
* **Data Export/Import**: CSV/Excel، تكامل ERP.
* **i18n/L10n**: ترجمات لكل Tenant.
* **Geo**: خرائط/مسارات.

---

## 5) Workflows (ملخصات تنفيذية)

* **Sales**: POS/Online → `order.created` → Pricing → Payment → Compliance (Invoice) → CRM Activity → Reporting.
* **Procurement**: Low Stock → `po.created` → Supplier Confirm → Receive → Recipe/Stock Update → Reporting.
* **KDS**: `order_item.queued` → Route to station → `kds.ticket_done` → Notify POS/Customer.
* **HR**: Post Job → Applications → Stages → Hire → Shift Assign → Payroll.
* **Support**: Ticket Open → Triage → Resolve → CSAT Survey.

---

## 6) Events & Topics (عقد رئيسية)

* `tenant.*`, `user.*`, `auth.*`
* `order.*`, `payment.*`, `invoice.*`, `stock.*`, `po.*`
* `kds.*`, `delivery.*`, `report.*`, `notify.*`

---

## 7) مصفوفة التفعيل (مثال)

| Module      | Required            | Optional |
| ----------- | ------------------- | -------- |
| Core        | ✅                   | —        |
| Security    | ✅                   | —        |
| Billing     | ✅                   | —        |
| Pricing     | —                   | ✅        |
| CRM         | —                   | ✅        |
| Inventory   | ✅ (للضيافة/التجارة) | —        |
| Hospitality | —                   | ✅        |
| E‑Commerce  | —                   | ✅        |
| Real Estate | —                   | ✅        |
| Recruitment | —                   | ✅        |

---

## 8) معايير الجودة

* جميع الجداول تحتوي `tenant_id` + فهارس مركبة.
* كل API خلف `X‑Tenant` أو Context Resolver Domain.
* كل حدث قابل لإعادة التشغيل (idempotent handlers).
* سجلات تدقيق شاملة لكل تغيّر حال.

---

# TECH\_GUIDE.md — EliteSaaS / CafeOS Engineering Guide

> **الغرض**: مرجع تنفيذي موحد لبناء المنصة: معمارية، معايير كود، بنية الملفات، مخططات قاعدة البيانات، التصميم البرمجي، الـ APIs، الجبهة الأمامية، الأمان، الاختبارات، والنشر.

---

## 1) المعمارية

* **Stack**: Laravel 12 (PHP 8.3+), MySQL 8, Redis, Reverb WebSockets, Inertia/Vue 3 + Pinia + Vite, Filament (Super Admin), Horizon (Queues), Meilisearch/Elastic (اختياري).
* **Multi‑Tenancy**: قاعدة مشتركة بعزل `tenant_id` + Global Scopes + Middleware لاستخلاص السياق من الدومين/الهيدر.
* **قابلية التوسع**: موازنة أحمال، توسيع أفقياً، فصل خدمات زمن‑حقيقي (Reverb) وعمال الطوابير.
* **التكامل**: موائمات (Adapters) عبر طبقة Integrations، Webhooks موقّعة.

## 2) حزم أساسية (Composer/NPM)

* `stancl/tenancy`، `laravel/sanctum`، `spatie/laravel-permission`، `laravel/horizon`، `laravel/reverb`، `spatie/laravel-activitylog`، `barryvdh/laravel-dompdf`، `maatwebsite/excel`، `pestphp/pest`, `laravel/pint`, `phpstan/phpstan`.
* Frontend: Vue 3, Pinia, Vue Router, Axios.

> **ملاحظة**: ثبّت إصدارات متوافقة مع Laravel 12، وتجنب خلط إصدارات Filament (استخدم نفس Major عبر الموارد).

## 3) بنية الملفات (Monorepo داخل Laravel)

```
EliteSaaS/
├─ app/
│  ├─ Domains/
│  │  ├─ Core/
│  │  │  ├─ Models/Tenant.php
│  │  │  ├─ Services/TenantService.php
│  │  │  ├─ Repositories/TenantRepository.php
│  │  │  └─ Policies/
│  │  ├─ Security/ …
│  │  ├─ Billing/ …
│  │  ├─ Pricing/ …
│  │  ├─ CRM/ …
│  │  ├─ SCM/ … (Inventory & Supply Chain)
│  │  ├─ HR/ …
│  │  ├─ Hospitality/ (POS,KDS,Menu,Tables,…)
│  │  ├─ ECommerce/
│  │  ├─ RealEstate/
│  │  └─ Recruitment/
│  ├─ Events/ (Domain events)
│  ├─ Listeners/
│  ├─ Jobs/
│  ├─ Http/
│  │  ├─ Controllers/Api/V1/
│  │  ├─ Middleware/TenantContext.php
│  │  └─ Resources/ (API Transformers)
│  ├─ Support/ (DTOs, Enums, Helpers)
│  └─ Observers/
├─ bootstrap/
├─ config/ (tenancy.php, horizon.php, reverb.php, services.php)
├─ database/
│  ├─ migrations/
│  ├─ seeders/
│  └─ factories/
├─ resources/
│  ├─ js/ (app.ts, stores/, components/, pages/)
│  ├─ views/ (Blade for Filament & emails)
│  └─ lang/
├─ routes/
│  ├─ api_v1_core.php
│  ├─ api_v1_hospitality.php
│  └─ web.php
├─ tests/
│  ├─ Unit/
│  ├─ Feature/
│  └─ Browser/ (Dusk – اختياري)
└─ ops/ (CI, deploy, scripts)
```

## 4) سياق المستأجر (Tenant Context)

* **Middleware** `TenantContext`: يستنتج المستأجر من الدومين الفرعي أو هيدر `X-Tenant`، ويضبط **Scope** عمومي.
* **Trait** `BelongsToTenant` يحقن `tenant_id` تلقائيًا عند الإنشاء.

### Trait مثال

```php
<?php
namespace App\Support\Tenancy;

use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model) {
            if (tenant()) {
                $model->tenant_id = tenant('id');
            }
        });
        static::addGlobalScope('tenant', function ($builder) {
            if (tenant()) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', tenant('id'));
            }
        });
    }
}
```

## 5) نمط الخدمات/المستودعات (Service/Repository)

* **Repository** للاستعلامات المعقدة + **Service** للمنطق. اجعل الـ Controller نحيفًا.

```php
class OrderService
{
    public function create(array $data): Order
    {
        // التحقق/التسعير/الأحداث…
        $order = Order::create($data);
        event(new OrderCreated($order->id));
        return $order;
    }
}
```

## 6) الأحداث والطوابير

* تسمية معيارية: `domain.action` (مثال: `order.created`).
* جميع الـ Listeners **Idempotent** وتستخدم Backoff + حد أقصى للمحاولات + تحويل إلى DLQ.
* استخدم Horizon للمراقبة.

## 7) التصميم البياني وقاعدة البيانات

* كل جداول الدومين تحتوي `tenant_id` + فهارس مركبة (`tenant_id,status|date`).
* علاقات صريحة بالمفاتيح الخارجية مع `on delete cascade` حيث يلزم.
* مثال مختصر لـ Orders:

```sql
CREATE TABLE orders (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  tenant_id BIGINT NOT NULL,
  customer_id BIGINT NULL,
  amount DECIMAL(12,2) NOT NULL,
  status VARCHAR(32) NOT NULL,
  channel VARCHAR(16) NOT NULL,
  created_at TIMESTAMP NULL,
  INDEX idx_orders_tenant_status (tenant_id, status),
  INDEX idx_orders_tenant_created (tenant_id, created_at)
);
```

## 8) تصميم الـ API

* **REST v1**: `/api/v1/...`، مصادقة **Sanctum** (Bearer).
* **معيار الأخطاء**: `{ "message": string, "errors": {field:[..]} }`.
* **محدد المعدل**: حسب Tenant/Token.
* **مثال مسارات أساسية**:

```php
Route::prefix('api/v1')->middleware(['auth:sanctum','tenant'])
  ->group(function () {
    Route::get('/tenants/{id}', [TenantController::class,'show']);
    Route::post('/orders', [OrderController::class,'store']);
    Route::get('/orders/{id}', [OrderController::class,'show']);
  });
```

## 9) الواجهة الأمامية (Vue/Inertia)

* **مجلدات**: `resources/js/components`, `pages`, `stores` (Pinia), `services/api.ts`.
* **Themes**: متغيرات CSS لكل Tenant من جدول `themes`.
* **Realtime**: الاشتراك في قنوات Reverb بـ Token.

## 10) الأمان

* **MFA** (TOTP/SMS) عبر Security Module.
* **RBAC**: `spatie/laravel-permission` مع **سياسات** و**بوابات**.
* **التدقيق**: سجل نشاط/تغييرات (activitylog + audit\_logs).
* **سرية المفاتيح**: تخزين آمن مشفر (Vault/DB encrypted cols).

## 11) المراقبة والجودة

* **Sentry/Telescope** (حسب البيئة) + Logs مُهيكلة (JSON).
* **مقاييس**: زمن استجابة، معدلات خطأ، Throughput لكل Tenant.
* **الجودة**: Pint (تنسيق)، PHPStan (تحليل)، Pest/PHPUnit (>80% تغطية للوحدات الحرجة).

## 12) CI/CD (GitHub Actions مثال مختصر)

```yaml
name: laravel-ci
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: test
        ports: ['3306:3306']
        options: >-
          --health-cmd="mysqladmin ping -proot" --health-interval=10s --health-timeout=5s --health-retries=3
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with: { php-version: '8.3', extensions: mbstring, intl, pdo_mysql, redis }
      - name: Install PHP deps
        run: composer install --no-interaction --prefer-dist
      - name: Copy .env
        run: cp .env.example .env
      - name: App key
        run: php artisan key:generate
      - name: Migrate
        env: { DB_HOST: 127.0.0.1, DB_DATABASE: test, DB_USERNAME: root, DB_PASSWORD: root }
        run: php artisan migrate --force
      - name: Build frontend
        run: |
          npm ci
          npm run build
      - name: Static checks
        run: |
          vendor/bin/pint -v
          vendor/bin/phpstan analyse --no-progress
      - name: Tests
        run: ./vendor/bin/pest -q
```

## 13) التشغيل المحلي

* `composer install && npm i`
* `cp .env.example .env && php artisan key:generate`
* ضبط MySQL وRedis محليًا → `php artisan migrate --seed`
* تشغيل Reverb/Horizon: `php artisan reverb:start & php artisan horizon`
* تشغيل Vite: `npm run dev`

## 14) النشر (Runbook سريع)

* **أول مرة**: رفع الكود → إعداد `.env` (مفاتيح، DB) → `php artisan key:generate` → `migrate --force` → seed للـ Super Admin → إعداد CRON للـ queue/schedule.
* **تحديث**: وضع صيانة → Pull/Artifacts → Migrate → Cache Clear/Warm → خروج من الصيانة.
* **Rollback**: احتفظ بنسخ DB (`mysqldump`) + نسخ الإصدار السابق.

## 15) أمثلة تكوين `.env` (مقتطف)

```
APP_NAME=CafeOS
APP_ENV=production
APP_KEY=base64:...
APP_URL=https://tenant1.example.com

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

BROADCAST_CONNECTION=reverb
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
REVERB_HOST=...
REVERB_PORT=6001
```

## 16) Module Registry (مثال JSON)

```json
{
  "modules": [
    {"key":"core","required":true},
    {"key":"security","required":true},
    {"key":"billing","required":true},
    {"key":"pricing"},
    {"key":"crm"},
    {"key":"inventory"},
    {"key":"hospitality"},
    {"key":"ecommerce"},
    {"key":"realestate"},
    {"key":"recruitment"}
  ]
}
```

## 17) سياسات التسمية

* **Events**: `domain.action` (`order.created`).
* **Permissions**: `module.action` (`pos.refund`).
* **Routes**: `/api/v1/{module}/...`.
* **جداول**: مفرد، snake\_case، يحوي `tenant_id`.

## 18) خارطة الطريق (Sprints موجزة)

* **S0**: Setup & Tenancy & Security & CI.
* **S1**: Billing + Pricing + SuperAdmin UI.
* **S2**: Inventory + Recipes + Suppliers.
* **S3**: POS + Orders + Payments + Cash Reports.
* **S4**: KDS + Realtime Events.
* **S5**: CRM + Notifications + Coupons/Loyalty.
* **S6**: Reports/Analytics + Exports.
* **S7**: Online Ordering + Tables/Reservations.
* **S8**: Marketplace + Integrations Hub.
* **S9**: Recruitment/RealEstate/E‑Commerce (قابلة للتشغيل حسب أولوية السوق).
* **S10**: Advanced Pricing/Forecasting/Offline Edge.

## 19) نصائح تنفيذ حرجة

* تأكّد من توافق إصدارات Filament ومكتباتها مع Laravel 12 لتجنب أخطاء `Form`/`Schema`.
* استخدم Reverb بدل WebSockets القديمة لمواءمة Laravel 12.
* لا تخزّن أسرار مزوّدي الدفع/SMS في Config، استخدم مخزن أسرار/أعمدة مُشفّرة.
* اجعل كل Listener آمنًا لإعادة التشغيل واصنع **Keys للتعريف** (idempotency keys).

— انتهى —
