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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('question'); // Texto da pergunta
            $table->text('answer'); // Resposta correta
            $table->string('language_id'); // Chave estrangeira para a tabela 'Linguagens'
            $table->string('category_id'); // Chave estrangeira para a tabela 'Categorias'
            $table->timestamps();

            // $table->foreign('language_id')->references('id')->on('languages');
            // $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
