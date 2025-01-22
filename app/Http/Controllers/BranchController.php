<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\DailyInventoryOut;
use App\Http\Resources\BranchResource;

use App\Models\DailySales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $branches = Branch::all();

        if ($request->wantsJson()) {
            return response()->json($branches);
        }

        return view("branch.index", compact("branches"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("branch.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->only('name', 'type'));
        if ($request->wantsJson()) {
            return response()->json($branch);
        }
        return redirect()->back()->with('success', 'SUCCEED');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        try {
            // Log the update attempt
            Log::info('Updating branch', [
                'branch_id' => $branch->id,
                'data' => $request->validated()
            ]);

            // Using Eloquent's fill and save for better model events handling
            $branch->fill($request->validated());
            $branch->save();

            // Using fresh() to get a fresh instance with updated data
            $updatedBranch = $branch->fresh();

            // Prepare success response
            if ($request->wantsJson()) {
                return BranchResource::make($updatedBranch)
                    ->additional(['message' => 'Branch updated successfully'])
                    ->response()
                    ->setStatusCode(200);
            }

            return redirect()->back()->with('success', 'Branch updated successfully');

        } catch (\Exception $e) {
            Log::error('Branch update failed', [
                'branch_id' => $branch->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to update branch',
                    'errors' => [$e->getMessage()]
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update branch. Please try again.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Branch $branch)
    {
        try {
            // Log the delete attempt
            Log::info('Deleting branch', [
                'branch_id' => $branch->id
            ]);

            // Using Eloquent's delete
            $branch->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Branch deleted successfully'
                ], 200);
            }

            return redirect()->back()->with('success', 'Branch deleted successfully');

        } catch (\Exception $e) {
            Log::error('Branch deletion failed', [
                'branch_id' => $branch->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to delete branch',
                    'errors' => [$e->getMessage()]
                ], 422);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete branch. Please try again.']);
        }
    }
}
