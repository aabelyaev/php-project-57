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
        return view('label.index', ['labels' => Label::paginate(3)]);
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
        Label::create($request->all());
        flash('Метка успешно создана')->success();

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
        $label->update($request->all());
        flash('Метка успешно изменена')->success();

        return redirect(route('labels.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label): RedirectResponse
    {
        if ($label->tasks->isNotEmpty()) {
            flash('Не удалось удалить метку')->error();

            return redirect(route('labels.index'));
        }

        $label->delete();
        flash('Метка успешно удалена')->success();

        return redirect(route('labels.index'));
    }
}
