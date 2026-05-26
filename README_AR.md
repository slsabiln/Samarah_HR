# نظام الموارد البشرية - Laravel

نظام موارد بشرية لمستخدم واحد، جاهز لإدخال البيانات الفعلية بدون بيانات موظفين تجريبية.

## الوحدات

- الموظفون
- الرواتب الشهرية بالدينار الكويتي
- الإجازات
- القروض والسلف
- الوثائق الرسمية والتنبيه قبل الانتهاء
- الجزاءات
- الحضور والانصراف
- التدريب والتطوير
- التقارير
- سجل التدقيق Audit Trail

## التشغيل

```powershell
cd C:\Users\admin\projects
Expand-Archive C:\Users\admin\Downloads\samara-hrm-laravel.zip -DestinationPath .
cd .\samara-hrm-laravel
copy .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

افتح المتصفح:

```text
http://127.0.0.1:8000/login
```

بيانات الدخول الافتراضية:

```text
Email: nabeel@hr.local
Password: password
```

بعد الدخول يفضل تغيير كلمة المرور من قاعدة البيانات أو إضافة شاشة تغيير كلمة مرور لاحقاً حسب سياسة التشغيل.

## ملاحظات تشغيلية

- قاعدة البيانات الافتراضية: `samara_hrm`.
- لا توجد بيانات موظفين أو رواتب أو وثائق وهمية داخل Seeder.
- Seeder ينشئ مستخدم النظام فقط حتى تستطيع الدخول وبدء الإدخال.
- النظام يستخدم ملفات CSS/JS مباشرة بدون Node حتى يكون تشغيله أسرع.
