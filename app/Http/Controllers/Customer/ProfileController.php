<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\UpdateCustomerRequest;
use App\Services\MasterData\CustomerService;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        return view('customer.profile.index');
    }

    public function update(UpdateCustomerRequest $request, CustomerService $service)
    {
        $service->update($request, auth()->guard('customer')->user());

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
