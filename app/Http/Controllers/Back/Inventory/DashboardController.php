<?php
// app/Http/Controllers/Back/Inventory/DashboardController.php

namespace App\Http\Controllers\Back\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\DashboardService;

/**
 * Controller untuk menangani tampilan dashboard inventaris.
 * Bertanggung jawab untuk memuat data dari service dan menampilkannya ke view.
 */
class DashboardController extends Controller
{
    /**
     * @var DashboardService Instance dari DashboardService.
     */
    protected DashboardService $dashboardService;

    /**
     * Konstruktor untuk menginisialisasi DashboardService melalui dependency injection.
     * * @param DashboardService $dashboardService
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Menampilkan halaman dashboard inventaris.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan semua data ringkasan dashboard dari service
        $data = $this->dashboardService->getDashboardData();

        // Mengirim data ke view 'back.inventory.dashboard.index'
        return view('back.inventory.dashboard.index', compact('data'));
    }
}
