<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\User;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with(['student', 'creator'])
            ->when($request->search, function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->student, function ($query, $student) {
                $query->where('student_id', $student);
            })
            ->latest()
            ->paginate(15);

        $students = User::students()->get();

        return Inertia::render('invoices/index', [
            'invoices' => $invoices,
            'students' => $students,
            'filters' => $request->only(['search', 'status', 'student']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::students()->with('program')->get();
        $feeTypes = FeeType::active()->get();

        return Inertia::render('invoices/create', [
            'students' => $students,
            'feeTypes' => $feeTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['invoice_number'] = $this->generateInvoiceNumber();

        $invoice = Invoice::create($data);

        // Create invoice items
        foreach ($data['items'] as $item) {
            $invoice->items()->create($item);
        }

        // Update total amount
        $totalAmount = $invoice->items()->sum('total');
        $invoice->update(['total_amount' => $totalAmount]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['student.program', 'creator', 'items.feeType', 'payments']);

        return Inertia::render('invoices/show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('items.feeType');
        $students = User::students()->with('program')->get();
        $feeTypes = FeeType::active()->get();

        return Inertia::render('invoices/edit', [
            'invoice' => $invoice,
            'students' => $students,
            'feeTypes' => $feeTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $data = $request->validated();
        
        $invoice->update($data);

        // Update invoice items
        $invoice->items()->delete();
        foreach ($data['items'] as $item) {
            $invoice->items()->create($item);
        }

        // Update total amount
        $totalAmount = $invoice->items()->sum('total');
        $invoice->update(['total_amount' => $totalAmount]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Generate unique invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . date('Y') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Invoice::where('invoice_number', $number)->exists());

        return $number;
    }
}