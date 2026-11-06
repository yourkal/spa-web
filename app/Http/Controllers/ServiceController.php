<?php

namespace App\Http\Controllers;

use App\Models\SpaService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search   = $request->get('search');

        $services = SpaService::where('is_active', true)
            ->when($category && $category !== 'Semua', fn($q) => $q->where('category', $category))
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->get();

        $categories = ['Semua', 'Massage', 'Aromatherapy', 'Facial', 'Body Treatment', 'Couple Treatment', 'Paket'];
        return view('customer.services', compact('services', 'categories', 'category'));
    }

    public function show(SpaService $spaService)
    {
        return view('customer.service-detail', compact('spaService'));
    }
}
