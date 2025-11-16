<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of journal entries
     */
    public function index(Request $request): JsonResponse
    {
        $query = JournalEntry::with(['createdBy', 'postedBy']);

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'draft') {
                $query->draft();
            } elseif ($request->status === 'posted') {
                $query->posted();
            }
        }

        // Filter by type
        if ($request->has('entry_type')) {
            $query->ofType($request->entry_type);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('entry_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('entry_date', '<=', $request->to_date);
        }

        $entries = $query->orderBy('entry_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $entries,
        ]);
    }

    /**
     * Store a newly created journal entry
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'entry_type' => 'required|in:purchase,production,dispatch,sale,payment,expense,depreciation,adjustment,opening_balance',
            'description' => 'required|string',
            'reference_type' => 'nullable|string|max:50',
            'reference_id' => 'nullable|integer',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit_amount' => 'required|numeric|min:0',
            'lines.*.credit_amount' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);

        // Validate that each line has either debit or credit (not both)
        foreach ($validated['lines'] as $line) {
            if (($line['debit_amount'] > 0 && $line['credit_amount'] > 0) ||
                ($line['debit_amount'] == 0 && $line['credit_amount'] == 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Each line must have either debit or credit amount, not both or neither',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $entry = JournalEntry::create([
                'entry_date' => $validated['entry_date'],
                'entry_type' => $validated['entry_type'],
                'description' => $validated['description'],
                'reference_type' => $validated['reference_type'] ?? null,
                'reference_id' => $validated['reference_id'] ?? null,
                'status' => 'draft',
                'created_by_user_id' => auth()->id(),
            ]);

            foreach ($validated['lines'] as $line) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit_amount' => $line['debit_amount'],
                    'credit_amount' => $line['credit_amount'],
                    'description' => $line['description'] ?? null,
                ]);
            }

            $entry->recalculateTotals();
            $entry->load(['lines.account', 'createdBy']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Journal entry created successfully',
                'data' => $entry,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create journal entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified journal entry
     */
    public function show(JournalEntry $journalEntry): JsonResponse
    {
        $journalEntry->load([
            'lines.account',
            'createdBy',
            'postedBy'
        ]);

        return response()->json([
            'success' => true,
            'data' => $journalEntry,
        ]);
    }

    /**
     * Post a journal entry
     */
    public function post(JournalEntry $journalEntry): JsonResponse
    {
        if ($journalEntry->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft journal entries can be posted',
            ], 422);
        }

        if (!$journalEntry->isBalanced()) {
            return response()->json([
                'success' => false,
                'message' => 'Journal entry is not balanced. Debits must equal credits.',
            ], 422);
        }

        try {
            $journalEntry->post(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Journal entry posted successfully',
                'data' => $journalEntry->fresh(['lines.account', 'postedBy']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to post journal entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reverse a journal entry
     */
    public function reverse(Request $request, JournalEntry $journalEntry): JsonResponse
    {
        if ($journalEntry->status !== 'posted') {
            return response()->json([
                'success' => false,
                'message' => 'Only posted journal entries can be reversed',
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $reversingEntry = $journalEntry->reverse(auth()->id(), $validated['reason']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Journal entry reversed successfully',
                'data' => [
                    'original_entry' => $journalEntry->fresh(),
                    'reversing_entry' => $reversingEntry,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reverse journal entry: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get draft entries
     */
    public function drafts(): JsonResponse
    {
        $entries = JournalEntry::with(['createdBy', 'lines.account'])
            ->draft()
            ->orderBy('entry_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entries,
        ]);
    }

    /**
     * Get entries by type
     */
    public function byType(string $type): JsonResponse
    {
        $entries = JournalEntry::with(['createdBy', 'postedBy'])
            ->ofType($type)
            ->posted()
            ->orderBy('entry_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entries,
        ]);
    }
}
