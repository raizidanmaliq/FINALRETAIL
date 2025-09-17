<?php

namespace App\Http\Controllers\Back\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\SupplierRequest;
use App\Models\Supplier;
use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('back.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        try {
            $this->supplierService->store($request);
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('back.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $this->supplierService->update($supplier, $request);
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->destroy($supplier);
            return response()->json(['message' => 'Supplier deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Generate data for DataTables.
     */
    public function data()
    {
        return $this->supplierService->generateDataTables();
    }
}
