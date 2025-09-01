<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Http\Requests\Cms\UpdateInformationPagesRequest;
use App\Models\Cms\InformationPages;

class InformationPagesService
{
    public function update(UpdateInformationPagesRequest $request, string $slug)
    {
        $validatedData = $request->validated();

        try {
            // Temukan halaman berdasarkan slug dan perbarui dengan data yang divalidasi
            InformationPages::where('slug', $slug)->update($validatedData);
        } catch (\Exception $e) {
            // Gunakan Exception daripada Error
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }
}
