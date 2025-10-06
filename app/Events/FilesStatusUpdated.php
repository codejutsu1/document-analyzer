<?php

namespace App\Events;

use App\Models\File;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FilesStatusUpdated implements ShouldBroadcast
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
            new PrivateChannel('file-status.' . $this->file->id),
        ];
    }

    public function broadcastWith(): array
    {
        $progress = 0;

        if ($this->file->total_chunks > 0) {
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
