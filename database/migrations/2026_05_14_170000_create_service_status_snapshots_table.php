<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_status_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->date('recorded_on');
            $table->enum('status', ['operational', 'degraded', 'partial_outage', 'major_outage']);
            $table->decimal('uptime_percentage', 5, 2);
            $table->unsignedInteger('response_time_ms');
            $table->unsignedTinyInteger('incidents_count')->default(0);
            $table->timestamps();

            $table->unique(['service_id', 'recorded_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_status_snapshots');
    }
};
