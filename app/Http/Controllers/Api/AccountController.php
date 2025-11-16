<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Account::with('parentAccount')->active();

        // Filter by type
        if ($request->has('account_type')) {
            $query->ofType($request->account_type);
        }

        // Filter top-level accounts only
        if ($request->boolean('top_level_only')) {
            $query->topLevel();
        }

        $accounts = $query->orderBy('account_code')->get();

        return response()->json([
            'success' => true,
            'data' => $accounts,
        ]);
    }

    /**
     * Display the specified account
     */
    public function show(Account $account): JsonResponse
    {
        $account->load(['parentAccount', 'childAccounts']);
        $account->current_balance = $account->currentBalance();

        return response()->json([
            'success' => true,
            'data' => $account,
        ]);
    }

    /**
     * Get account balance
     */
    public function balance(Request $request, Account $account): JsonResponse
    {
        $asOfDate = $request->input('as_of_date', now());

        $balance = $account->balanceAsOf($asOfDate);

        return response()->json([
            'success' => true,
            'data' => [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'account_type' => $account->account_type,
                'normal_balance' => $account->normal_balance,
                'current_balance' => $balance,
                'as_of_date' => $asOfDate,
            ],
        ]);
    }

    /**
     * Get chart of accounts (hierarchical)
     */
    public function chartOfAccounts(): JsonResponse
    {
        $accounts = Account::with('childAccounts')
            ->active()
            ->topLevel()
            ->orderBy('account_code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $accounts,
        ]);
    }

    /**
     * Get accounts by type
     */
    public function byType(string $type): JsonResponse
    {
        $accounts = Account::active()
            ->ofType($type)
            ->orderBy('account_code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $accounts,
        ]);
    }

    /**
     * Get trial balance
     */
    public function trialBalance(Request $request): JsonResponse
    {
        $asOfDate = $request->input('as_of_date', now());

        $accounts = Account::active()->get();

        $trialBalance = $accounts->map(function ($account) use ($asOfDate) {
            $balance = $account->balanceAsOf($asOfDate);

            return [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'account_type' => $account->account_type,
                'debit' => $account->normal_balance === 'debit' && $balance > 0 ? $balance : 0,
                'credit' => $account->normal_balance === 'credit' && $balance > 0 ? $balance : 0,
            ];
        });

        $totalDebits = $trialBalance->sum('debit');
        $totalCredits = $trialBalance->sum('credit');

        return response()->json([
            'success' => true,
            'data' => [
                'as_of_date' => $asOfDate,
                'accounts' => $trialBalance,
                'total_debits' => $totalDebits,
                'total_credits' => $totalCredits,
                'is_balanced' => abs($totalDebits - $totalCredits) < 0.01,
            ],
        ]);
    }
}
