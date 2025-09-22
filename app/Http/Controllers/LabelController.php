<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreLabelRequest;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::paginate();
        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $label = new Label();
        Gate::authorize('create', $label);
        return view('labels.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        $label = new Label();
        Gate::authorize('store', $label);
        $data = $request->validated();
        $label->fill($data);
        $label->description = $request->description;
        $label->save();
        flash('Метка успешно создана')->success();
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        Gate::authorize('edit', $label);
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $label = Label::findOrFail($label->id);
        Gate::authorize('update', $label);
        $data = $request->validate([
            'name' => "required",
        ]);
        $label->description = $request->description;
        $label->fill($data);
        $label->save();
        flash('Метка успешно изменена')->success();
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label, Request $request)
    {
        Gate::authorize('delete', $label);
        if (($label->tasks->count()) > 0) {
            $request->session()->flash('error', 'Не удалось удалить метку');
        } else {
            $label->delete();
            flash('Метка успешно удалена')->success();
        }
        return redirect()->route('labels.index');
    }
}
