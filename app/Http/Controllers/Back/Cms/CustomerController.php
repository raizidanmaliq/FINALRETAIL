<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\CustomerRequest;
use App\Models\Customer;
use App\Services\Cms\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        return view('back.cms.customers.index');
    }

    public function create()
    {
        return view('back.cms.customers.create');
    }

    public function store(CustomerRequest $request)
    {
        $this->customerService->store($request);
        return redirect()->route('admin.cms.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('back.cms.customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->customerService->update($request, $customer);
        return redirect()->route('admin.cms.customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->destroy($customer);
        return response()->json(['message' => "Data pelanggan berhasil dihapus"], 200);
    }

    public function data()
    {
        return $this->customerService->data();
    }
}
