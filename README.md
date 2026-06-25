<div align="center">

<img src="./assets/tkamel.png" alt="TAKAMOL Logo" width="380">

# 🤝 تكامل | TAKAMOL

### نظام إدارة متكامل للجمعيات الأهلية في المملكة العربية السعودية

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.0-646CFF?style=flat&logo=vite&logoColor=white)](https://vitejs.dev)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

[نظرة عامة](#-نظرة-عامة) •
[المزايا](#-المزايا-الرئيسية) •
[التقنيات](#%EF%B8%8F-التقنيات-المستخدمة) •
[التثبيت](#-التثبيت-والتشغيل) •
[البنية](#-بنية-المشروع) •
[الأدوار](#-نظام-الأدوار-والصلاحيات)

</div>

---

## 📋 نظرة عامة

**تكامل (TAKAMOL)** هو نظام إدارة متكامل مبني باستخدام **Laravel** مخصص لإدارة عمليات الجمعيات غير الربحية في المملكة العربية السعودية. يوفر النظام واجهة عربية (RTL) كاملة تُمكّن الجمعيات من تنظيم الفرص التطوعية، إدارة المشاريع المشتركة، جدولة الاجتماعات، ومتابعة طلبات الخدمات، ضمن منظومة موحّدة تخدم ثلاث فئات من المستخدمين: **الإدارة العامة (Admin)**، **الجمعيات (Association)**، و**المستخدمين/المتطوعين (User)**.

تم تصميم لوحة تحكم الإدارة بمعمارية **SPA (Single Page Application)** جزئية، حيث يتم تحميل الأقسام (الاجتماعات، الإعدادات، لوحة المعلومات...) ديناميكيًا داخل صفحة موحّدة (`consulting.blade.php`) عبر استدعاءات API، مما يوفر تجربة استخدام سلسة وسريعة دون الحاجة لإعادة تحميل الصفحة بالكامل.

---

## ✨ المزايا الرئيسية

| الميزة | الوصف |
|---|---|
| 🏢 **إدارة الجمعيات** | تسجيل ومتابعة طلبات انضمام الجمعيات، وتصنيفها حسب الفئات (`AssociationCategory`) |
| 🙋 **الفرص التطوعية** | نشر الفرص (`Opportunities`)، استقبال طلبات المتطوعين، وتحديد المستهدفين (`OpportunityTargets`) |
| 🤝 **المشاريع المشتركة** | إدارة المشاريع التعاونية بين الجمعيات مع تتبع التحديثات (`JointProjectUpdate`) |
| 📅 **نظام الاجتماعات** | جدولة اجتماعات بحضور متعدد، أجندات (`MeetingAgendaItem`)، مستهدفين، ودعوات بصلاحيات اتجاه (Invitation Direction) |
| 🛎️ **طلبات الخدمات** | تقديم ومتابعة طلبات الخدمة (`ServiceRequest`) من قِبل المستخدمين والجمعيات |
| 🔔 **الإشعارات** | نظام إشعارات داخلي للمستخدمين والإدارة لمتابعة كل المستجدات |
| 💬 **الرسائل** | نظام تراسل داخلي (`Message`) بين الأطراف المختلفة |
| 🖥️ **لوحة تحكم SPA** | تنقّل سلس بين الأقسام الإدارية عبر `showSection()` دون إعادة تحميل الصفحة |
| 🌐 **دعم RTL كامل** | واجهة عربية بالكامل مع تصميم مخصص يدعم الاتجاه من اليمين لليسار |
| 🔐 **مصادقة وصلاحيات** | نظام أدوار (Roles) مع Middleware مخصص للتحقق من الهوية والصلاحيات |

---

## 🛠️ التقنيات المستخدمة

**Backend**
- [Laravel 12](https://laravel.com) — إطار العمل الأساسي
- [Laravel Sanctum](https://laravel.com/docs/sanctum) — المصادقة عبر API
- PHP 8.2+

**Frontend**
- [Tailwind CSS 4](https://tailwindcss.com) — التنسيق والتصميم
- [Vite 7](https://vitejs.dev) — أداة البناء والتجميع
- Vanilla JavaScript (وحدات SPA مخصصة: `dashboard-spa.js`, `settings-spa.js`, `meetings.js`, `joint-projects.js`)
- [Axios](https://axios-http.com) — استدعاءات API

**أدوات التطوير**
- [Pest / PHPUnit](https://phpunit.de) — الاختبارات
- [Laravel Pint](https://laravel.com/docs/pint) — تنسيق الكود
- [Faker](https://fakerphp.github.io) — توليد بيانات تجريبية

---

## 🏗️ بنية المشروع

```
TAKAMOL/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # متحكمات لوحة الإدارة (Meetings, JointProjects, Opportunities...)
│   │   │   ├── User/           # متحكمات واجهة المستخدم/المتطوع
│   │   │   ├── Auth/           # تسجيل الدخول والتسجيل
│   │   │   └── DashboardController.php
│   │   ├── Middleware/         # AuthMiddleware, RoleMiddleware
│   │   └── Requests/           # FormRequests لكل نموذج (Meeting, JointProject, ServiceRequest...)
│   └── Models/                 # Association, Opportunity, Meeting, JointProject, Notification...
├── database/
│   └── migrations/             # هجرات قاعدة البيانات الكاملة
├── resources/
│   └── views/
│       ├── layouts/
│       ├── user/
│       └── auth/
├── routes/
│   ├── web.php                 # المسارات حسب الدور (Admin / User / Association)
│   └── api.php
└── public/
```

---

## 🔐 نظام الأدوار والصلاحيات

يعتمد النظام على ثلاث فئات مستخدمين، يُحدَّد الوصول لكل منها عبر `RoleMiddleware`:

| الدور | الوصف | أمثلة على الصلاحيات |
|---|---|---|
| **Admin** | الإدارة العامة للنظام | إدارة جميع الجمعيات، الموافقة على الطلبات، إدارة الاجتماعات والفرص والمشاريع |
| **Association** | الجمعيات الأهلية | إدارة الفرص التطوعية الخاصة بها، المشاركة في المشاريع المشتركة، طلبات الخدمات |
| **User** | المستخدم/المتطوع | التصفح والتسجيل في الفرص التطوعية، حضور الاجتماعات، تقديم طلبات الخدمات |

---

## ⚙️ التثبيت والتشغيل

### المتطلبات الأساسية

- PHP `^8.2`
- Composer
- Node.js وNPM
- قاعدة بيانات (SQLite بشكل افتراضي، أو MySQL/PostgreSQL)

### خطوات التثبيت

```bash
# 1. استنساخ المشروع
git clone <repository-url>
cd TAKAMOL

# 2. تثبيت اعتمادات PHP
composer install

# 3. نسخ ملف البيئة وتوليد المفتاح
cp .env.example .env
php artisan key:generate

# 4. تثبيت اعتمادات Node
npm install

# 5. تنفيذ الهجرات
php artisan migrate

# 6. (اختياري) تشغيل بيانات تجريبية
php artisan db:seed
```

### تشغيل بيئة التطوير

يحتوي المشروع على سكربت موحّد يشغّل السيرفر، الطابور (Queue)، السجلات (Pail)، وVite معًا:

```bash
composer run dev
```

أو تشغيل كل خدمة بشكل منفصل:

```bash
php artisan serve              # السيرفر المحلي
php artisan queue:listen       # معالجة الطابور
npm run dev                    # Vite (تجميع الأصول لحظيًا)
```

### تشغيل الاختبارات

```bash
composer run test
```

---

## 📡 أمثلة على نقاط API الداخلية

| Method | Endpoint | الوصف |
|---|---|---|
| `GET` | `/api/dashboard` | جلب بيانات لوحة المعلومات (SPA) |
| `GET` | `/api/meetings` | قائمة الاجتماعات |
| `POST` | `/meetings` | إنشاء اجتماع جديد |
| `POST` | `/api/meetings/{meeting}/join` | الانضمام لاجتماع وإشعار الإدارة |
| `GET` | `/api/meetings/{meeting}/attendees` | الحاضرين في اجتماع معيّن |

> ملاحظة: تم تطبيق Rate Limiting (`throttle:api`, `throttle:login`) على مسارات المصادقة وواجهة الـ API لحماية النظام من الطلبات المفرطة.

---

## 🤝 المساهمة

المساهمات مرحب بها! يُرجى فتح Issue لمناقشة أي تغيير جوهري قبل تقديم Pull Request.

```bash
git checkout -b feature/amazing-feature
git commit -m "إضافة ميزة رائعة"
git push origin feature/amazing-feature
```

---

## 📄 الترخيص

هذا المشروع مرخّص بموجب [MIT License](LICENSE).

---

<div align="center">

صُنع بـ ❤️ لخدمة القطاع غير الربحي في المملكة العربية السعودية

</div>
