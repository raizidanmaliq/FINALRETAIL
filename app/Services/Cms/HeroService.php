<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Cms\UpdateHeroRequest;
use App\Models\Cms\Hero;
use Yajra\DataTables\Facades\DataTables;

class HeroService {
    protected $imageHelper;

    public function __construct() {
        $this->imageHelper = new ImageHelpers('back_assets/img/cms/heroes/');
    }

    public function store(UpdateHeroRequest $request) {
        $validatedData = $request->validated();
        try {
            $isActive = isset($validatedData['is_active']) && $validatedData['is_active'];

            // If a new hero is to be active, deactivate all existing heroes first
            if ($isActive) {
                Hero::where('is_active', true)->update(['is_active' => false]);
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $this->imageHelper->uploadFile($image);
                }
            }

            Hero::create([
                'headline' => $validatedData['headline'] ?? null,
                'subheadline' => $validatedData['subheadline'] ?? null,
                'images' => $imagePaths,
                'is_active' => $isActive,
            ]);

            // If no hero was active before and the new one wasn't set to active,
            // we must make sure at least one is active.
            if (!Hero::where('is_active', true)->exists()) {
                Hero::orderBy('created_at', 'desc')->first()->update(['is_active' => true]);
            }

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateHeroRequest $request, Hero $hero) {
        $validatedData = $request->validated();

        try {
            $imagePaths = $hero->images;
            if ($request->hasFile('images')) {
                // Delete old images before uploading new ones
                if ($hero->images) {
                    foreach ($hero->images as $oldImage) {
                        $this->imageHelper->deleteImage($oldImage);
                    }
                }

                $newImagePaths = [];
                foreach ($request->file('images') as $image) {
                    $newImagePaths[] = $this->imageHelper->uploadFile($image);
                }
                $imagePaths = $newImagePaths;
            }

            // Check if this is the last active hero and the user is trying to deactivate it
            if ($hero->is_active && isset($validatedData['is_active']) && !$validatedData['is_active']) {
                $activeHeroesCount = Hero::where('is_active', true)->count();
                if ($activeHeroesCount === 1) {
                    // Prevent deactivation and keep it active
                    $validatedData['is_active'] = true;
                }
            }

            // If this hero is set to be active, deactivate all others
            if (isset($validatedData['is_active']) && $validatedData['is_active']) {
                Hero::where('is_active', true)
                    ->where('id', '!=', $hero->id)
                    ->update(['is_active' => false]);
            }

            $hero->update(array_merge($validatedData, [
                'images' => $imagePaths,
            ]));

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Hero $hero) {
        try {
            // Check if the hero being deleted is the last active one
            if ($hero->is_active) {
                $activeHeroesCount = Hero::where('is_active', true)->count();
                if ($activeHeroesCount === 1) {
                    throw new \Exception('Tidak bisa menghapus hero aktif terakhir. Silakan aktifkan hero lain terlebih dahulu.');
                }
            }

            // Delete all images associated with this hero entry
            if ($hero->images) {
                foreach ($hero->images as $image) {
                    $this->imageHelper->deleteImage($image);
                }
            }

            $hero->delete();

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(object $hero) {
        $array = $hero->get(['id', 'headline', 'subheadline', 'images', 'is_active']);
        $data = [];
        $no = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no'] = $no;
            $nestedData['headline'] = $item->headline;
            $nestedData['subheadline'] = $item->subheadline;

            // Generate HTML for multiple images
            $imagesHtml = '';
            if (!empty($item->images)) {
                foreach ($item->images as $imagePath) {
                    $imagesHtml .= '<img src="' . asset($imagePath) . '" width="80" height="50" style="margin-right: 5px;">';
                }
            }
            $nestedData['image'] = $imagesHtml;

            $nestedData['status'] = $item->is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Tidak Aktif</span>';
            $nestedData['actions'] = '
                <div class="btn-group">
                    <a href="' . route('admin.cms.heroes.edit', $item) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.cms.heroes.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)
            ->rawColumns(["actions", "image", "status"])
            ->toJson();
    }
}
