<?php

use App\Models\User;
use App\Enums\FileType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->foreignIdFor(User::class);

            $table->string('path');
            $table->string('name')->nullable();
            $table->string('size')->nullable();
            $table->string('author')->nullable();
            $table->string('pages')->nullable();
            $table->string('type')->default(FileType::PDF);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
