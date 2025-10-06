<?php

namespace App\Jobs;

use App\Models\File;
use App\Enums\FileStatus;
use App\Events\FilesStatusUpdated;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinalizeFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public File $file)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->file->processed_chunks > $this->file->total_chunks) {
            $this->file->processed_chunks = $this->file->total_chunks;
            $this->file->save();
            $this->file->refresh();
        }

        if($this->file->processed_chunks == $this->file->total_chunks) {
            $this->file->status = FileStatus::COMPLETED;
            $this->file->save();

            
            event(new FilesStatusUpdated($this->file));
        }
    }
}
