<?php

use App\Enums\TipoExpediente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conasis.t_expediente', function (Blueprint $table) {
            $table->string('tipoExpediente', 1)->nullable()->after('codigo');
        });
    }

    public function down(): void
    {
        Schema::table('conasis.t_expediente', function (Blueprint $table) {
            $table->dropColumn('tipoExpediente');
        });
    }
};
