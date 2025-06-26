<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('iban')->nullable()->after('email');
            $table->string('bic')->nullable()->after('iban');
            $table->string('bank_name')->nullable()->after('bic');
            $table->string('chairman_name')->nullable()->after('bank_name');
            $table->string('logo_path')->nullable()->after('chairman_name');
            $table->string('pdf_template')->nullable()->after('logo_path'); // z. B. Hintergrundbriefbogen
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'iban',
                'bic',
                'bank_name',
                'chairman_name',
                'logo_path',
                'pdf_template',
            ]);
        });
    }
};
