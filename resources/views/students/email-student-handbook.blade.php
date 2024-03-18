<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>معلومات مودل {{ $student->fa_name }} {{ $student->fa_father_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .table{
            width: 100% !important;
            border-collapse: collapse;
            border:1px solid #000;
        }

        .table td, .table th{
            border:1px solid #000;
            text-align: right;
            width: 50%;
        }
    </style>
</head>

<body style="padding:20px;" dir="rtl">
    <div class="container">

        <div id="container">
            <p>
                سلام {{ $student->fa_name }} {{ $student->fa_father_name }}  عزیز,
            </p>

            <p>
            برای دسترسی به برنامه درسی مودل شما از ایمیل آدرس و  پسورد ذیل که برای شما مهیا شده است استفاده نمایید.
            </p>
            <p> برنامه مودل را شما میتوانید از لینک  ذیل دانلود کنید. 
                <br>
                <a href="https://play.google.com/store/apps/details?id=com.moodle.moodlemobile&pcampaignid=web_share">https://play.google.com/store/apps/details?id=com.moodle.moodlemobile&pcampaignid=web_share</a>
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
                    <th class="text-end">{{ $student->subGrade->grade->number }}</th>
                </tr>
                <tr>
                    <th>اسم در سیستم</th>
                    <th class="text-end">{{ $student->name_in_system }}</th>
                </tr>
                <tr>
                    <th>ایمیل سیستم</th>
                    <th class="text-end">{{ $student->email }}</th>
                </tr>
                <tr>
                    <th>پسورد سیستم</th>
                    <th class="text-end" style="direction: ltr !important;">{{ $student->password }}</th>
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
                    <th class="text-end"><a href="https://t.me/+ebX2eq5ZIDViOWE1">https://t.me/+ebX2eq5ZIDViOWE1</a></th>
                </tr>
                
            </table>
            نوت: برای دانلود کردن برنامه تلگرام از این استفاده کنید.
            <br>
            <a href="https://play.google.com/store/apps/details?id=org.telegram.messenger&pcampaignid=web_share">https://play.google.com/store/apps/details?id=org.telegram.messenger&pcampaignid=web_share</a>

            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            باتشکر
            <br>
            عزیزالله سعیدی
        </div>
</body>

</html>
