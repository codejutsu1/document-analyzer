<?php

namespace App\Jobs;

use App\Enums\FileStatus;
use App\Events\FileDetailsUpdated;
use App\Models\File;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ProcessFileJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected File $file,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $parser = new Parser;

        $pdf = $parser->parseFile(
            Storage::disk('public')->path($this->file->path)
        );

        $pages = $pdf->getPages();
        $pagesCount = count($pages);

        $details = $pdf->getDetails();

        $author = $details['Author'] ?? null;
        $title = $details['Title'] ?? null;

        $this->file->update([
            'name' => $title,
            'author' => $author,
            'pages' => $pagesCount,
            'status' => FileStatus::PROCESSING,
        ]);

        // event(new FileDetailsUpdated($this->file));
        ProcessDocumentJob::dispatch($this->file);
    }
}
