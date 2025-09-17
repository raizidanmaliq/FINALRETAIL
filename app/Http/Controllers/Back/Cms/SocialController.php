<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\UpdateSocialRequest;
use App\Models\Cms\Social;
use App\Services\Cms\SocialService;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    protected $socialService;

    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    public function index()
    {
        return view('back.cms.socials.index');
    }

    public function create()
    {
        return view('back.cms.socials.create');
    }

    public function store(UpdateSocialRequest $request)
    {
        $this->socialService->store($request);

        return redirect()->route('admin.cms.socials.index')->with('success', 'Social & E-commerce content added successfully');
    }

    public function edit(Social $social)
    {
        return view('back.cms.socials.edit', compact('social'));
    }

    public function update(UpdateSocialRequest $request, Social $social)
    {
        $this->socialService->update($request, $social);

        return redirect()->route('admin.cms.socials.index')->with('success', 'Social & E-commerce content updated successfully');
    }

    public function destroy(Social $social)
    {
        $this->socialService->destroy($social);

        return response()->json(['message' => "Social & E-commerce content deleted successfully"], 200);
    }

    public function data(Social $social)
    {
        return $this->socialService->data($social);
    }
}
