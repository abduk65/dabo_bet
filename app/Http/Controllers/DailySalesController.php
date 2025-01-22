<?php

namespace App\Http\Controllers;

use App\Models\CashCollected;
use App\Models\DailyCommission;
use App\Models\DailyInventoryOut;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Commission;
use App\Models\DailySales;
use App\Http\Requests\StoreDailySalesRequest;
use App\Http\Requests\UpdateDailySalesRequest;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DailySalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dailySales = DailySales::with('product', 'branch', 'commission')->limit(80)->get();


        if ($request->wantsJson()) {
            return response()->json($dailySales);
        }

        return view('dailySales.index');
    }

    public function profitReport(Request $request)
    {
        $dailySales = DailySales::with('product', 'branch', 'commission')->limit(40)->get();
        $cost = DailyInventoryOut::with('inventoryItem', 'unit')->limit(40)->get();
        return response()->json(compact('dailySales', 'cost'));
    }

    public function createDailySales(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $smtj = collect(Arr::where($data, function ($value, $key) {
            return is_int($key);
        }));
        Log::info($smtj);
        $smtj->map(function ($item, $key) {
            $expenses = new Expense();
            $cashCollected = new CashCollected();
            $branch = $item['branch'];
            Arr::map($item['salesData'], function ($item, $key) use($branch) {
                $dailySales = new DailySales();
                $dailySales->branch_id = $branch['id'];
                $dailySales->product_id = $item['id'];
                $dailySales->quantity = $item['quantity'];
                $dailySales->adari = $item['carryover'];
                $dailySales->save();

                $commissionQuantities = explode(',', $item['commissionQuantityCSV']); // Split CSV into array

                // Ensure that the quantities match the commissions selected
                foreach ($item['selectedCommission'] as $index => $commission) {
                    if (isset($commissionQuantities[$index])) { // Check if quantity exists for this commission
                        $dailyCommission = new DailyCommission();
                        $dailyCommission->daily_sales_id = $dailySales->id; // Associate with the DailySales ID
                        $dailyCommission->commission_recipient_id = $commission['id']; // Associate with the selected commission ID
                        $dailyCommission->quantity = (int)$commissionQuantities[$index]; // Cast to int if necessary
                        $dailyCommission->save(); // Save the commission record
                    }
                }
            });
//            $expenses->branch_id = $branch->id;
//            $cashCollected->branch_id = $branch->id;
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $commissions = Commission::all();
        return view("dailySales.create", compact('products', 'commissions'));
    }


    function getLargestVueProjects($page = 1, $perPage = 10)
    {
        // GitHub API URL to search repositories
//        $url = "https://api.github.com/search/repositories?q=language:Vue&sort=size&order=desc&page={$page}&per_page={$perPage}";
        $url = "https://api.github.com/search/repositories?q=Vue+stars:>100+forks:>50&sort=stars&order=desc&page={$page}&per_page={$perPage}";

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub API requires a user agent

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
            return [];
        }

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Check if there are items in the response
        if (isset($data['items'])) {
            return $data['items'];
        }

        return [];
    }

    public function getGh(Request $request) {
        // Fetch the largest Vue.js projects
        $projects = $this->getLargestVueProjects();

        if (!empty($projects))
        {
            echo "Largest Vue.js Projects on GitHub:\n";
            foreach ($projects as $project)
            {
                echo "Name: " . $project['name'] . "\n";
                echo "URL: " . $project['html_url'] . "\n";
                echo "Stars: " . $project['stargazers_count'] . "\n";
                echo "Forks: " . $project['forks_count'] . "\n";
                echo "Size: " . $project['size'] . " KB\n"; // Size in kilobytes
                echo "Description: " . ($project['description'] ?? 'No description') . "\n\n";
            }
        } else {
            echo "No Vue.js projects found.\n";
        }
    }

/**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailySalesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailySales $dailySales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailySales $dailySales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailySalesRequest $request, DailySales $dailySales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailySales $dailySales)
    {
        //
    }
}
