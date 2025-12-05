<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadedTorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TesseractOcrController;
use App\Http\Controllers\TorApprovalController;
use App\Http\Controllers\TorGradeController;
use App\Http\Controllers\UserOtherInfoController;
use App\Http\Controllers\ProspectusController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdvisingController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

// -----------------------------
// Test Cloudinary
// -----------------------------
Route::get('/test-cloudinary', function () {
    return [
        'env' => env('CLOUDINARY_URL'),
        'config' => config('cloudinary.cloud_url'),
    ];
});

Route::get('/check-cloudinary', function () {
    $cfg = config('cloudinary');
    return response()->json([
        'exists' => $cfg !== null,
        'type'   => gettype($cfg),
        'keys'   => array_keys($cfg ?? []),
        'cloud_url' => $cfg['cloud_url'] ?? 'missing',
    ]);
});

// -----------------------------
// Public Routes
// -----------------------------
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// -----------------------------
// Protected Routes
// -----------------------------
Route::middleware('auth-ocr')->group(function () {

    // -----------------------------
    // User Management
    // -----------------------------
    Route::apiResource('/users', UserController::class);
    Route::get('/me', [UserController::class, 'getMyInfo']);
    // routes/api.php
    Route::middleware('auth:sanctum')->get('/users/current-user', [UserController::class, 'currentUser']);
    Route::get('/tor/{tor}/advising', [AdvisingController::class, 'getByTor']);
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);
    Route::post('/users/update/{id}', [UserOtherInfoController::class, 'adminStoreOrUpdateUser']);
    Route::get('/users/other-info', [UserOtherInfoController::class, 'show']);
    Route::post('/users/other-info', [UserOtherInfoController::class, 'storeOrUpdate']);
    

    // -----------------------------
    // Courses, Curriculums, and Subjects
    // -----------------------------
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('curriculums', CurriculumController::class);
    Route::apiResource('subjects', SubjectController::class);

    // Curriculum-specific data
    Route::get('/curriculums/{curriculum_id}/subjects', [SubjectController::class, 'getByCurriculum']);
    Route::get('/curriculums/{curriculum_id}/prospectus', [CurriculumController::class, 'prospectus']);

    // ✅ FIXED — Subject filters by course (this is your working API endpoint)
    Route::get('/subjects/course/{courseCode}', [SubjectController::class, 'getSubjectsByCourse']);
    Route::get('/subjects/by-user-course', [SubjectController::class, 'getSubjectsByUserCourse']);

    // -----------------------------
    // Students & Prospectus
    // -----------------------------
    Route::get('/student/{id}/subjects', [StudentController::class, 'getSubjects']);
    Route::get('/student/{id}/prospectus', [ProspectusController::class, 'getStudentSubjects']);

    // -----------------------------
    // TOR Management
    // -----------------------------
    Route::apiResource('tor', UploadedTorController::class);
    Route::post('/tor/upload/{curriculum_id}', [UploadedTorController::class, 'storeWithCurriculum']);
    Route::get('/fetchMyTors', [UploadedTorController::class, 'fetchMyTors']);
    Route::post('/process-tor/{id}/{curriculum_id}', [TesseractOcrController::class, 'analyzeTor']);

    // TOR Grades
    Route::apiResource('grades', TorGradeController::class);

    // TOR Approval
    Route::post('/tors/approve', [TorApprovalController::class, 'approve']);
    Route::post('/tors/reject/{tor_id}', [TorApprovalController::class, 'rejectTor']);

    // -----------------------------
    // Advising
    // -----------------------------
    Route::post('/advising', [AdvisingController::class, 'store']);
    Route::get('/advising/{torId}', [AdvisingController::class, 'show']);
    Route::post('/new-student/advising', [AdvisingController::class, 'newStudentAdvising']);

    // -----------------------------
    // Notifications
    // -----------------------------
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // -----------------------------
    // Statistics / Summary
    // -----------------------------
    Route::get('/student/summary', [StatController::class, 'summary']);
    Route::get('/admin/summary', [StatController::class, 'adminSummary']);
});
