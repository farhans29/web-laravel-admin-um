<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    /**
     * Roman numeral mapping for months
     */
    protected static $romanMonths = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    /**
     * Generate invoice number based on paid_at date
     * Format: 0001/PK1/KGA-INV/XXI/2026
     *
     * @param Transaction $transaction
     * @param int|null $sequenceNumber Optional override for sequence number
     * @return string
     */
    public static function generate(Transaction $transaction, ?int $sequenceNumber = null): string
    {
        $paidAt = $transaction->paid_at ? Carbon::parse($transaction->paid_at) : now();
        $year = $paidAt->format('Y');
        $month = (int) $paidAt->format('m');

        // Get sequence number if not provided
        if ($sequenceNumber === null) {
            $sequenceNumber = self::getSequenceNumber($transaction, $year);
        }

        // Format: 0001/PK1/KGA-INV/XXI/2026
        $formattedSequence = str_pad($sequenceNumber, 4, '0', STR_PAD_LEFT);
        $romanMonth = self::$romanMonths[$month];

        return "{$formattedSequence}/PK1/KGA-INV/{$romanMonth}/{$year}";
    }

    /**
     * Get sequence number for a transaction within a year
     *
     * @param Transaction $transaction
     * @param string $year
     * @return int
     */
    protected static function getSequenceNumber(Transaction $transaction, string $year): int
    {
        // Count transactions paid before this one in the same year
        $count = Transaction::where('transaction_status', 'paid')
            ->whereNotNull('paid_at')
            ->whereYear('paid_at', $year)
            ->where(function ($query) use ($transaction) {
                $query->where('paid_at', '<', $transaction->paid_at)
                    ->orWhere(function ($q) use ($transaction) {
                        $q->where('paid_at', '=', $transaction->paid_at)
                            ->where('idrec', '<=', $transaction->idrec);
                    });
            })
            ->count();

        return $count > 0 ? $count : 1;
    }

    /**
     * Generate invoice numbers for a collection of transactions
     * More efficient batch method - calculates sequence in one query
     *
     * @param \Illuminate\Support\Collection $transactions
     * @return array Map of transaction idrec => invoice number
     */
    public static function generateBatch($transactions): array
    {
        $invoiceNumbers = [];

        // Group transactions by year
        $byYear = $transactions->groupBy(function ($transaction) {
            $paidAt = $transaction->paid_at ? Carbon::parse($transaction->paid_at) : now();
            return $paidAt->format('Y');
        });

        foreach ($byYear as $year => $yearTransactions) {
            // Get the count of all paid transactions before the earliest one in our set
            $earliestPaidAt = $yearTransactions->min(function ($t) {
                return $t->paid_at ? Carbon::parse($t->paid_at)->timestamp : now()->timestamp;
            });

            $baseCount = Transaction::where('transaction_status', 'paid')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $year)
                ->where('paid_at', '<', Carbon::createFromTimestamp($earliestPaidAt))
                ->count();

            // Sort transactions by paid_at and idrec
            $sorted = $yearTransactions->sortBy([
                ['paid_at', 'asc'],
                ['idrec', 'asc'],
            ])->values();

            foreach ($sorted as $index => $transaction) {
                $sequenceNumber = $baseCount + $index + 1;
                $invoiceNumbers[$transaction->idrec] = self::generate($transaction, $sequenceNumber);
            }
        }

        return $invoiceNumbers;
    }

    /**
     * Get Roman numeral for month
     *
     * @param int $month
     * @return string
     */
    public static function getRomanMonth(int $month): string
    {
        return self::$romanMonths[$month] ?? 'I';
    }
}
