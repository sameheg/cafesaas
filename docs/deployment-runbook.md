# دليل النشر والتحديث والاسترجاع

## 1. النشر لأول مرة

1. **رفع الكود** إلى الخادم (نسخ أو استخدام Git).
2. تثبيت الاعتمادات: `composer install --no-dev -o` ثم `npm ci && npm run build` إذا كان الواجهة مطلوبة.
3. نسخ ملف البيئة: `cp .env.example .env` وتحديث الإعدادات (قاعدة البيانات، المفاتيح، Redis، Reverb...).
4. توليد مفتاح التطبيق: `php artisan key:generate`.
5. تشغيل الترحيلات: `php artisan migrate --force` ثم تهيئة السوبر أدمن عبر Seeder إن وجدت.
6. إعداد مهام CRON للـ Queue/Schedule: `* * * * * php /path/artisan schedule:run >> /dev/null 2>&1`.

## 2. إجراءات التحديث

1. إدخال التطبيق في وضع الصيانة: `php artisan down`.
2. جلب التحديثات أو نشر الحزمة الجديدة وتثبيت الاعتمادات (`composer install --no-dev -o`, `npm ci && npm run build`).
3. تشغيل الترحيلات: `php artisan migrate --force`.
4. تنظيف الكاش ثم إعادة توليده:
   ```bash
   php artisan cache:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
5. الخروج من وضع الصيانة: `php artisan up`.

## 3. خطة الاسترجاع (Rollback)

1. **نسخ احتياطية مسبقة**:
   - قاعدة البيانات: `mysqldump -uUSER -p DB > backup.sql`.
   - نسخة من الإصدار السابق (Zip أو مجلد). 
2. عند الحاجة للاسترجاع:
   1. تفعيل وضع الصيانة: `php artisan down`.
   2. استعادة قاعدة البيانات: `mysql -uUSER -p DB < backup.sql`.
   3. استبدال الكود بالإصدار السابق.
   4. تنظيف وإعادة توليد الكاش كما في خطوات التحديث.
   5. الخروج من وضع الصيانة والتأكد من سلامة النظام.
