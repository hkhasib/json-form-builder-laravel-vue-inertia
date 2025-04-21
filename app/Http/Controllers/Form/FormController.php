<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFormRequest;
use App\Http\Requests\FormRequest;
use App\Models\Form;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use function Termwind\render;

class FormController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (auth()->user()->isAdmin()) {
            $forms = Form::with('fields')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $forms = Form::where('user_id', auth()->id())
                ->with('fields')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return Inertia::render('Forms/Index', [
            'forms' => $forms
        ]);
    }

    public function fetchForms(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $forms = Form::with('fields')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $forms = Form::where('user_id', auth()->id())
                ->with('fields')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return response()->json($forms);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Forms/Create');
    }

    public function draft(CustomFormRequest $request)
    {
        Log::info($request);
        $data = $request->validated();

        Log::info("Here is the data: ", [$data]);

        DB::beginTransaction();
        try{
            $form = Form::create([
                'user_id' => auth()->id(),
                'title' => $data['title'],
                'action' => $data['action'],
                'method' => $data['method'],
                'status' => $data['status'] ?? 'draft',
            ]);

            $form->fields()->createMany($data['fields']);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
            return back()->with('error', 'Something went wrong!');
        }

        return redirect()->route('form.edit',$form->id)->with('success', 'Form created successfully!');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $form = Form::with('fields')->find($id);
        if ($form->status == 'published') {
            return Inertia::render('Forms/Show', [
                'form' => $form,
                'id' => $id
            ]);
        } else {
            return redirect()->back()->with('error', 'Form not found');
        }
    }

    public function preview(string $id)
    {
        $form = Form::with('fields')->find($id);
        if ($form->status !== 'archived') {
            return Inertia::render('Forms/Show', [
                'form' => $form,
                'id' => $id
            ]);
        } else {
            return redirect()->back()->with('error', 'Form not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {



        $form= Form::with('fields')->find($id);

        //We are checking whether the user can edit the form or not
        $this->authorize('update', $form);

        if ($form) {
            return Inertia::render('Forms/Edit', [
                'form' => $form,
                'id' => $id
            ]);
        } else {
            return redirect()->back()->with('error', 'Form not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomFormRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $form = Form::with('fields')->find($id);

            //checking if the form can be updated by the current user
            $this->authorize('update', $form);

            $form->update([
                'title' => $validated['title'],
                'action' => $validated['action'],
                'method' => $validated['method'],
                'status' => $validated['status'] ?? 'draft',
            ]);

            foreach ($validated['fields'] as $fieldUpdate) {
                $field = $form->fields->where('id', $fieldUpdate['id'] ?? null)->first();
                Log::info($field);
                if ($field) {
                    $field->update([
                        'name' => $fieldUpdate['name'],
                        'type' => $fieldUpdate['type'],
                        'label' => $fieldUpdate['label'] ?? null,
                        'placeholder' => $fieldUpdate['placeholder'] ?? null,
                        'required' => $fieldUpdate['required'] ?? false,
                        'position' => $fieldUpdate['position'],
                        'class_name' => $fieldUpdate['class_name'] ?? null,
                        'custom_style' => $fieldUpdate['custom_style'] ?? null,
                        'options' => $fieldUpdate['options'] ?? [],
                    ]);
                }
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return back()->with('error', 'Something went wrong!');
        }

        return back()->with('success', 'Form updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
