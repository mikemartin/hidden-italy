<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_mobile')->nullable()->after('password');
        });

        // Carry forward whichever number the user had set previously.
        \DB::statement('UPDATE users SET phone_mobile = COALESCE(phone_home, phone_italy) WHERE phone_mobile IS NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_home',
                'phone_italy',
                'emergency_contact_name',
                'emergency_contact_email',
                'emergency_contact_phone',
                'postal_country',
                'postal_street',
                'postal_city',
                'postal_state',
                'postcode',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_home')->nullable();
            $table->string('phone_italy')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_email')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->string('postal_country')->nullable();
            $table->string('postal_street')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_state')->nullable();
            $table->string('postcode')->nullable();
        });

        \DB::statement('UPDATE users SET phone_home = phone_mobile WHERE phone_mobile IS NOT NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_mobile');
        });
    }
};
