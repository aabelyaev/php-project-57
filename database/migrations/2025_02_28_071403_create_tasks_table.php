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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //обязательное. Название задачи
            $table->string('description')->nullable(); //необязательное. Описание задачи

            $table->bigInteger('status_id'); //обязательное. Связано с сущностью статуса
            $table->foreign('status_id')->references('id')->on('task_statuses');

            //обязательное. Связано с сущностью пользователя. Создатель задачи
            $table->bigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');



            $table->integer('assigned_to_id')->nullable(); //необязательное. Связано с сущностью пользователя. Тот на кого поставлена задача
            $table->foreign('assigned_to_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
