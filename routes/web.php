<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferalController;
use App\Http\Controllers\ScheduleController;
use App\Models\Kelas;
use App\Models\Mentor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $kelas = Kelas::with('mentors')
        ->where('is_active', 1)
        ->latest()
        ->paginate(3);

    $mentors = Mentor::with('kelas')
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('kelas', 'mentors'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route::get('/payment/success', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');
Route::get('/payment/success', function () {
    return view('payment.success');
});

Route::get('/payment/failed',  function () {
    $kelas = Kelas::where('is_active', 1)->latest()->paginate(3); // 🔥 pagination 3 data
    return view('welcome', compact('kelas'));
});
// Route::prefix('family')->group(function () {
//     Route::get('/', [FamilyTreeController::class, 'index']);
//     Route::post('/', [FamilyTreeController::class, 'store']);
//     Route::put('/{id}', [FamilyTreeController::class, 'update']);
//     Route::delete('/{id}', [FamilyTreeController::class, 'destroy']);
//     Route::get('/data', [FamilyTreeController::class, 'data']);
//     // select2 search
//     Route::get('/search', [FamilyTreeController::class, 'search']);

//     Route::post('/member', [FamilyTreeController::class, 'storeMember']);
//     Route::post('/marriage', [FamilyTreeController::class, 'storeMarriage']);
//     Route::post('/divorce/{id}', [FamilyTreeController::class, 'divorce']);
// });

// Route::get('/', [FamilyTreeController::class, 'index']);

// Route::resource('family', FamilyTreeController::class);
// Route::get('/family/search', [FamilyTreeController::class, 'search']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/assignment/{assignment}', [AssignmentController::class, 'show'])
        ->name('assignment.show');
    Route::post('/assignment/{assignment}/submit', [AssignmentController::class, 'submit'])
        ->name('assignment.submit');
    Route::put('/assignment/submission/{id}', [AssignmentController::class, 'updatesubmit'])
        ->name('assignment.submission.update');
    // Route::post('/absen/{session}', [AttendanceController::class, 'absen'])
    //     ->name('attendance.absen');

    Route::post('/attendance/{schedule}', [AttendanceController::class, 'store'])
        ->name('attendance.store');
});

Route::middleware(['auth', 'level:1'])->group(function () {

    Route::get('/kelas/data', [KelasController::class, 'data']);

    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'id'
    ]);
    Route::resource('referral', ReferalController::class)->parameters([
        'referals' => 'id'
    ]);

    Route::resource('mentor', MentorController::class)->parameters([
        'referals' => 'id'
    ]);
    // SCHEDULE
    Route::resource('schedule', ScheduleController::class)->parameters([
        'schedule' => 'id'
    ]);

    // routes/web.php

    Route::post('/schedule/upload-record/{id}', [ScheduleController::class, 'uploadRecord'])
        ->name('schedule.upload.record');

    // CLASS SESSION
    Route::resource('class-session', ClassSessionController::class)->parameters([
        'class-session' => 'id'
    ]);

    Route::prefix('laporan')->name('laporan.')->group(function () {

        Route::get('/', [LaporanController::class, 'index'])
            ->name('index');
    });

    Route::get('/laporan/chart', [LaporanController::class, 'chart'])->name('laporan.chart');
    // Route::get('/referals/data', [ReferalController::class, 'data']);
    Route::resource('assignments', AssignmentController::class);
    Route::get('/assignments/{id}/submissions', [AssignmentController::class, 'submissions']);

    // Route::put('/assignment-submissions/{id}', [AssignmentController::class, 'updateSubmission']);

    Route::put('/submission/{id}/review', [AssignmentController::class, 'updateSubmission']);
});
Route::post('/checkout', [OrderController::class, 'checkout']);
Route::get('kelas/{id}/modul/data', [ModulController::class, 'data']);
Route::post('kelas/{id}/modul', [ModulController::class, 'store']);
Route::delete('/modul/{id}', [ModulController::class, 'destroy']);


Route::get('/check-referral', [ReferalController::class, 'checkReferral']);

require __DIR__ . '/auth.php';
