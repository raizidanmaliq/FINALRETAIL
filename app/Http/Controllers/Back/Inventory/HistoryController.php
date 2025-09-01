<?php

namespace App\Http\Controllers\Back\Inventory; // Perbaikan 1: Ubah namespace dari Stock menjadi Inventory

use App\Http\Controllers\Controller;
use App\Services\Inventory\HistoryService; // Perbaikan 2: Ubah Stock menjadi Inventory

class HistoryController extends Controller
{
    protected $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Menampilkan halaman riwayat mutasi stok.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mutations = $this->historyService->getStockMutations();
        return view('back.inventory.history.index', compact('mutations')); // Perbaikan 3: Ubah view path
    }
}
