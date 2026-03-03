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
     * Format: 0001/{property_initial}/KGA-INV/XXI/2026
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

        // Get property initial from transaction's property relationship (uppercase)
        $propertyInitial = 'PK1'; // Default fallback
        if ($transaction->property && $transaction->property->initial) {
            $propertyInitial = strtoupper($transaction->property->initial);
        }

        // Format: 0001/{property_initial}/KGA-INV/XXI/2026
        $formattedSequence = str_pad($sequenceNumber, 4, '0', STR_PAD_LEFT);
        $romanMonth = self::$romanMonths[$month];

        return "{$formattedSequence}/{$propertyInitial}/KGA-INV/{$romanMonth}/{$year}";
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
     * Generate invoice numbers for a collection of transactions.
     * Uses true global rank so the number is stable regardless of filters or per_page.
     *
     * @param \Illuminate\Support\Collection $transactions
     * @return array Map of transaction idrec => invoice number
     */
    public static function generateBatch($transactions): array
    {
        $invoiceNumbers = [];

        // Group by year (one query per year)
        $byYear = $transactions->groupBy(function ($transaction) {
            $paidAt = $transaction->paid_at ? Carbon::parse($transaction->paid_at) : now();
            return $paidAt->format('Y');
        });

        foreach ($byYear as $year => $yearTransactions) {
            // Fetch ALL paid transaction idrecs for this year ordered chronologically.
            // This gives us the definitive global rank for every transaction in the year,
            // regardless of any active filter or page size.
            $orderedIdrecs = Transaction::where('transaction_status', 'paid')
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $year)
                ->orderBy('paid_at', 'asc')
                ->orderBy('idrec', 'asc')
                ->pluck('idrec')
                ->toArray();

            // Build rank map: idrec => 1-based global rank
            $rankMap = array_flip($orderedIdrecs); // idrec => 0-based index

            foreach ($yearTransactions as $transaction) {
                $rank = isset($rankMap[$transaction->idrec])
                    ? $rankMap[$transaction->idrec] + 1
                    : 1;
                $invoiceNumbers[$transaction->idrec] = self::generate($transaction, $rank);
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
