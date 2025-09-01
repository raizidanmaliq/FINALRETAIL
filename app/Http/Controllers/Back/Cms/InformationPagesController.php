<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\UpdateInformationPagesRequest;
use App\Models\Cms\InformationPages;
use App\Services\Cms\InformationPagesService;

class InformationPagesController extends Controller
{
    protected $informationPagesService;

    public function __construct(InformationPagesService $informationPagesService)
    {
        $this->informationPagesService = $informationPagesService;
    }

    public function index()
    {
        return view('back.cms.information-pages.index');
    }

    public function edit(string $slug)
    {
        // Tentukan slug yang diizinkan
        $allowedSlugs = ['general-settings', 'privacy-policy', 'terms-and-conditions'];

        // Jika slug tidak diizinkan, kembalikan 404
        if (!in_array($slug, $allowedSlugs)) {
            abort(404);
        }

        $informationPage = InformationPages::firstOrNew(['slug' => $slug]);

        // Khusus untuk 'general-settings', jika belum ada, buat entri baru
        if (!$informationPage->exists && $slug === 'general-settings') {
            $informationPage->title = 'Pengaturan Umum';
            $informationPage->content = ''; // Set default content
            $informationPage->save();
        }

        // Jika halaman tidak ada dan bukan 'general-settings', kembalikan 404
        if (!$informationPage->exists && $slug !== 'general-settings') {
            abort(404);
        }

        return view('back.cms.information-pages.edit', compact('informationPage'));
    }

    public function update(UpdateInformationPagesRequest $request, string $slug)
    {
        $this->informationPagesService->update($request, $slug);

        return redirect()->back()->with('success', 'Data Berhasil Diperbaharui');
    }
}
