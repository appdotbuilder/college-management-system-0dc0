<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeeTypeRequest;
use App\Http\Requests\UpdateFeeTypeRequest;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $feeTypes = FeeType::withCount('invoiceItems')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status !== null, function ($query) use ($request) {
                $query->where('is_active', $request->boolean('status'));
            })
            ->latest()
            ->paginate(15);

        return Inertia::render('fee-types/index', [
            'feeTypes' => $feeTypes,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('fee-types/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTypeRequest $request)
    {
        $feeType = FeeType::create($request->validated());

        return redirect()->route('fee-types.show', $feeType)
            ->with('success', 'Fee type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeType $feeType)
    {
        return Inertia::render('fee-types/show', [
            'feeType' => $feeType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeType $feeType)
    {
        return Inertia::render('fee-types/edit', [
            'feeType' => $feeType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeTypeRequest $request, FeeType $feeType)
    {
        $feeType->update($request->validated());

        return redirect()->route('fee-types.show', $feeType)
            ->with('success', 'Fee type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeType $feeType)
    {
        $feeType->delete();

        return redirect()->route('fee-types.index')
            ->with('success', 'Fee type deleted successfully.');
    }
}