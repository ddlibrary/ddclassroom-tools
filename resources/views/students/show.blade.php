<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <title>{{ $student->fa_name . ' - ' . $student->fa_father_name }} - صنف {{ $student->subGrade->grade->number }}
    </title>
    <style>
        table {
            page-break-inside: avoid;
        }

        td {
            page-break-inside: avoid;
        }

        @media print {
            .body {
                direction: rtl;
            }
        }
    </style>
</head>

<body style="padding:20px;" dir="rtl">
    <div class="container">
        <button onclick="printDiv('container')" class="btn btn-success my-2">چاپ</button>
        <a href="{{ url('email-handbook/' . $student->uuid) }}" style="float:left">
            <button class="btn btn-success my-2">ایمیل به شاگرد</button>
        </a>



        <div id="container" dir="rtl">
            <p>
                سلام {{ $student->fa_name }} {{ $student->fa_father_name }} عزیز,
            </p>

            <p>
                برای دسترسی به برنامه درسی مودل شما از ایمیل آدرس و پسورد ذیل که برای شما مهیا شده است استفاده نمایید.
            </p>
            <p> برنامه مودل را شما میتوانید از لینک ذیل دانلود کنید.
                <br>
                <a
                    href="https://play.google.com/store/apps/details?id=com.moodle.moodlemobile&pcampaignid=web_share">https://play.google.com/store/apps/details?id=com.moodle.moodlemobile&pcampaignid=web_share</a>
            </p>
            <h1 class="text-center">ایمیل و رمزعبور {{ $student->fa_name }} {{ $student->fa_father_name }} در مودل</h1>


            <table class="table table-bordered border-dark">
                <tr>
                    <th class="w-50 text-right">اسم شاگرد</th>
                    <th class="text-end">{{ $student->fa_name }} {{ $student->fa_last_name }}</th>
                </tr>
                <tr>
                    <th>اسم پدر</th>
                    <th class="text-end">{{ $student->fa_father_name }}</th>
                </tr>
                <tr>
                    <th>صنف</th>
                    <th class="text-end">{{ $student->subGrade->name }}</th>
                </tr>
                <tr>
                    <th>اسم در سیستم</th>
                    <th class="text-end">{{ $student->name_in_system }}</th>
                </tr>
                <tr>
                    <th>نام کاربری</th>
                    <th class="text-end">{{ $student->username }}</th>
                </tr>
                <tr>
                    <th>ایمیل سیستم</th>
                    <th class="text-end"><code>{{ $student->email }}</code></th>
                </tr>
                <tr>
                    <th>پسورد سیستم</th>
                    <th class="text-end" style="direction: ltr !important;"><code>{{ $student->password }}</code></th>
                </tr>
                <tr>
                    <th>آدرس سایت درخت دانش</th>
                    <th class="text-end">https://classroom.darakhtdanesh.org/login/index.php</th>
                </tr>
                <tr>
                    <th>نام سایت درخت دانش در مودل</th>
                    <th class="text-end">ddc</th>
                </tr>
                <tr>
                    <th>گروپ تلگرام - حل مشکلات تخنیکی</th>
                    <th class="text-end"><a href="https://t.me/+ebX2eq5ZIDViOWE1">https://t.me/+ebX2eq5ZIDViOWE1</a>
                    </th>
                </tr>
                <tr>
                    <th>گروپ تلگرام - ویدیوهای آموزشی </th>
                    <th class="text-end"><a href="https://t.me/+ooZAZgJMm0kwYTNl">https://t.me/+ooZAZgJMm0kwYTNl</a>
                    </th>
                </tr>



            </table>
            نوت: برای دانلود کردن برنامه تلگرام از این استفاده کنید.
            <br>
            <a
                href="https://play.google.com/store/apps/details?id=org.telegram.messenger&pcampaignid=web_share">https://play.google.com/store/apps/details?id=org.telegram.messenger&pcampaignid=web_share</a>

            <br>
            <br>
            <table class="table table-bordered border-dark">
                <tr>
                    <th class="w-50 text-center">سوالات دانش‌‌آموزان</th>
                    <th class="text-center">جوابات</th>
                </tr>
                <tr>
                    <td>
                        ما از چی طریقی به درس‌های آنلاین حاضر شده می‌توانیم؟
                    </td>
                    <td>
                        شما از طریق پلتفورم Moodle به درس‌های آنلاین خود وصل می‌شوید. ویدیوی درس تان قبلن در سیستم مودل
                        گذاشته
                        می‌شود، شما می توانید آن ویدیو را ببینید، سپس از طریق لینک Google Meet که در مودل گذاشته شده نظر
                        به
                        تقسیم‌اوقات تان در ساعت مشخص به صنف آنلاین خود وصل شوید.
                    </td>
                </tr>
                <tr>
                    <td>
                        Moodle چی است؟
                    </td>
                    <td>
                        Moodle یک پلتفورمی است که برای آموزش آنلاین استفاده می‌شود. ما نیز در این پلتفورم فضای را برای
                        صنف شما
                        در نظر گرفته ایم تا شما بتوانید به درس‌های خود به‌صورت سیستماتیک ادامه دهید. شما با استفاده از
                        یوزر و
                        پسوردی که در جدول فوق تهیه شده، می‌توانید به این صنف تان دست‌رسی داشته باشید.
                    </td>
                </tr>
                <tr>
                    <td>چطور بدانیم که امروز درس کدام مضمون است؟</td>
                    <td>به رویت تقسیم‌اوقات هفته‌وار که با شما شریک شده می‌توانید مضمون امروز را پیدا کنید.</td>
                </tr>
                <tr>
                    <td>درس‌ جدید خود را چه وقت مشاهده کنیم و یا بخوانیم؟</td>
                    <td>قبل از این‌که جلسه درسی شما با استاد مضمون همان‌روز به‌طور آن‌لاین شروع شود، باید درس امروز را
                        به دقت بخوانید و آماده‌گی داشته باشید.</td>
                </tr>
                <tr>
                    <td>جوابات فعالیت‌ها، ارزیابی‌‌ها و کارخانه‌گی‌ها را چه وقت انجام دهیم؟</td>
                    <td>در هر درس، سوالات ارزیابی، فعالیت‌ها و کار‌خانه‌گی‌ها را خواهید دید. بعد از این‌که درس امروز تان
                        را به دقت خواندید، جوابات سوالات ارزیابی و کار‌خانگی‌ها را در کتابچه‌های خود یادداشت کنید.</td>
                </tr>
                <tr>
                    <td>جوابات فعالیت‌ها، ارزیابی‌ها و کارخانه‌گی را چه قسم با استاد شریک بسازیم؟</td>
                    <td>همه جوابات را به‌طور منظم در کتابچه‌های خود به خط درشت نوشته و از هر کدام آن عکس گرفته و نظر به
                        رهنمود گذاشته شده در مودل کار خانگی خود را ارسال کنید.</td>
                </tr>
                <tr>
                    <td>آیا در دروس آن‌لاین کتابچه کار است؟</td>
                    <td>داشتن کتابچه حتمی نیست، اما برای گرفتن یادداشت‌ یا تمرین دروس، داشتن کتابچه یا کاغذ می‌تواند
                        مفید باشد. </td>
                </tr>
                <tr>
                    <td>چگونه بفهمیم که جوابات ما درست است یا خیر؟</td>
                    <td>همه جوابات شما را استاد مربوطه مرور می‌کند و بعدا بازخورد خود را از طریق مودل با شما شریک میکند.
                    </td>
                </tr>
                <tr>
                    <td>اگر در درس‌های خود مشکل داشته باشیم، چه کنیم؟</td>
                    <td>می‌توانید از استادان تان هم به‌طور شفاهی و هم تحریری از طریق گروه تلگرام سوال کنید. البته در
                        اوقات معینه نظر به تقسیم اوقاتی که برای تان داده شده است.</td>
                </tr>
                <tr>
                    <td>چه وقت در صنف آن‌لاین حاضر باشیم؟</td>
                    <td>حداقل پنج دقیقه قبل از شروع درس آنلاین باشید و به صنف تان از طریق لینک فرستاده شده در گروه
                        تلگرام و یا هم از طریق لینک موجوده در مودل وصل شوید. </td>
                </tr>
                <tr>
                    <td>اگر در صنف آن‌لاین حاضر نشوم غیرحاضر شمرده می‌شوم؟</td>
                    <td>بلی! مثل صنف حضوری، حاضری نزد هر استاد موجود است و در صورت شرکت نکردن تان به صنف، شما را غیر
                        حاضرمی‌سازد. حاضری شما در صنف ها نظر به فیصدی حضور شما محاسبه میشود و در مجموع نتایج تان اثیر
                        خواهد داشت. </td>
                </tr>
                <tr>
                    <td>آیا می‌توانیم از تبلت/لپ‌تاپ خود در مسایل شخصی استفاده کنیم؟</td>
                    <td>نخیر!
                        <br>
                        فقط درمسایل درسی استفاده کرده می‌توانید و بس.
                    </td>
                </tr>
                <tr>
                    <td>آیا می‌توانیم از معلم‌ صاحب ما در موارد مشکلات درسی سوال کنیم؟</td>
                    <td>بلی! می‌توانید هم شفاهی و هم تحریری از طریق گروه تلگرام و یا هم میتوانید با ارسال نمودن پیام در
                        حساب شخصی معلم سوال کنید.</td>
                </tr>
                <tr>
                    <td>آیا دانش‌آموزان مکلف به سپری نمودن امتحان می‌باشند؟</td>
                    <td>بلی! برعلاوه این‌که همانند مکتب حضوری امتحان چهارونیم‌ماهه و سالانه دارید، ارزیابی روزانه نیز
                        بخشی از برنامه‌های ما است. کوشش کنید تا امتحانات و ارزیابی‌ها را موفقانه سپری نمایید.</td>
                </tr>



            </table>

            <h3>سوال و جواب راجع به درسها و مودل:</h3>
            <div>
                <div>
                    <p>
                        1. چرا بسته اینترنت‌ام زود تمام می‌شود؟
                    </p>
                    <p>
                        ویدیوهای درسی در درخت دانش بیشتر از ۲۰۰ مگابایت حجم ندارد و تماس صوتی در واتساپ برای یک ساعت بین
                        ۱۰۰ - ۱۵۰ مگابایت مصرف می‌شود. حداکثر مصرف در یک روز به ۳۰۰ میگابت میرسد. در یک ماه ۸ گیگابایت
                        (GB) اینترنت از طرف دفتر به شما فعال می‌شود. استفاده شخصی و نا مورد از اینترنت، دانلود نکردن
                        درس‌ها و تماشای تکرار ویدیو‌های درسی و دریافت و ارسال ویدیوهای شخصی در واتساپ باعث تمام‌شدن
                        اینترنت شما پیش از وقت می‌گردد. اگر بسته اینترنت شما پیش از یک ماه تمام شود، مسئولیت خود شما است
                        تا دوباره آن‌را فعال کنید.
                    </p>


                </div>
                <br>
                <div>
                    <p>
                        2. چه‌ گونه حساب درخت دانش خود را بیابم؟
                    </p>
                    <p>
                        برنامه درسی‌ که شما در آن درس‌ها را دریافت می‌کنید و به کمک آن درس می‌خواند برای هر شاگرد یک
                        حساب کاربری دارد که توسط آن می‌دانیم که شاگردان دروس را مرور کرده اند یا خیر. در صورتی‌ که شما
                        به هر دلیلی از حساب برنامه درسی تان خارج شدید و به درس ها دسترسی نداشتید، لطفاً مراحل زیر را
                        دنبال کنید تا دوباره داخل حساب خود شوید. برنامه را باز کنید و I'M A LEARNER انتخاب کنید.
                    </p>
                    <img src="{{ asset('handbook-images/1.png') }}" class="w-100">
                    <p>در صفحه بعدی DDC نوشته کنید</p>
                    <img src="{{ asset('handbook-images/2.png') }}" class="w-100">
                    <p>منتظر بمانید تا لیست بیاید و بعد در لیست (Darakht-e Danesh Classroom (DDC را انتخاب کنید.</p>
                    <img src="{{ asset('handbook-images/3.jpg') }}" class="w-100">
                    <p>در صفحه آخری از شما ایمیل و رمز حساب شما پرسیده می‌شود که در جدول بالا برای‌تان داده شده. ایمیل و
                        رمز حساب هر شاگرد متفاوت می‌باشد. بعد از نوشتن ایمیل و رمز تان دکمه LOG IN که به رنگ نارنجی است،
                        را کلیک کنید.
                    </p>

                </div>

                <div>
                    <p>
                        3. چگونه کورس‌ها را دانلود نماییم؟
                    </p>
                    <p>
                        <span class="text-danger">a)</span> برای دانلود کردن کورس ها در صفحه کورس بالای شکل ابر کلیک کنید.
                    </p>
                    <img src="{{ asset('handbook-images/4.png') }}" class="w-100">
                    <br>
                    <br>
                    <p><span class="text-danger">b)</span>  در صفحه دانلود روبروی هر درس شکل ابر (آیکن دانلود) وجود دارد، بالای آن کلیک کنید و صبر کنید تا
                        دانلود تکمیل شود. در صورت که دانلود تکمیل شود، شکل روبروی آن از ابر به سطل زباله تبدیل میشود. در
                        صورت که شما درس ها را دانلود کنید، شما می‌توانید بدون مصرف اینترنت درس را بارها مکرراً تماشا
                        کنید. حتی اگر بسته اینترنت شما تمام شود، درس های دانلود شده قابل مشاهده میباشد.
                    </p>
                    <div class="d-flex">

                        <img src="{{ asset('handbook-images/5.png') }}" class="w-50">
                        <img src="{{ asset('handbook-images/6.png') }}" class="w-50">
                    </div>


                </div>
                <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            باتشکر
            <br>
            {{ auth()->user()->name }}
        </div>


        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('assets/js/jquery-min.js') }}"></script>
        <script src="{{ asset('assets/js/print-this.js') }}"></script>
        <script src="{{ asset('assets/js/myjs.js') }}"></script>
</body>

</html>
