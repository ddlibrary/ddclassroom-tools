<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <title>{{ $student->fa_name . ' - ' . $student->fa_father_name }} - صنف {{ $student->subGrade->grade->number }}</title>
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
                    <th class="text-end">https://courses.darakhtdanesh.org/login/index.php</th>
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
            <br>
            <br>
            <br>
            <br>
            <br>
            باتشکر
            <br>
            {{ auth()->user()->name }}


            {{-- <div dir="ltr">
                <p style="font-size: 9pt; font-family: Verdana, sans-serif; font-weight: 700; color: rgb(245, 130, 61); background-color: transparent; margin: 0;">
                    Canadian Women for Women in Afghanistan
                </p>
                <p style="color: rgb(34, 34, 34); font-family: Arial, sans-serif; font-size: 9pt; line-height: 1.656; margin: 0;">
                    <a href="http://www.cw4wafghan.ca/" style="color: rgb(17, 85, 204); font-family: Verdana, sans-serif; background-color: transparent;">
                        www.cw4wafghan.ca
                    </a>
                    <span style="font-family: Verdana, sans-serif;">
                        &nbsp;| Follow us @cw4wafghan
                    </span>
                </p>
                <p style="font-size: 9pt; font-family: Georgia, serif; color: rgb(0, 0, 0); background-color: transparent;line-height: 1.656; margin: 0;font-style: italic;">
                    <br>
                    <span>
                        Making the&nbsp;
                    </span>
                    <span style="font-weight: 700;">
                        Right to Learn
                    </span>
                    <span>
                        &nbsp;a Reality
                    </span>
                </p>
            </div> --}}
        </div>


        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('assets/js/jquery-min.js') }}"></script>
        <script src="{{ asset('assets/js/print-this.js') }}"></script>
        <script src="{{ asset('assets/js/myjs.js') }}"></script>
</body>

</html>
