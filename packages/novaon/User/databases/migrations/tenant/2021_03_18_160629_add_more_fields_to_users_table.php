<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('provider_agent_id');
            $table->string('job_title')->nullable()->after('company_name');
            $table->string('language')->nullable()->after('phone_number');
            $table->string('timezone')->nullable()->after('language');
            $table->string('facebook')->nullable()->after('timezone');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('skype')->nullable()->after('linkedin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'job_title',
                'language',
                'timezone',
                'facebook',
                'linkedin',
                'skype'
            ]);
        });
    }
}
