<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AturanController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\BkuController;
use App\Http\Controllers\DpaController;
use App\Http\Controllers\RekeningKoranController;
use App\Http\Controllers\AngkasController;
use App\Http\Controllers\SpjFungsionalController;
use App\Http\Controllers\SpjTransaksiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TusUploadController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AnalisisController;

// TUS Upload Routes (no authentication required for file uploads)
Route::match(['get', 'post', 'patch', 'options', 'head'], '/tus/uploads/{uploadId?}', [TusUploadController::class, 'handle'])->name('tus.upload');
Route::get('/tus/uploads/{uploadId}/info', [TusUploadController::class, 'getCompletedUpload'])->name('tus.upload.info');

Route::get('/', function () {
    return view('welcome');
})->name('login.form');

// Auth Routes
Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Dashboard Routes
Route::middleware(['auth'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    // Aturan Routes
    Route::resource('aturan', AturanController::class);

    // SKPD Routes
    Route::resource('skpd', SkpdController::class);

    // Anggota Routes
    Route::resource('anggota', AnggotaController::class)->names('superadmin.anggota')->parameters(['anggota' => 'anggota']);
    Route::post('anggota/{id}/create-user', [AnggotaController::class, 'createUser'])->name('superadmin.anggota.create-user');
    Route::post('anggota/{id}/reset-password', [AnggotaController::class, 'resetPassword'])->name('superadmin.anggota.reset-password');

    // Chat AI Routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/clear', [ChatController::class, 'clearConversation'])->name('chat.clear');

    // Rekening Koran Routes
    Route::get('/rekening_koran/ocr/{rekening_koran_id}', [RekeningKoranController::class, 'ocr'])->name('rekening_koran.ocr');
    Route::get('/rekening_koran/{id}/details', [RekeningKoranController::class, 'getDetails'])->name('rekening_koran.details');
    Route::resource('rekening_koran', RekeningKoranController::class);

    // Upload Data Routes
    Route::prefix('upload')->group(function () {
        // DPA Routes (Upload DPA Perbulan)
        Route::get('/dpa/ocr/{dpa_id}', [DpaController::class, 'ocr'])->name('upload.dpa.ocr');
        Route::resource('dpa', DpaController::class)->names('upload.dpa');
        
        // Anggaran Kas Routes
        Route::get('/anggaran/ocr/{anggaran_id}', [AngkasController::class, 'ocr'])->name('upload.anggaran.ocr');
        Route::resource('anggaran', AngkasController::class)->names('upload.anggaran');
        
        // SPJ Fungsional Routes
        Route::get('/spj-fungsional/ocr/{spj_fungsional_id}', [SpjFungsionalController::class, 'ocr'])->name('upload.spj-fungsional.ocr');
        Route::resource('spj-fungsional', SpjFungsionalController::class)->names('upload.spj-fungsional');
        
        // OCR Routes (must be defined before resource routes)
        Route::get('/bku/ocr', [BkuController::class, 'ocr'])->name('upload.bku.ocr');
        Route::post('/bku/ocr/process', [BkuController::class, 'ocrProcess'])->name('upload.bku.ocr.process');
        Route::post('/bku/ocr/save', [BkuController::class, 'ocrSave'])->name('upload.bku.ocr.save');
        Route::get('/bku/{id}/details', [BkuController::class, 'getDetails'])->name('upload.bku.details');
        
        // BKU Routes (Upload BKU Perbulan)
        Route::resource('bku', BkuController::class)->names('upload.bku');
        
        Route::get('/rekening', [RekeningKoranController::class, 'index'])->name('upload.rekening');
        
        // SPJ Transaksi Routes
        Route::get('/spj-transaksi/ocr/{spj_transaksi_id}', [SpjTransaksiController::class, 'ocr'])->name('upload.spj-transaksi.ocr');
        Route::resource('spj-transaksi', SpjTransaksiController::class)->names('upload.spj-transaksi');
    });

    // Sinkronisasi Routes
    Route::prefix('sinkronisasi')->group(function () {
        Route::get('/rekening-bku', function () {
            return view('superadmin.sinkronisasi.rekening-bku');
        })->name('sinkronisasi.rekening-bku');
        
        Route::get('/spj-bku', function () {
            return view('superadmin.sinkronisasi.spj-bku');
        })->name('sinkronisasi.spj-bku');
        
        Route::get('/spj-bku2', function () {
            return view('superadmin.sinkronisasi.spj-bku2');
        })->name('sinkronisasi.spj-bku2');
        
        Route::get('/saldo', function () {
            return view('superadmin.sinkronisasi.saldo');
        })->name('sinkronisasi.saldo');
    });

    // Analisis Data Routes
    Route::resource('analisis', AnalisisController::class);

    // Laporan Routes
    Route::prefix('laporan')->group(function () {
        Route::get('/transaksi', function () {
            return view('superadmin.laporan.transaksi');
        })->name('laporan.transaksi');
        
        Route::get('/kelengkapan', function () {
            return view('superadmin.laporan.kelengkapan');
        })->name('laporan.kelengkapan');
        
        Route::get('/transfer', function () {
            return view('superadmin.laporan.transfer');
        })->name('laporan.transfer');
        
        Route::get('/distribusi', function () {
            return view('superadmin.laporan.distribusi');
        })->name('laporan.distribusi');
        
        Route::get('/temuan', function () {
            return view('superadmin.laporan.temuan');
        })->name('laporan.temuan');
        
        Route::prefix('audit')->group(function () {
            Route::get('/rkbd', function () {
                return view('superadmin.laporan.audit.rkbd');
            })->name('laporan.audit.rkbd');
            
            Route::get('/kepegawaian', function () {
                return view('superadmin.laporan.audit.kepegawaian');
            })->name('laporan.audit.kepegawaian');
            
            Route::get('/keuangan', function () {
                return view('superadmin.laporan.audit.keuangan');
            })->name('laporan.audit.keuangan');
            
            Route::get('/lainnya', function () {
                return view('superadmin.laporan.audit.lainnya');
            })->name('laporan.audit.lainnya');
        });
        
        Route::get('/rekap', function () {
            return view('superadmin.laporan.rekap');
        })->name('laporan.rekap');
        
        Route::get('/rincian', function () {
            return view('superadmin.laporan.rincian');
        })->name('laporan.rincian');
        
        Route::get('/modal', function () {
            return view('superadmin.laporan.modal');
        })->name('laporan.modal');
    });
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
