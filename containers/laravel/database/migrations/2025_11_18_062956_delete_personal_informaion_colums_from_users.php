<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *  - ユーザーIDはリレーションに必要なので物理削除したくない
 *  - ユーザーの個人情報は物理削除したい
 * →ユーザーテーブルは単なるリレーション用にして認証情報と個人情報は別テーブルに分けよう
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'email_verified_at',
                'password',
                'remember_token',
            ]);
            $table->enum('status', ['Active', 'Canceled', 'Frozen'])->default('Active')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        });
    }
};
