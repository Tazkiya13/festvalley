<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Event details from booking form
            $table->string('event_type')->nullable()->after('rejection_reason');
            $table->date('event_date')->nullable()->after('event_type');
            $table->string('event_time')->nullable()->after('event_date');
            $table->text('event_location')->nullable()->after('event_time');
            $table->text('venue_address')->nullable()->after('event_location');
            $table->text('event_description')->nullable()->after('venue_address');
            $table->text('special_requirements')->nullable()->after('event_description');
            $table->text('technical_requirements')->nullable()->after('special_requirements');
            
            // Additional booking details
            $table->integer('guest_count')->nullable()->after('technical_requirements');
            $table->string('event_duration')->nullable()->after('guest_count');
            $table->string('budget_range')->nullable()->after('event_duration');
            
            // Customer details (in case user account doesn't have them)
            $table->string('customer_phone')->nullable()->after('budget_range');
            $table->string('customer_company')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'event_type',
                'event_date', 
                'event_time',
                'event_location',
                'venue_address',
                'event_description',
                'special_requirements',
                'technical_requirements',
                'guest_count',
                'event_duration',
                'budget_range',
                'customer_phone',
                'customer_company'
            ]);
        });
    }
};
