<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::prefix('public')->group(function () {
    Route::prefix('home')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Public/Beranda');
        })
        ->name('home');
        // ->middleware('permission:view');
    });
    // Route::get('/test', function () {
    //     return \Inertia\Inertia::render('Public/TestPage/TestPage');
    // })->name("publicTest");

    // Route::get('/test2', function () {
    //     return \Inertia\Inertia::render('Public/TestPage/TestPage');
    // })->name("publicTest2");
});

Route::prefix('autentikasi')->group(function () {
    Route::get('/masuk', function () {
        return \Inertia\Inertia::render('Auth/Masuk',['title' => "Masuk"]);
    })->middleware('guest')
    ->name('masuk');

    Route::get('/daftar', function () {
        return \Inertia\Inertia::render('Auth/Daftar',['title' => "Daftar"]);
    })->middleware('guest')
    ->name('daftar');
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth','layout']], function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Admin/Dashboard');
            })->name('dashboard');
        });

        // Route::resource('/menu', \App\Http\Controllers\Apps\MenuController::class)
        //     ->middleware('permission:index')->name('menu');

        Route::prefix('menu')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MenuController::class,'index'])->name('menu');
            // ->middleware('permission:view');
            Route::post('/checkValidationMenu', [\App\Http\Controllers\Admin\MenuController::class,'checkValidation']);
            Route::post('/storeMenu', [\App\Http\Controllers\Admin\MenuController::class,'store']);
        });

        Route::prefix('role')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RoleController::class,'index'])->name('role');
            // ->middleware('permission:view');
            Route::post('/storeRole', [\App\Http\Controllers\Admin\RoleController::class,'store']);
            Route::get('/deleteRole', [\App\Http\Controllers\Admin\RoleController::class,'delete']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UserController::class,'index'])->name('user');
            // ->middleware('permission:view');
            Route::post('/storeUser', [\App\Http\Controllers\Admin\UserController::class,'store']);
            Route::get('/deleteUser', [\App\Http\Controllers\Admin\UserController::class,'delete']);
        });
        
        Route::prefix('upload')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UploadController::class,'index'])->name('upload');
            // ->middleware('permission:view');
            Route::post('/storeUpload', [\App\Http\Controllers\Admin\UploadController::class,'store']);
            Route::get('/deleteUpload', [\App\Http\Controllers\Admin\UploadController::class,'delete']);
        });

        Route::prefix('testUpload')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UploadController::class,'indexTestUpload'])->name('testUpload');
            Route::post('/storeUpload', [\App\Http\Controllers\Admin\UploadController::class,'storeTestUpload']);
            Route::post('/deleteUpload', [\App\Http\Controllers\Admin\UploadController::class,'deleteTestUpload']);
            Route::post('/zippedFiles', [\App\Http\Controllers\Admin\UploadController::class,'zippedFiles']);
        });

        Route::prefix('testEmail')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TestEmailController::class,'index'])->name('testEmail');
            Route::get('/testSendEmail', [\App\Http\Controllers\Admin\TestEmailController::class,'testSendEmail']);
        });

        Route::prefix('testStateManagement')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TestStateManagementController::class,'index'])->name('testStateManagement');
            Route::get('/detail', [\App\Http\Controllers\Admin\TestStateManagementController::class,'detail']);
        });

        Route::prefix('testRealtime')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TestRealtimeController::class,'index'])->name('testRealtime');
            Route::post('/sendChatRealtime', [\App\Http\Controllers\Admin\TestRealtimeController::class,'send']);
        });

        Route::prefix('trelloView')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TrelloViewController::class,'index'])->name('trelloView');
        });

        // Route::get('/hak-akses', function () {
        //     return Inertia::render('Admin/Dashboard');
        // })->name('hakAkses')->middleware('permission:view');
    });
});

Route::prefix('core')->group(function () {
    Route::get('/upload/information', [\App\Http\Controllers\Core\UploadController::class, 'information']);
    Route::post('/upload/check', [\App\Http\Controllers\Core\UploadController::class, 'check']);
});

// Route::get('routes', function () {
//     $routeCollection = Route::getRoutes();

//     echo "<table style='width:100%'>";
//     echo "<tr>";
//     echo "<td width='10%'><h4>HTTP Method</h4></td>";
//     echo "<td width='10%'><h4>Route</h4></td>";
//     echo "<td width='10%'><h4>Name</h4></td>";
//     echo "<td width='70%'><h4>Corresponding Action</h4></td>";
//     echo "</tr>";
//     foreach ($routeCollection as $value) {
//         // echo json_encode($value);
//         echo "<tr>";
//         echo "<td>" . $value->methods()[0] . "</td>";
//         echo "<td>" . $value->uri() . "</td>";
//         echo "<td>" . $value->getName() . "</td>";
//         echo "<td>" . $value->getActionName() . "</td>";
//         echo "</tr>";
//     }
//     echo "</table>";
// });