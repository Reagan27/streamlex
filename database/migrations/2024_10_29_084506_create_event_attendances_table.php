<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_event_id')
                  ->constrained('training_events')
                  ->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name');
            $table->string('id_number');
            $table->string('phone_number');
            $table->string('email');
            $table->text('signature');
            $table->boolean('is_authenticated_user')->default(false);
            $table->boolean('is_registration')->default(false);
            $table->integer('days_attended')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_attendances');
    }
};