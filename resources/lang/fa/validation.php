<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (فارسی)
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used
    | by the validator class. Some of these rules have multiple versions
    | such as the size rules. Feel free to tweak each of these messages
    | here.
    |
    */

    'accepted' => 'فیلد :attribute باید پذیرفته شود.',
    'accepted_if' => 'فیلد :attribute باید زمانی پذیرفته شود که :other برابر با :value باشد.',
    'active_url' => 'فیلد :attribute یک آدرس اینترنتی معتبر نیست.',
    'array' => 'فیلد :attribute باید یک آرایه باشد.',
    'ascii' => 'فیلد :attribute باید فقط شامل کاراکترها و اعداد لاتین باشد.',
    'before' => 'فیلد :attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal' => 'فیلد :attribute باید تاریخی قبل یا برابر با :date باشد.',
    'between' => [
        'array' => 'فیلد :attribute باید بین :min و :max آیتم باشد.',
        'file' => 'فیلد :attribute باید بین :min و :max کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بین :min و :max باشد.',
        'string' => 'فیلد :attribute باید بین :min و :max کاراکتر باشد.',
    ],
    'boolean' => 'فیلد :attribute باید درست یا غلط باشد.',
    'can' => 'فیلد :attribute شامل مقدار غیرمجاز است.',
    'confirmed' => 'تأیید فیلد :attribute با مطابقت ندارد.',
    'contains' => 'فیلد :attribute باید شامل یکی از مقادیر زیر باشد: :values.',
    'current_password' => 'رمز عبور فعلی اشتباه است.',
    'date' => 'فیلد :attribute یک تاریخ معتبر نیست.',
    'date_equals' => 'فیلد :attribute باید تاریخی برابر با :date باشد.',
    'date_format' => 'فیلد :attribute با قالب :format مطابقت ندارد.',
    'decimal' => 'فیلد :attribute باید شامل :decimal رقم اعشار باشد.',
    'declined' => 'فیلد :attribute باید رد شود.',
    'declined_if' => 'فیلد :attribute باید زمانی رد شود که :other برابر با :value باشد.',
    'different' => 'فیلد :attribute و فیلد :other باید متفاوت باشند.',
    'digits' => 'فیلد :attribute باید :digits رقم باشد.',
    'digits_between' => 'فیلد :attribute باید بین :min و :max رقم باشد.',
    'dimensions' => 'فیلد :attribute دارای ابعاد تصویر نامعتبر است.',
    'distinct' => 'فیلد :attribute دارای مقدار تکراری است.',
    'doesnt_contain' => 'فیلد :attribute نباید شامل هیچ یک از موارد زیر باشد: :values.',
    'doesnt_end_with' => 'فیلد :attribute نباید با یکی از این موارد تمام شود: :values.',
    'doesnt_start_with' => 'فیلد :attribute نباید با یکی از این موارد شروع شود: :values.',
    'email' => 'فیلد :attribute باید یک آدرس ایمیل معتبر باشد.',
    'ends_with' => 'فیلد :attribute باید با یکی از این موارد تمام شود: :values.',
    'enum' => 'مقدار انتخابی برای :attribute معتبر نیست.',
    'exists' => 'مقدار انتخاب شده برای فیلد :attribute معتبر نیست.',
    'extensions' => 'فیلد :attribute باید دارای پسوندهای :values باشد.',
    'file' => 'فیلد :attribute باید یک فایل باشد.',
    'filled' => 'فیلد :attribute باید مقداری داشته باشد.',
    'gt' => [
        'array' => 'فیلد :attribute باید بیش از :value آیتم داشته باشد.',
        'file' => 'فیلد :attribute باید بزرگ‌تر از :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بزرگ‌تر از :value باشد.',
        'string' => 'فیلد :attribute باید بیش از :value کاراکتر باشد.',
    ],
    'gte' => [
        'array' => 'فیلد :attribute باید :value آیتم یا بیشتر داشته باشد.',
        'file' => 'فیلد :attribute باید بزرگ‌تر یا برابر :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید بزرگ‌تر یا برابر :value باشد.',
        'string' => 'فیلد :attribute باید :value کاراکتر یا بیشتر باشد.',
    ],
    'hex_color' => 'فیلد :attribute باید یک کد رنگ هگزادسیمال معتبر باشد.',
    'image' => 'فیلد :attribute باید یک تصویر باشد.',
    'in' => 'مقدار انتخاب شده برای :attribute معتبر نیست.',
    'in_array' => 'فیلد :attribute باید در :other وجود داشته باشد.',
    'integer' => 'فیلد :attribute باید عدد صحیح باشد.',
    'ip' => 'فیلد :attribute باید یک آدرس IP معتبر باشد.',
    'ipv4' => 'فیلد :attribute باید یک آدرس IPv4 معتبر باشد.',
    'ipv6' => 'فیلد :attribute باید یک آدرس IPv6 معتبر باشد.',
    'json' => 'فیلد :attribute باید یک رشته JSON معتبر باشد.',
    'lowercase' => 'فیلد :attribute باید با حروف کوچک باشد.',
    'lt' => [
        'array' => 'فیلد :attribute باید کمتر از :value آیتم داشته باشد.',
        'file' => 'فیلد :attribute باید کوچک‌تر از :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید کوچک‌تر از :value باشد.',
        'string' => 'فیلد :attribute باید کمتر از :value کاراکتر باشد.',
    ],
    'lte' => [
        'array' => 'فیلد :attribute نباید بیش از :value آیتم داشته باشد.',
        'file' => 'فیلد :attribute باید کوچک‌تر یا برابر :value کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید کوچک‌تر یا برابر :value باشد.',
        'string' => 'فیلد :attribute باید :value کاراکتر یا کمتر باشد.',
    ],
    'mac_address' => 'فیلد :attribute باید یک آدرس MAC معتبر باشد.',
    'max' => [
        'array' => 'فیلد :attribute نباید بیش از :max آیتم داشته باشد.',
        'file' => 'فیلد :attribute نباید بزرگ‌تر از :max کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute نباید بزرگ‌تر از :max باشد.',
        'string' => 'فیلد :attribute نباید بیش از :max کاراکتر باشد.',
    ],
    'max_digits' => 'فیلد :attribute نباید بیش از :max رقم داشته باشد.',
    'mimes' => 'فیلد :attribute باید فایلی با فرمت: :values باشد.',
    'mimetypes' => 'فیلد :attribute باید فایلی با نوع: :values باشد.',
    'min' => [
        'array' => 'فیلد :attribute باید حداقل :min آیتم داشته باشد.',
        'file' => 'فیلد :attribute باید حداقل :min کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید حداقل :min باشد.',
        'string' => 'فیلد :attribute باید حداقل :min کاراکتر باشد.',
    ],
    'min_digits' => 'فیلد :attribute باید حداقل :min رقم داشته باشد.',
    'missing' => 'فیلد :attribute باید حذف شود.',
    'missing_if' => 'فیلد :attribute باید زمانی حذف شود که :other برابر با :value باشد.',
    'missing_unless' => 'فیلد :attribute باید حذف شود مگر اینکه :other برابر با :value باشد.',
    'missing_with' => 'فیلد :attribute باید زمانی حذف شود که :values موجود باشد.',
    'missing_with_all' => 'فیلد :attribute باید زمانی حذف شود که :values موجود باشند.',
    'multiple_of' => 'فیلد :attribute باید مضربی از :value باشد.',
    'not_in' => 'مقدار انتخاب شده برای :attribute معتبر نیست.',
    'not_regex' => 'قالب فیلد :attribute معتبر نیست.',
    'numeric' => 'فیلد :attribute باید عدد باشد.',
    'password' => [
        'letters' => 'رمز عبور باید حداقل شامل یک حرف باشد.',
        'mixed' => 'رمز عبور باید حداقل شامل یک حرف بزرگ و یک حرف کوچک باشد.',
        'numbers' => 'رمز عبور باید حداقل شامل یک عدد باشد.',
        'symbols' => 'رمز عبور باید حداقل شامل یک نماد باشد.',
        'uncompromised' => 'رمز عبور انتخاب شده در یک رخنه امنیتی ظاهر شده است. لطفاً رمز عبور دیگری انتخاب کنید.',
    ],
    'present' => 'فیلد :attribute باید موجود باشد.',
    'present_if' => 'فیلد :attribute باید زمانی موجود باشد که :other برابر با :value باشد.',
    'present_unless' => 'فیلد :attribute باید موجود باشد مگر اینکه :other برابر با :value باشد.',
    'present_with' => 'فیلد :attribute باید زمانی موجود باشد که :values موجود باشد.',
    'present_with_all' => 'فیلد :attribute باید زمانی موجود باشد که :values موجود باشند.',
    'prohibited' => 'فیلد :attribute مجاز نیست.',
    'prohibited_if' => 'فیلد :attribute زمانی مجاز نیست که :other برابر با :value باشد.',
    'prohibited_if_accepted' => 'فیلد :attribute زمانی مجاز نیست که :other پذیرفته شده باشد.',
    'prohibited_if_declined' => 'فیلد :attribute زمانی مجاز نیست که :other رد شده باشد.',
    'prohibited_unless' => 'فیلد :attribute مجاز نیست مگر اینکه :other در :values باشد.',
    'prohibits' => 'فیلد :attribute اکتساب فیلد :other را ممنوع می‌کند.',
    'regex' => 'قالب فیلد :attribute معتبر نیست.',
    'required' => 'فیلد :attribute الزامی است.',
    'required_array_keys' => 'فیلد :attribute باید شامل کلیدهای :values باشد.',
    'required_if' => 'فیلد :attribute زمانی الزامی است که :other برابر با :value باشد.',
    'required_if_accepted' => 'فیلد :attribute زمانی الزامی است که :other پذیرفته شده باشد.',
    'required_if_declined' => 'فیلد :attribute زمانی الزامی است که :other رد شده باشد.',
    'required_unless' => 'فیلد :attribute الزامی است مگر اینکه :other در :values باشد.',
    'required_with' => 'فیلد :attribute زمانی الزامی است که :values موجود باشد.',
    'required_with_all' => 'فیلد :attribute زمانی الزامی است که :values موجود باشند.',
    'required_without' => 'فیلد :attribute زمانی الزامی است که :values موجود نباشد.',
    'required_without_all' => 'فیلد :attribute زمانی الزامی است که هیچ یک از :values موجود نباشد.',
    'same' => 'فیلد :attribute باید با :other یکسان باشد.',
    'size' => [
        'array' => 'فیلد :attribute باید شامل :size آیتم باشد.',
        'file' => 'فیلد :attribute باید برابر با :size کیلوبایت باشد.',
        'numeric' => 'فیلد :attribute باید برابر با :size باشد.',
        'string' => 'فیلد :attribute باید :size کاراکتر باشد.',
    ],
    'starts_with' => 'فیلد :attribute باید با یکی از این موارد شروع شود: :values.',
    'string' => 'فیلد :attribute باید رشته باشد.',
    'timezone' => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'ulid' => 'فیلد :attribute باید یک ULID معتبر باشد.',
    'unique' => 'مقدار فیلد :attribute قبلاً انتخاب شده است.',
    'uploaded' => 'بارگذاری فیلد :attribute با خطا مواجه شد.',
    'uppercase' => 'فیلد :attribute باید با حروف بزرگ باشد.',
    'url' => 'فیلد :attribute قالب یک آدرس اینترنتی معتبر ندارد.',
    'uuid' => 'فیلد :attribute باید یک UUID معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines (فارسی)
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes (فارسی)
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute placeholders
    | with something more user friendly like "E-Mail Address" instead
    | of "email". This simply makes the messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'نام',
        'username' => 'نام کاربری',
        'email' => 'ایمیل',
        'phone' => 'تلفن',
        'mobile' => 'تلفن همراه',
        'password' => 'رمز عبور',
        'password_confirmation' => 'تأیید رمز عبور',
        'first_name' => 'نام',
        'last_name' => 'نام خانوادگی',
        'full_name' => 'نام کامل',
        'address' => 'آدرس',
        'city' => 'شهر',
        'state' => 'استان',
        'country' => 'کشور',
        'zip' => 'کد پستی',
        'postal_code' => 'کد پستی',
        'title' => 'عنوان',
        'description' => 'توضیحات',
        'content' => 'محتوا',
        'subject' => 'موضوع',
        'message' => 'پیام',
        'body' => 'متن',
        'slug' => 'نامک',
        'url' => 'آدرس اینترنتی',
        'website' => 'وب‌سایت',
        'age' => 'سن',
        'gender' => 'جنسیت',
        'birth_date' => 'تاریخ تولد',
        'birthday' => 'تاریخ تولد',
        'avatar' => 'تصویر پروفایل',
        'image' => 'تصویر',
        'photo' => 'عکس',
        'file' => 'فایل',
        'attachment' => 'پیوست',
        'status' => 'وضعیت',
        'role' => 'نقش',
        'type' => 'نوع',
        'category' => 'دسته‌بندی',
        'comment' => 'نظر',
        'note' => 'یادداشت',
        'tags' => 'برچسب‌ها',
        'published_at' => 'تاریخ انتشار',
        'start_date' => 'تاریخ شروع',
        'end_date' => 'تاریخ پایان',
        'date' => 'تاریخ',
        'time' => 'زمان',
        'price' => 'قیمت',
        'amount' => 'مبلغ',
        'quantity' => 'تعداد',
        'count' => 'شمارش',
    ],

];
