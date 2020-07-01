<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use App\Traits\Recurring;
use Bkwld\Cloner\Cloneable;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;

class Transaction extends Model
{
    use Cloneable, Currencies, DateTime, Media, Recurring;

    protected $table = 'transactions';

    protected $dates = ['deleted_at', 'paid_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'account_id', 'paid_at', 'amount', 'currency_code', 'currency_rate', 'document_id', 'contact_id', 'description', 'category_id', 'payment_method', 'reference', 'parent_id', 'transfer_account'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['paid_at', 'amount','category.name', 'account.name'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['recurring'];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    public function bill()
    {
        return $this->belongsTo('App\Models\Purchase\Bill', 'document_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice', 'document_id');
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'contact_id', 'id');
    }

    public function transfer()
    {
        return $this->hasOne('App\Models\Banking\Transfer', 'expense_transaction_id', 'id');
    }

    /**
     * Scope to only include contacts of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->table . '.type', (array) $types);
    }

    /**
     * Scope to include only income.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncome($query)
    {
        return $query->where($this->table . '.type', '=', 'income');
    }

    /**
     * Scope to include only expense.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpense($query)
    {
        return $query->where($this->table . '.type', '=', 'expense');
    }

    /**
     * Get only transfers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsTransfer($query)
    {
        return $query->where('type', '=', 'transfer');
    }

    /**
     * Skip transfers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNotTransfer($query)
    {
        return $query->where('type', '<>', 'transfer');
    }

    /**
     * Get only documents (invoice/bill).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsDocument($query)
    {
        return $query->whereNotNull('document_id');
    }

    /**
     * Get only transactions (revenue/payment).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNotDocument($query)
    {
        return $query->whereNull('document_id');
    }

    /**
     * Get by document (invoice/bill).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  integer $document_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDocument($query, $document_id)
    {
        return $query->where('document_id', '=', $document_id);
    }

    /**
     * Order by paid date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    /**
     * Scope paid invoice.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->sum('amount');
    }

    /**
     * Get only reconciled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsReconciled($query)
    {
        return $query->where('reconciled', 1);
    }

    /**
     * Get only not reconciled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsNotReconciled($query)
    {
        return $query->where('reconciled', 0);
    }

    public function onCloning($src, $child = null)
    {
        $this->document_id = null;
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }

    /**
     * Convert amount to double.
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        static $currencies;

        $amount = $this->amount;

        // Convert amount if not same currency
        if ($this->account->currency_code != $this->currency_code) {
            if (empty($currencies)) {
                $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();
            }

            $default_currency = setting('default.currency', 'USD');

            $default_amount = $this->amount;

            if ($default_currency != $this->currency_code) {
                $default_amount_model = new Transaction();

                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $this->amount;
                $default_amount_model->currency_code = $this->currency_code;
                $default_amount_model->currency_rate = $this->currency_rate;

                $default_amount = $default_amount_model->getAmountConvertedToDefault();
            }

            $transfer_amount = new Transaction();

            $transfer_amount->default_currency_code = $this->currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $this->account->currency_code;
            $transfer_amount->currency_rate = $currencies[$this->account->currency_code];

            $amount = $transfer_amount->getAmountConvertedFromDefault();
        }

        return $amount;
    }

    /**
     * Get the attachment.
     *
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->last();
    }

    /**
     * Get transfer value with + / - signal
     *
     * @return double
     */
    public function getTransferValueAttribute()
    {
        // if has transfer account, it's negative (transfer out)
        if (!empty($this->transfer_account)) {
            return - $this->amount;
        } else {
            return $this->amount; // positive (transfer in)
        }
    }

    /**
     * Change boot method in order to create transfer 2nd transaction
     * and Transfer model
     *
     */
    public static function boot()
    {
        parent::boot();

        Transaction::saved(function($from_transaction)
        {
            // check if transfer_account is defined
            if (empty($from_transaction->transfer_account))
                return;

            $transfer = Transfer::firstWhere('expense_transaction_id', $from_transaction->id);

            // check if there is a tranfer record for this transaction
            if (is_null($transfer)) {
                // create a new transaction for "transfer to" transaction
                $to_transaction = new Transaction;
            } else {
                $to_transaction = Transaction::find($transfer->income_transaction_id);
            }

            // copy values from origin
            foreach ($from_transaction->attributes as $key => $value) {
                if ($key != 'id')
                    $to_transaction->{$key} = $value;
            }

            // Convert amount if not same currency
            $from_currency = Account::where('id', $from_transaction->account_id)->pluck('currency_code')->first();
            $to_currency = Account::where('id', $from_transaction->transfer_account)->pluck('currency_code')->first();
            $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

            if ($from_currency != $to_currency) {
                $default_currency = setting('default.currency', 'USD');

                $default_amount = $from_transaction->amount;

                if ($default_currency != $from_currency) {
                    $default_amount_model = new Transfer();

                    $default_amount_model->default_currency_code = $default_currency;
                    $default_amount_model->amount = $from_transaction->amount;
                    $default_amount_model->currency_code = $from_currency;
                    $default_amount_model->currency_rate = $currencies[$from_currency];

                    $default_amount = $default_amount_model->getAmountConvertedToDefault();
                }

                $transfer_amount = new Transfer();

                $transfer_amount->default_currency_code = $from_currency;
                $transfer_amount->amount = $default_amount;
                $transfer_amount->currency_code = $to_currency;
                $transfer_amount->currency_rate = $currencies[$to_currency];

                $amount = $transfer_amount->getAmountConvertedFromDefault();
            }

            // ensures that it won't be looping setting transfer_account = null
            $to_transaction->account_id = $from_transaction->transfer_account;
            $to_transaction->transfer_account = null;
            $to_transaction->save();

            // create transfer model
            if (is_null($transfer)) {
                Transfer::create([
                    'company_id' => $from_transaction->company_id,
                    'expense_transaction_id' => $from_transaction->id,
                    'income_transaction_id' => $to_transaction->id,
                ]);

            }
        });
    }

}
