<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\UpdateCtaRequest;
use App\Models\Cms\Cta;
use App\Services\Cms\CtaService;
use Illuminate\Http\Request;

class CtaController extends Controller
{
    protected $ctaService;

    public function __construct(CtaService $ctaService)
    {
        $this->ctaService = $ctaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('back.cms.ctas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.cms.ctas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Cms\UpdateCtaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateCtaRequest $request)
    {
        $this->ctaService->store($request);

        return redirect()->route('admin.cms.ctas.index')->with('success', 'CTA Successfully Added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cms\Cta  $cta
     * @return \Illuminate\Http\Response
     */
    public function edit(Cta $cta)
    {
        return view('back.cms.ctas.edit', compact('cta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Cms\UpdateCtaRequest  $request
     * @param  \App\Models\Cms\Cta  $cta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCtaRequest $request, Cta $cta)
    {
        $this->ctaService->update($request, $cta);

        return redirect()->route('admin.cms.ctas.index')->with('success', 'CTA Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cms\Cta  $cta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cta $cta)
    {
        $this->ctaService->destroy($cta);

        return response()->json(['message' => "CTA successfully deleted"], 200);
    }

    public function data(Cta $cta)
    {
        return $this->ctaService->data($cta);
    }
}
