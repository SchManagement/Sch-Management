<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('whatsapp', 32);
            $table->string('project_type');
            $table->string('budget_range')->nullable();
            $table->string('target_timeline')->nullable();
            $table->longText('message');
            $table->string('status', 32)->default('new');
            $table->string('source', 64);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('email_send_failed_at')->nullable();
            $table->text('email_failure_message')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('project_type');
            $table->index('created_at');
            $table->index(['source', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
