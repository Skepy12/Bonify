<?php

namespace App\Http\Controllers\Business;

use App\Enums\DayOfWeek;
use App\Enums\ServiceMode;
use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Models\BusinessType;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', BusinessProfile::class);

        $businesses = $request->user()
            ->businesses()
            ->with(['country:id,name'])
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
        $this->authorize('create', BusinessProfile::class);

        return Inertia::render('businesses/Create', [
            'countries' => Country::query()->select('id', 'name')->orderBy('name')->get(),
            'languages' => Language::query()->select('id', 'name')->orderBy('name')->get(),
            'businessTypes' => BusinessType::query()->select('id', 'name')->orderBy('name')->get(),
            'categories' => Category::query()->select('id', 'name', 'business_type_id')->orderBy('name')->get(),
            'serviceModes' => collect(ServiceMode::cases())->map(
                fn (ServiceMode $mode): array => ['value' => $mode->value, 'label' => ucfirst($mode->value)]
            )->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', BusinessProfile::class);

        $validated = $this->validateBusinessProfile($request);

        $business = $request->user()->businesses()->create(
            Arr::except($validated, ['business_hours']),
        );

        $this->syncBusinessHours($business, $validated['business_hours'] ?? []);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business created.')]);

        return to_route('businesses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, BusinessProfile $business): Response
    {
        $this->authorize('view', $business);

        $business->load([
            'country:id,name',
            'language:id,name',
            'businessType:id,name',
            'category:id,name',
            'businessHours',
        ]);

        return Inertia::render('businesses/Show', [
            'business' => $business,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, BusinessProfile $business): Response
    {
        $this->authorize('update', $business);

        $business->load([
            'country:id,name',
            'language:id,name',
            'businessType:id,name',
            'category:id,name',
            'businessHours',
        ]);

        return Inertia::render('businesses/Edit', [
            'business' => $business,
            'countries' => Country::query()->select('id', 'name')->orderBy('name')->get(),
            'languages' => Language::query()->select('id', 'name')->orderBy('name')->get(),
            'businessTypes' => BusinessType::query()->select('id', 'name')->orderBy('name')->get(),
            'categories' => Category::query()->select('id', 'name', 'business_type_id')->orderBy('name')->get(),
            'serviceModes' => collect(ServiceMode::cases())->map(
                fn (ServiceMode $mode): array => ['value' => $mode->value, 'label' => ucfirst($mode->value)]
            )->values(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessProfile $business): RedirectResponse
    {
        $this->authorize('update', $business);

        $validated = $this->validateBusinessProfile($request);

        $business->update(Arr::except($validated, ['business_hours']));
        $this->syncBusinessHours($business, $validated['business_hours'] ?? []);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business updated.')]);

        return to_route('businesses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, BusinessProfile $business): RedirectResponse
    {
        $this->authorize('delete', $business);

        $business->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Business deleted.')]);

        return to_route('businesses.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBusinessProfile(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'service_mode' => ['required', Rule::enum(ServiceMode::class)],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'language_id' => ['nullable', 'integer', 'exists:languages,id'],
            'business_type_id' => ['nullable', 'integer', 'exists:business_types,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'business_hours' => ['nullable', 'array'],
            'business_hours.*.day_of_week' => [
                'required',
                'integer',
                Rule::in(collect(DayOfWeek::cases())->map->value->all()),
            ],
            'business_hours.*.is_closed' => ['nullable', 'boolean'],
            'business_hours.*.is_24_hours' => ['nullable', 'boolean'],
            'business_hours.*.opens_at' => ['nullable', 'date_format:H:i'],
            'business_hours.*.closes_at' => ['nullable', 'date_format:H:i'],
        ]);

        foreach ($validated['business_hours'] ?? [] as $index => $hour) {
            $isClosed = (bool) ($hour['is_closed'] ?? false);
            $is24Hours = (bool) ($hour['is_24_hours'] ?? false);
            $opensAt = $hour['opens_at'] ?? null;
            $closesAt = $hour['closes_at'] ?? null;

            if ($isClosed && $is24Hours) {
                throw ValidationException::withMessages([
                    "business_hours.{$index}.is_24_hours" => 'A day cannot be both closed and open 24 hours.',
                ]);
            }

            if ($isClosed || $is24Hours) {
                continue;
            }

            if (filled($opensAt) xor filled($closesAt)) {
                throw ValidationException::withMessages([
                    "business_hours.{$index}.opens_at" => 'Both opening and closing times are required.',
                ]);
            }

            if (filled($opensAt) && filled($closesAt) && $opensAt >= $closesAt) {
                throw ValidationException::withMessages([
                    "business_hours.{$index}.closes_at" => 'Closing time must be after opening time.',
                ]);
            }
        }

        return $validated;
    }

    /**
     * @param  array<int|string, array<string, mixed>>  $businessHours
     */
    private function syncBusinessHours(BusinessProfile $business, array $businessHours): void
    {
        $business->businessHours()->delete();

        foreach ($businessHours as $hour) {
            $isClosed = (bool) ($hour['is_closed'] ?? false);
            $is24Hours = (bool) ($hour['is_24_hours'] ?? false);
            $opensAt = $hour['opens_at'] ?? null;
            $closesAt = $hour['closes_at'] ?? null;

            if (! $isClosed && ! $is24Hours && blank($opensAt) && blank($closesAt)) {
                continue;
            }

            $business->businessHours()->create([
                'day_of_week' => $hour['day_of_week'],
                'is_closed' => $isClosed,
                'is_24_hours' => $is24Hours,
                'opens_at' => ($isClosed || $is24Hours) ? null : $opensAt,
                'closes_at' => ($isClosed || $is24Hours) ? null : $closesAt,
            ]);
        }
    }
}
