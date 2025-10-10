<?php

namespace App\Jobs;

use App\Enums\FileStatus;
use App\Events\FilesStatusUpdated;
use App\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FinalizeFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $fileId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = File::findOrFail($this->fileId);

        if ($file->processed_chunks > $file->total_chunks) {
            $file->processed_chunks = $file->total_chunks;
            $file->save();
            $file->refresh();
        }

        if ($file->processed_chunks == $file->total_chunks) {
            /* @phpstan-ignore-next-line */
            $file->status = FileStatus::COMPLETED;
            $file->save();

            event(new FilesStatusUpdated($file));
        }
    }
}
