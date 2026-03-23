<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        UnitType::query()->delete();

        $types = [
            // فيلا
            [
                'name' => 'Villa',
                'name_ar' => 'فيلا',
                'fields' => [
                    // المساحات
                    ['name' => 'total_area', 'label' => 'المساحة بالكامل (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    ['name' => 'garden_area', 'label' => 'مساحة الجاردن (م²)', 'type' => 'text', 'rules' => 'nullable|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    // الأرقام
                    ['name' => 'floors', 'label' => 'عدد الطوابق', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'rooms', 'label' => 'عدد الغرف', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'bathrooms', 'label' => 'عدد الحمامات', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'kitchens', 'label' => 'عدد المطابخ', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'licensed', 'label' => 'مرخصة'],
                        ['value' => 'unlicensed', 'label' => 'غير مرخصة'],
                        ['value' => 'pending', 'label' => 'تحت الترخيص'],
                    ], 'rules' => 'required|string'],
                    ['name' => 'finishing_type', 'label' => 'التشطيب', 'type' => 'select', 'options' => [
                        ['value' => 'half', 'label' => 'نصف تشطيب'],
                        ['value' => 'full', 'label' => 'تشطيب كامل'],
                        ['value' => 'super_lux', 'label' => 'سوبر لوكس'],
                    ], 'rules' => 'required|string'],
                    // الميزات الإضافية
                    ['name' => 'has_pool', 'label' => 'بها حمام سباحة؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_garage', 'label' => 'بها جراج؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_guard_room', 'label' => 'بها غرفة حارس؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                ],
            ],

            // أرض
            [
                'name' => 'Land',
                'name_ar' => 'أرض',
                'fields' => [
                    // المساحات
                    ['name' => 'area', 'label' => 'المساحة (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    ['name' => 'street_width', 'label' => 'عرض الشارع (متر)', 'type' => 'text', 'rules' => 'nullable|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    // الأرقام
                    ['name' => 'floors', 'label' => 'عدد الطوابق', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'basements', 'label' => 'عدد البدرومات', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'apartments_count', 'label' => 'عدد الشقق', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'apartments_per_floor', 'label' => 'عدد الشقق في الدور الواحد', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'rooms_per_apartment', 'label' => 'عدد الغرف في الشقة الواحدة', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'not_applied', 'label' => 'لسه متعملتش'],
                        ['value' => 'in_progress', 'label' => 'في الجهاز'],
                        ['value' => 'ready', 'label' => 'جاهزة'],
                    ], 'rules' => 'required|string'],
                    // الميزات الإضافية
                    ['name' => 'has_garage', 'label' => 'بها جراج؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_roof', 'label' => 'بها روف؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_commercial_shop', 'label' => 'بها محل تجاري؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                ],
            ],

            // عمارة
            [
                'name' => 'Building',
                'name_ar' => 'عمارة',
                'fields' => [
                    // المساحات
                    ['name' => 'land_area', 'label' => 'مساحة الأرض (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    ['name' => 'apartment_area', 'label' => 'مساحة الشقة (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    ['name' => 'floor_area', 'label' => 'مساحة الدور (م²)', 'type' => 'text', 'rules' => 'nullable|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'apartment_price', 'label' => 'سعر الشقة', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'roof_price', 'label' => 'سعر الروف', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    // الأرقام
                    ['name' => 'floors', 'label' => 'عدد الطوابق', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'basements', 'label' => 'عدد البدرومات', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'rooms', 'label' => 'عدد الغرف', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'bathrooms', 'label' => 'عدد الحمامات', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                    ['name' => 'apartments_count', 'label' => 'عدد الشقق', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'not_applied', 'label' => 'لسه متعملتش'],
                        ['value' => 'in_progress', 'label' => 'في الجهاز'],
                        ['value' => 'ready', 'label' => 'جاهزة'],
                    ], 'rules' => 'required|string'],
                    ['name' => 'finish_type', 'label' => 'التشطيب', 'type' => 'select', 'options' => [
                        ['value' => 'half', 'label' => 'نص تشطيب'],
                        ['value' => 'finished', 'label' => 'تشطيب'],
                        ['value' => 'super_lux', 'label' => 'سوبر لوكس'],
                    ], 'rules' => 'required|string'],
                    // الميزات الإضافية
                    ['name' => 'has_garage', 'label' => 'بها جراج؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_guard_room', 'label' => 'بها غرفة حارس؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                    ['name' => 'has_commercial_shop', 'label' => 'بها محل تجاري؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                ],
            ],

            // شقة
            [
                'name' => 'Apartment',
                'name_ar' => 'شقة',
                'fields' => [
                    // المساحات
                    ['name' => 'area', 'label' => 'مساحة الشقة (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    // الأرقام
                    ['name' => 'rooms', 'label' => 'عدد الغرف', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'bathrooms', 'label' => 'عدد الحمامات', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'not_applied', 'label' => 'لسه متعملتش'],
                        ['value' => 'in_progress', 'label' => 'في الجهاز'],
                        ['value' => 'ready', 'label' => 'جاهزة'],
                    ], 'rules' => 'required|string'],
                    ['name' => 'finish_type', 'label' => 'التشطيب', 'type' => 'select', 'options' => [
                        ['value' => 'half', 'label' => 'نص تشطيب'],
                        ['value' => 'finished', 'label' => 'تشطيب'],
                        ['value' => 'super_lux', 'label' => 'سوبر لوكس'],
                    ], 'rules' => 'required|string'],
                ],
            ],

            // محل
            [
                'name' => 'Shop',
                'name_ar' => 'محل',
                'fields' => [
                    // المساحات
                    ['name' => 'total_area', 'label' => 'المساحة بالكامل (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'not_applied', 'label' => 'لسه متعملتش'],
                        ['value' => 'in_progress', 'label' => 'في الجهاز'],
                        ['value' => 'ready', 'label' => 'جاهزة'],
                    ], 'rules' => 'required|string'],
                    ['name' => 'finish_type', 'label' => 'التشطيب', 'type' => 'select', 'options' => [
                        ['value' => 'half', 'label' => 'نص تشطيب'],
                        ['value' => 'finished', 'label' => 'تشطيب'],
                        ['value' => 'super_lux', 'label' => 'سوبر لوكس'],
                    ], 'rules' => 'required|string'],
                    // الميزات الإضافية
                    ['name' => 'license_type', 'label' => 'نوع الرخصة؟', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                ],
            ],

            // مكتب
            [
                'name' => 'Office',
                'name_ar' => 'مكتب',
                'fields' => [
                    // المساحات
                    ['name' => 'total_area', 'label' => 'المساحة بالكامل (م²)', 'type' => 'text', 'rules' => 'required|string'],
                    ['name' => 'reception_area', 'label' => 'مساحة الريسبشن (م²)', 'type' => 'text', 'rules' => 'nullable|string'],
                    // الأسعار
                    ['name' => 'price_per_meter', 'label' => 'سعر المتر', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    ['name' => 'total_price', 'label' => 'السعر بالكامل', 'type' => 'number', 'rules' => 'required|numeric|min:0'],
                    // الأرقام
                    ['name' => 'offices_count', 'label' => 'عدد المكاتب', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    ['name' => 'bathrooms', 'label' => 'عدد الحمامات', 'type' => 'number', 'rules' => 'required|numeric|min:1'],
                    // الوصف
                    ['name' => 'description', 'label' => 'الوصف', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    // الحالة والتشطيب
                    ['name' => 'license_status', 'label' => 'حالة الرخصة', 'type' => 'select', 'options' => [
                        ['value' => 'not_applied', 'label' => 'لسه متعملتش'],
                        ['value' => 'in_progress', 'label' => 'في الجهاز'],
                        ['value' => 'ready', 'label' => 'جاهزة'],
                    ], 'rules' => 'required|string'],
                    ['name' => 'finish_type', 'label' => 'التشطيب', 'type' => 'select', 'options' => [
                        ['value' => 'half', 'label' => 'نص تشطيب'],
                        ['value' => 'finished', 'label' => 'تشطيب'],
                        ['value' => 'super_lux', 'label' => 'سوبر لوكس'],
                    ], 'rules' => 'required|string'],
                    // الميزات الإضافية
                    ['name' => 'has_buffet', 'label' => 'بها بوفية؟', 'type' => 'checkbox', 'rules' => 'boolean'],
                ],
            ],
        ];

        foreach ($types as $t) {
            UnitType::create($t);
        }
    }
}
