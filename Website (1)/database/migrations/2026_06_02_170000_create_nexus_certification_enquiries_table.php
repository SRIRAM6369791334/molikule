<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nexus_certification_enquiries')) {
            Schema::create('nexus_certification_enquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_no', 20);
                $table->string('email');
                $table->string('company_name');
                $table->string('segment');
                $table->text('thoughts');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_certification_enquiries');
    }
};
