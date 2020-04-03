<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->date('date');
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('document_number');
            $table->unique(['document_type_id', 'document_number']);
            $table->text('explanation');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
            $table->foreign('document_type_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('postings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->decimal('debit', 13, 4);
            $table->unsignedBigInteger('subsidiary_ledger_id')->nullable();
            $table->unsignedBigInteger('report_line_item_id')->nullable();
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
            $table->foreign('subsidiary_ledger_id')
                ->references('id')
                ->on('subsidiary_ledgers')
                ->onDelete('cascade');
            $table->foreign('report_line_item_id')
                ->references('id')
                ->on('report_line_items')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('journal_entry_posting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id');
            $table->unsignedBigInteger('posting_id');
            $table->unique(['journal_entry_id', 'posting_id']);
            $table->foreign('journal_entry_id')
                ->references('id')
                ->on('journal_entries')
                ->onDelete('cascade');
            $table->foreign('posting_id')
                ->references('id')
                ->on('postings')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
}
