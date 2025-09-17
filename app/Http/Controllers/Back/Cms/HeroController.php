<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\UpdateHeroRequest;
use App\Models\Cms\Hero;
use App\Services\Cms\HeroService;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    protected $heroService;

    public function __construct(HeroService $heroService)
    {
        $this->heroService = $heroService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('back.cms.heroes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.cms.heroes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateHeroRequest $updateHeroRequest)
    {
        $this->heroService->store($updateHeroRequest);

        return redirect()->route('admin.cms.heroes.index')->with('success', 'Hero Berhasil Ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Hero $hero)
    {
        return view('back.cms.heroes.edit', compact('hero'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHeroRequest $updateHeroRequest, Hero $hero)
    {
        $this->heroService->update($updateHeroRequest, $hero);

        return redirect()->route('admin.cms.heroes.index')->with('success', 'Hero Berhasil Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hero $hero)
    {
        $this->heroService->destroy($hero);

        return response()->json(['message' => "Hero berhasil dihapus"], 200);
    }

    public function data(Hero $hero)
    {
        return $this->heroService->data($hero);
    }
}
