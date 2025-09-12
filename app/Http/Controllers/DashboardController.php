<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Program;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isStaff()) {
            return $this->staffDashboard($user);
        } elseif ($user->hasAdminAccess()) {
            return $this->adminDashboard($user);
        }
        
        return Inertia::render('dashboard');
    }

    /**
     * Student dashboard with their invoices and payments.
     */
    protected function studentDashboard(User $user)
    {
        $invoices = $user->invoices()
            ->with(['items.feeType', 'payments'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = $user->payments()
            ->with('invoice')
            ->latest()
            ->take(5)
            ->get();

        $totalOutstanding = $user->invoices()
            ->where('status', '!=', 'paid')
            ->sum('total_amount') - $user->invoices()
            ->where('status', '!=', 'paid')
            ->sum('paid_amount');

        return Inertia::render('dashboard', [
            'studentData' => [
                'invoices' => $invoices,
                'recentPayments' => $recentPayments,
                'totalOutstanding' => $totalOutstanding,
                'program' => $user->program,
            ]
        ]);
    }

    /**
     * Staff dashboard with invoice and payment statistics.
     */
    protected function staffDashboard(User $user)
    {
        $recentInvoices = Invoice::with(['student', 'items'])
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with(['student', 'invoice'])
            ->latest()
            ->take(10)
            ->get();

        $todayPayments = Payment::whereDate('created_at', today())
            ->sum('amount');

        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        return Inertia::render('dashboard', [
            'staffData' => [
                'recentInvoices' => $recentInvoices,
                'recentPayments' => $recentPayments,
                'todayPayments' => $todayPayments,
                'pendingInvoices' => $pendingInvoices,
                'overdueInvoices' => $overdueInvoices,
            ]
        ]);
    }

    /**
     * Admin dashboard with system-wide statistics.
     */
    protected function adminDashboard(User $user)
    {
        $totalStudents = User::students()->count();
        $totalStaff = User::staff()->count();
        $activePrograms = Program::active()->count();
        
        $thisMonthRevenue = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        $recentStudents = User::students()
            ->with('program')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('dashboard', [
            'adminData' => [
                'totalStudents' => $totalStudents,
                'totalStaff' => $totalStaff,
                'activePrograms' => $activePrograms,
                'thisMonthRevenue' => $thisMonthRevenue,
                'pendingInvoices' => $pendingInvoices,
                'overdueInvoices' => $overdueInvoices,
                'recentStudents' => $recentStudents,
            ]
        ]);
    }
}