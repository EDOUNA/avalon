<?php

namespace App\Console\Commands;

use App\Models\BankAccounts;
use App\Models\Currencies;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Storage;

class ProcessImport extends Command
{
    /**
     * @var string
     */
    protected $signature = 'avalon:start-import';

    /**
     * @var string
     */
    protected $description = 'Starting the import process for ABN AMRO specifically';

    protected $tmpFile = 'tmp.STA';

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $storage = Storage::disk('local');
        if (!$storage->exists($this->tmpFile)) {
            Log::debug('File not found for processing');
            $this->error('File not found!');
            return false;
        }

        $parser = new \Kingsquare\Parser\Banking\Mt940();
        $parsedStatement = $parser->parse($storage->get($this->tmpFile));

        $bar = $this->output->createProgressBar(count($parsedStatement));
        $bar->start();

        foreach ($parsedStatement as $k => $l) {
            $primaryBankAccount = $this->matchPrimaryBankAccount($l->getAccount());
            foreach ($l->getTransactions() as $tK => $t) {
                $currency = $this->matchPrimaryCurrency('EUR');

                $transaction = Transactions::where('unique_hash', md5(json_encode($t->jsonSerialize())))->first();

                if (null !== $transaction) {
                    continue;
                }

                $newTransaction = new Transactions;
                $newTransaction->primary_bank_account = $primaryBankAccount;
                $newTransaction->currency_id = $currency;
                $newTransaction->transaction_date = Carbon::createFromTimestamp($t->getEntryTimestamp())->toDateTimeString();
                $newTransaction->amount = $t->isDebit() ? -$t->getPrice() : $t->getPrice();
                $newTransaction->description = $t->getDescription();

                $newTransaction->unique_hash = md5(json_encode($t->jsonSerialize()));
                $newTransaction->json_serialize = json_encode($t->jsonSerialize());

                $newTransaction->save();
            }

            $bar->advance();
        }

        $bar->finish();
    }

    /**
     * @param String $currencyCode
     * @return int
     */
    private function matchPrimaryCurrency(String $currencyCode): int
    {
        $currencyCode = strtoupper(trim($currencyCode));
        $currency = Currencies::where('code', $currencyCode)->first();

        if (null === $currency) {
            $newCurrency = new Currencies;
            $newCurrency->code = $currencyCode;
            $newCurrency->save();

            return $newCurrency->id;
        }

        return $currency->id;
    }

    /**
     * @param String $accountNumber
     * @return int
     */
    private function matchPrimaryBankAccount(String $accountNumber): int
    {
        $accountNumber = trim($accountNumber);
        $bankAccount = BankAccounts::where('account_number', $accountNumber)->first();

        // No primary bank account found, create a new entry
        if (null === $bankAccount) {
            $newBank = new BankAccounts;
            $newBank->account_number = $accountNumber;
            $newBank->save();

            return $newBank->id;
        }

        return $bankAccount->id;
    }
}
