<?php

namespace App\Events;

use App\Models\File;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FilesStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public File $file)
    {
        $this->file = $file;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('file-status.'.$this->file->id),
        ];
    }

    public function broadcastWith(): array
    {
        $progress = 0;

        if ($this->file->total_chunks > 0) {
            /* @phpstan-ignore-next-line */
            $progress = round(($this->file->processed_chunks / $this->file->total_chunks) * 100);
        }

        return [
            'uuid' => $this->file->uuid,
            'processed' => $this->file->processed_chunks,
            'total' => $this->file->total_chunks,
            'progress' => $progress,
            'status' => $this->file->status,
        ];
    }
}
