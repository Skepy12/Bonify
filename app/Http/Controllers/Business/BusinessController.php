<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Business::class);

        $businesses = $request->user()
            ->businesses()
            ->latest()
            ->paginate(10);

        return Inertia::render('businesses/Index', [
            'businesses' => $businesses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Business::class);

        return Inertia::render('businesses/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Business::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $request->user()->businesses()->create($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business created.')]);

        return to_route('businesses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Business $business): Response
    {
        $this->authorize('view', $business);

        return Inertia::render('businesses/Show', [
            'business' => $business,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Business $business): Response
    {
        $this->authorize('update', $business);

        return Inertia::render('businesses/Edit', [
            'business' => $business,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $this->authorize('update', $business);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $business->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business updated.')]);

        return to_route('businesses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Business $business): RedirectResponse
    {
        $this->authorize('delete', $business);

        $business->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business deleted.')]);

        return to_route('businesses.index');
    }
}
