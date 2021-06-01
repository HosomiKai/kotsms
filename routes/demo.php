<?php

use Hosomikai\Kotsms\Controllers\KotsmsController;

Route::get('test/kotsms', [KotsmsController::class, 'index'])->name('kotsms.demo');
Route::post('test/kotsms', [KotsmsController::class, 'send'])->name('kotsms.send');
