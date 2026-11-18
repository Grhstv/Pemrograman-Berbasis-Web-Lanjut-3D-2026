<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained('users')->nullOnDelete();
        });

        // Hubungkan anggota lama dengan akun user yang emailnya sama.
        DB::table('anggotas')->whereNull('user_id')->orderBy('id')->each(function ($a) {
            $uid = DB::table('users')->where('email', $a->email)->value('id');
            if ($uid) {
                DB::table('anggotas')->where('id', $a->id)->update(['user_id' => $uid]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
