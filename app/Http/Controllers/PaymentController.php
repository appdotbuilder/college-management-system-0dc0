<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payments = Payment::with(['student', 'invoice', 'processor'])
            ->when($request->search, function ($query, $search) {
                $query->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->method, function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->latest()
            ->paginate(15);

        return Inertia::render('payments/index', [
            'payments' => $payments,
            'filters' => $request->only(['search', 'status', 'method']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $invoice = null;
        if ($request->invoice_id) {
            $invoice = Invoice::with(['student', 'items.feeType'])->findOrFail($request->invoice_id);
        }

        $unpaidInvoices = Invoice::with('student')
            ->where('status', '!=', 'paid')
            ->latest()
            ->get();

        return Inertia::render('payments/create', [
            'invoice' => $invoice,
            'unpaidInvoices' => $unpaidInvoices,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['payment_number'] = $this->generatePaymentNumber();
        $data['processed_by'] = auth()->id();

        $payment = Payment::create($data);

        // Update invoice paid amount and status
        $invoice = $payment->invoice;
        $invoice->paid_amount += $payment->amount;
        $invoice->updateStatus();

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'invoice.items.feeType', 'processor']);

        return Inertia::render('payments/show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Update invoice paid amount
        $invoice = $payment->invoice;
        $invoice->paid_amount -= $payment->amount;
        $invoice->updateStatus();

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Generate unique payment number.
     */
    protected function generatePaymentNumber(): string
    {
        do {
            $number = 'PAY-' . date('Y') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Payment::where('payment_number', $number)->exists());

        return $number;
    }
}