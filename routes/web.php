<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HandBookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ShoqaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentResultCardController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\SubGradeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', [AttendanceLogController::class, 'studentAttendance']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);

    Route::get('students/create/multiple', [StudentController::class, 'createMultipleStudents']);
    Route::post('storeMultipleStudents', [StudentController::class, 'storeMultipleStudents'])->name('students.store-multiple-student');
    Route::post('student-uploading-sample-file', [StudentController::class, 'studentUpladingSampleFile'])->name('student-uploading-sample-file');
    Route::get('email-handbook/{uuid}', [StudentController::class, 'emailHandbook']);

    Route::get('handbooks', [HandBookController::class, 'index'])->name('handbooks.index');
    Route::post('handbooks.send-handbook', [HandBookController::class, 'sendHandbook'])->name('handbooks.send-handbook');

    Route::controller(StudentResultController::class)->group(function () {
        Route::get('student-result', 'index')->name('student-result.index');
        Route::post('student-result.send-score', 'sendScore')->name('student-result.send-score');
        Route::get('student-result/create/multiple', 'createMultipleStudentScores');
        Route::post('storeMultipleStudentsScore', 'storeMultipleStudentsScore')->name('student-result.store-multiple-student-score');
    });

    Route::controller(AttendanceController::class)->group(function () {
        Route::get('student-attendance', 'index')->name('student-attendance.index');
        Route::post('student-attendance.send-score', 'sendScore')->name('student-attendance.send-attendance');
        Route::get('student-attendance/create/multiple', 'createMultipleStudentAttendance');
        Route::post('store-multiple-students-attendance', 'storeMultipleStudentsAttendance')->name('student-attendance.store-multiple-student-attendance');
    });

    Route::controller(AttendanceLogController::class)->group(function () {
        Route::get('student-attendance-log', 'index')->name('student-attendance-log.index');
        Route::post('student-attendance-log.send-score', 'sendScore')->name('student-attendance-log.send-attendance');
        Route::get('student-attendance-log/create/multiple', 'createMultipleStudentAttendance');
        Route::post('store-multiple-students-attendance-log', 'storeMultipleStudentsAttendanceLog')->name('student-attendance-log.store-multiple-student-attendance');
        Route::get('students-attendance-log-reports', 'studentAttendanceLogReports')->name('student-attendance-log.students-attendance-log-reports');
        Route::get('get-attendance-log-report-as-excel', 'getAttendanceLogReportAsExcel');
    });

    Route::resource('subjects', SubjectController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('years', YearController::class);
    Route::post('toggle-country-is-active', [CountryController::class, 'toggleIsActive']);
    Route::resource('results', ResultController::class);
    Route::post('toggle-result-is-active', [ResultController::class, 'toggleIsActive']);
    Route::resource('sub-grades', SubGradeController::class);
    Route::post('toggle-sub-grade-is-active', [SubGradeController::class, 'toggleIsActive']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Log file
    Route::get('logs', [LogViewerController::class, 'index']);
});

Route::controller(StudentResultCardController::class)->group(function () {
    Route::get('student-hand-book/{uuid}', 'studentHandBook');
    Route::get('student-result-card/{uuid}/{year}', 'studentResultCard');
});

Route::controller(ScoreController::class)->group(function () {
    Route::get('student-score', 'index')->name('student-score.index');
    Route::get('student-score/create', 'create')->name('student-score.create');
    Route::get('student-score/{score}/edit', 'edit');
    Route::post('student-score', 'store')->name('student-score.store');
    Route::put('student-score/{score}', 'update')->name('student-score.update');

});
Route::post('get-students', [EnrollmentController::class, 'getStudents'])->name('get-students');

Route::controller(ShoqaController::class)->group(function () {

    Route::get('shoqa', 'index');
    Route::get('get-shoqa-as-excel', 'getShoqaAsExcel');
});
Route::get('get-student-result-as-excel', [StudentResultController::class, 'getStudentResultAsExcel']);

Route::get('clear', [HomeController::class, 'clear']);

require __DIR__.'/auth.php';
