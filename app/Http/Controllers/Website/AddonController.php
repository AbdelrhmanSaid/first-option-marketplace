<?php

namespace App\Http\Controllers\Website;

use App\Enums\OS;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Category;
use App\Models\Discipline;
use App\Models\Software;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Addon::query()
            ->with(['publisher', 'category', 'discipline', 'software', 'versions'])
            ->where('is_active', true);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereJsonContains('tags', $search);
            });
        }

        // App Type filters
        if ($request->filled('app_type')) {
            $appTypes = $request->get('app_type');
            if (is_array($appTypes)) {
                $query->where(function ($q) use ($appTypes) {
                    foreach ($appTypes as $type) {
                        switch ($type) {
                            case 'free':
                                $q->orWhereNull('price')->orWhere('price', 0);
                                break;
                            case 'trial':
                                $q->orWhere('trial_period', '>', 0);
                                break;
                            case 'paid':
                                $q->orWhere('price', '>', 0);
                                break;
                        }
                    }
                });
            }
        }

        // OS filters
        if ($request->filled('os')) {
            $query->whereIn('os', $request->get('os'));
        }

        // Software filters
        if ($request->filled('software')) {
            $query->whereIn('software_id', $request->get('software'));
        }

        // Category filters
        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->get('category'));
        }

        // Discipline filters
        if ($request->filled('discipline')) {
            $query->whereIn('discipline_id', $request->get('discipline'));
        }

        // Price range filters
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->get('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->get('price_max'));
        }

        // Sorting
        $sort = $request->get('sort', 'most_relevant');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(price, 0) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(price, 0) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'most_relevant':
            default:
                if ($request->filled('search')) {
                    // For search, order by relevance (exact matches first)
                    $search = $request->get('search');
                    $query->orderByRaw('CASE WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END', [$search, "%{$search}%"]);
                } else {
                    // Default: newest first
                    $query->orderBy('created_at', 'desc');
                }
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 24);
        $addons = $query->paginate($perPage)->withQueryString();

        // Get filter data
        $categories = Category::whereHas('addons')->orderBy('name')->get();
        $disciplines = Discipline::whereHas('addons')->orderBy('name')->get();
        $software = Software::whereHas('addons')->orderBy('name')->get();
        $osOptions = OS::toArray();

        return view('website.addons.index', [
            'addons' => $addons,
            'categories' => $categories,
            'disciplines' => $disciplines,
            'software' => $software,
            'osOptions' => $osOptions,
            'currentFilters' => $request->all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Addon $addon)
    {
        $addon->load(['publisher', 'rates.user']);

        return view('website.addons.show', [
            'addon' => $addon,
        ]);
    }
}
