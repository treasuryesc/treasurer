<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Loans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tipos de empréstimo
        // 1 - Contrato com fiador
        // 2 - Contrato sem fiador
        // 3 - Antecipação
        Schema::create('loan_types', function (Blueprint $table) {
            $table->integer('company_id');           // id da empresa App\Models\Common\Company
            $table->integer('id');                   // tipo de empréstimo 
            $table->string('name');                  // descrição
            $table->json('attributes_schema');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['company_id', 'id']);
        });

        // Status de empréstimo
        // 1 - Aberto
        // 2 - Liberado
        // 3 - Encerrado
        Schema::create('loan_status', function (Blueprint $table) {
            $table->integer('company_id');            // id da empresa App\Models\Common\Company
            $table->integer('id');                    // status de empréstimo 
            $table->string('name');                   // descrição
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['company_id', 'id']);
        });

        // Tipos de recebíveis
        // 1 - Boleto
        // 2 - Crédito em conta
        // 3 - Cheque
        Schema::create('receivable_types', function (Blueprint $table) {
            $table->integer('company_id');       // id da empresa App\Models\Common\Company
            $table->integer('id');               // tipo de recebível
            $table->string('name');              // descrição
            $table->json('attributes_schema');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['company_id', 'id']);
        });

        // Status de recebíveis
        // 1 - Aberto
        // 2 - Atrasado
        // 3 - Pago
        Schema::create('receivable_status', function (Blueprint $table) {
            $table->integer('company_id');       // id da empresa App\Models\Common\Company
            $table->integer('id');               // status de recebível
            $table->string('name');              // descrição
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['company_id', 'id']);
        });

        // Contratos de empréstimo
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');                           // id do empréstimo
            $table->integer('company_id');                      // id da empresa App\Models\Common\Company
            $table->integer('customer_id');                     // id do cliente App\Models\Common\Contact
            $table->string('contract');                         // contrato
            $table->integer('type_id');                         // tipo de empréstimo (tabela loan_types->id)
            $table->integer('status_id');                       // status do empréstimo  (tabela loan_status->id)
            $table->integer('account_id');                      // id da conta App\Models\Banking\Account
            $table->integer('amortizations');                   // quantidade de parcelas
            $table->double('interest_rate', 15, 8);             // taxa de juros
            $table->double('amount', 15, 8);                    // valor do empréstimo
            $table->double('due', 15, 8);                       // saldo devido
            $table->dateTime('contract_at');                    // data do contrato
            $table->dateTime('lent_at')->nullable();            // data do depósito
            $table->dateTime('last_at')->nullable();            // data do último pagamento
            $table->integer('parent_id')->default(0);           // id do empréstimo de origem (tabela loans->id)
            $table->string('reference')->nullable();            // referência
            $table->string('index')->nullable();                // índice de reajuste
            $table->text('notes')->nullable();                  // observações
            $table->json('attributes');                         
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'contract']);
            $table->index(['company_id', 'customer_id']);
            $table->unique(['company_id', 'contract', 'parent_id', 'deleted_at'], 'loans_uniq1');
        });

        // Recebíveis
        Schema::create('receivables', function (Blueprint $table) {
            $table->increments('id');                       // id do recebível
            $table->integer('company_id');                  // id da empresa App\Models\Common\Company
            $table->integer('loan_id');                     // id do empréstimo (tabela loans->id)
            $table->integer('sequence');                      // número da parcela (sequencial 1, 2, 3...)
            $table->integer('customer_id');                 // id do cliente App\Models\Common\Contact
            $table->integer('type_id');                     // tipo de recebível (tabela receivable_types->id)
            $table->integer('status_id');                   // status do recebível (tabela receivable_status->id)
            $table->double('principal', 15, 8);             // valor do principal
            $table->double('interest', 15, 8);              // valor de juros
            $table->double('amount', 15, 8);                // valor do recebível
            $table->double('due', 15, 8);                   // valor devido
            $table->dateTime('due_at');                     // data de vencimento
            $table->dateTime('paid_at')->nullable();        // data de pagamento
            $table->string('reference')->nullable();        // referência
            $table->text('notes')->nullable();              // observações
            $table->json('attributes');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'loan_id', 'sequence']);
            $table->unique(['company_id', 'loan_id', 'sequence', 'deleted_at'], 'receivables_uniq1');
        });

        // Pagamentos recebidos
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');                       // id do recebimento
            $table->integer('company_id');                  // id da empresa App\Models\Common\Company
            $table->integer('receivable_id');               // id do recebível (tabela receivables->id)
            $table->integer('account_id');                  // id da conta App\Models\Banking\Account
            $table->integer('transaction_id');              // id da transação App\Models\Banking\Transaction
            $table->double('paid', 15, 8);                  // valor recebido
            $table->dateTime('paid_at')->nullable();        // data de pagamento
            $table->dateTime('credit_at')->nullable();      // data de recebimento
            $table->string('reference')->nullable();        // referência
            $table->text('notes')->nullable();              // observações
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'receivable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loans');
        Schema::drop('loan_types');
        Schema::drop('loan_status');
        Schema::drop('receivables');
        Schema::drop('receivable_types');
        Schema::drop('receivable_status');
        Schema::drop('incomes');
    }
}
