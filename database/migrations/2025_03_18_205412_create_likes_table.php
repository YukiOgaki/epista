<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'comment_id']); // 同じユーザーが同じコメントに複数回イイネできないようにする
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
