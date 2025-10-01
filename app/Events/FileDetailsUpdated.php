<?php

namespace App\Events;

use App\Http\Resources\File\FileIndexResource;
use App\Models\File;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;  
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class FileDetailsUpdated implements ShouldBroadcast
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
        Log::info("FileDetailsUpdatedEvent dispatched for user: " . $this->file->user_id);

        return [
            new PrivateChannel('user-files.' . $this->file->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'file' => new FileIndexResource($this->file),
        ];
    }
}
