<?php

namespace App\Http\Controllers;

use App\Http\Resources\File\FileIndexResource;
use App\Models\File;
use App\Models\User;
use Inertia\Inertia;
use App\Jobs\ProcessFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::whereBelongsTo(Auth::user())->get();

        return Inertia::render('File', [
            'files' => fn () => FileIndexResource::collection($files),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
    ): RedirectResponse
    {
        $request->validate(
            [
                'file' => 'required|file|mimes:pdf|max:10240',
            ],
            [
                'file.required' => 'The file is required',
                'file.file' => 'The file must be a file',
                'file.mimes' => 'The file must be a PDF file',
                'file.max' => 'The file must not be greater than 10MB',
            ]
        );

        $file = $request->file('file');

        try {
            DB::transaction(function () use ($file) {
                /** @var User $user */
                $user = Auth::user();

               $fileModel = $user->files()->create([
                'path' => $file->store('files', 'public'),
                'size' => round(($file->getSize() / 1024) / 1024, 2),
               ]);


               ProcessFileJob::dispatch($fileModel);
            });

            return redirect()->back()->with('success', 'File uploaded successfully');
        } catch (\Throwable $th) {
            report($th);

            return redirect()->back()->withErrors(['message' => 'File upload failed']);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
