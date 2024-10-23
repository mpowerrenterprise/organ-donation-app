<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\OrganController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MobileUserController;
use App\Http\Controllers\AuthenticationController;


Route::get('/', [AuthenticationController::class, 'index'])->name("index");
Route::post('/login', [AuthenticationController::class, 'login'])->name("login");
Route::get('/logout', [AuthenticationController::class, 'logout'])->name("logout");


Route::middleware(['CheckAdminAuth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name("show.dashboard");
    Route::get('/organs', [OrganController::class, 'showOrgans'])->name("show.organs");
    Route::get('/organs/requests', [OrganController::class, 'showOrganRequests'])->name("show.organ.requests");
    Route::get('/settings', [SettingController::class, 'showSettings'])->name("show.settings");

    Route::get('/users/mobile', [MobileUserController::class, 'showMobileUsers'])->name("show.mobile.users");
    Route::post('/users/mobile/ajax',  [MobileUserController::class, 'processMobileUsersAjax'])->name('process.mobile.users.ajax');
    Route::post('/users/mobile/approve/{id}', [MobileUserController::class, 'approveUser'])->name("approve.user");
    Route::delete('/users/delete/{id}', [MobileUserController::class, 'deleteUser'])->name('delete.user');


    Route::post('/organs/add', [OrganController::class, 'addOrgan'])->name('add.organ');
    Route::post('/organs/ajax',  [OrganController::class, 'processOrgansAjax'])->name('process.organs.ajax');
    Route::delete('/organs/delete/{id}', [OrganController::class, 'deleteOrgan'])->name('delete.organ');
    Route::post('organ-requests/accept/{id}', [OrganController::class, 'acceptOrganRequest'])->name('accept.organ.request');
    Route::post('organ-requests/reject/{id}', [OrganController::class, 'rejectOrganRequest'])->name('reject.organ.request');
    Route::post('organ-requests/ajax', [OrganController::class, 'processOrganRequestsAjax'])->name('process.organ.requests.ajax');

    Route::post('/courses/add', [CourseController::class, 'addCourse'])->name("add.course");
    Route::get('/courses/view/{id}', [CourseController::class, 'viewCourse'])->name('view.course');
    Route::post('/courses/ajax',  [CourseController::class, 'processCoursesAjax'])->name('process.courses.ajax');
    Route::put('/courses/edit/{data}', [CourseController::class, 'editCourse'])->name('edit.course');
    Route::delete('/courses/delete/{id}', [CourseController::class, 'deleteCourse'])->name('delete.course');

    Route::post('/batches/add', [BatchController::class, 'addBatch'])->name('add.batch');  
    Route::post('/batches/ajax',  [BatchController::class, 'processBatchesAjax'])->name('process.batches.ajax');
    Route::put('/batches/edit/{data}', [BatchController::class, 'editBatch'])->name('edit.batch');
    Route::delete('/batches/delete/{data}', [BatchController::class, 'deleteBatch'])->name('delete.batch');
    Route::get('/batches/view/{id}', [BatchController::class, 'viewBatch'])->name('view.batch');
    Route::get('/batches/{batch_id}/remove-student/{student_id}', [BatchController::class, 'removeStudentFromBatch'])->name('remove.student.from.batch');
    Route::post('/batches/{batch_id}/students/add', [BatchController::class, 'addStudentsToBatch'])->name('add.students.to.batch');


    Route::post('/update-username', [SettingController::class, 'updateUsername'])->name('update.username');
    Route::post('/update-password', [SettingController::class, 'updatePassword'])->name('update.password');

});