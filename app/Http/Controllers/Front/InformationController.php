<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cms\InformationPages;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Display a specific information page based on its slug.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        // Cari halaman berdasarkan slug. Jika tidak ditemukan, tampilkan 404 (firstOrFail).
        $informationPage = InformationPages::where('slug', $slug)->firstOrFail();

        // Kirim data halaman ke view yang sudah kita buat.
        return view('front.information.show', compact('informationPage'));
    }
}
