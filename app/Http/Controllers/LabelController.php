<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Label::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('label.index', ['labels' => Label::paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request): RedirectResponse
    {
        Label::create($request->validated());
        flash(__('flashes.labels.create.success'))->success();

        return redirect(route('labels.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label): View
    {
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label): RedirectResponse
    {
        $label->update($request->validated());
        flash(__('flashes.labels.update.success'))->success();

        return redirect(route('labels.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label): RedirectResponse
    {
        if ($label->tasks()->exists()) {
            flash(__('flashes.labels.delete.error'))->error();

            return back();
        }

        $label->delete();
        flash(__('flashes.labels.delete.success'))->success();

        return redirect(route('labels.index'));
    }
}
