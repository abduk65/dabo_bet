<?php

namespace App\Policies;

use App\Models\JournalEntry;
use App\Models\User;

class JournalEntryPolicy
{
    /**
     * Determine if the user can view any journal entries.
     */
    public function viewAny(User $user): bool
    {
        // Only managers and above can view journal entries
        return $user->isManager();
    }

    /**
     * Determine if the user can view the journal entry.
     */
    public function view(User $user, JournalEntry $journalEntry): bool
    {
        // Only managers and above can view journal entries
        return $user->isManager();
    }

    /**
     * Determine if the user can create journal entries.
     */
    public function create(User $user): bool
    {
        // Only managers and above can create journal entries
        return $user->isManager();
    }

    /**
     * Determine if the user can update the journal entry.
     */
    public function update(User $user, JournalEntry $journalEntry): bool
    {
        // Only draft entries can be updated
        // Only by managers and above
        return $journalEntry->status === 'draft' && $user->isManager();
    }

    /**
     * Determine if the user can post the journal entry.
     */
    public function post(User $user, JournalEntry $journalEntry): bool
    {
        // Only owners can post journal entries
        // Only draft entries
        return $journalEntry->status === 'draft' && $user->isOwner();
    }

    /**
     * Determine if the user can reverse the journal entry.
     */
    public function reverse(User $user, JournalEntry $journalEntry): bool
    {
        // Only owners can reverse journal entries
        // Only posted entries
        return $journalEntry->status === 'posted' && $user->isOwner();
    }

    /**
     * Determine if the user can delete the journal entry.
     */
    public function delete(User $user, JournalEntry $journalEntry): bool
    {
        // Only owners can delete
        // Only draft entries
        return $journalEntry->status === 'draft' && $user->isOwner();
    }

    /**
     * Determine if the user can view financial reports.
     */
    public function viewFinancialReports(User $user): bool
    {
        // Only owners can view comprehensive financial reports
        return $user->isOwner();
    }

    /**
     * Determine if the user can export financial data.
     */
    public function exportFinancialData(User $user): bool
    {
        // Only owners can export financial data
        return $user->isOwner();
    }
}
