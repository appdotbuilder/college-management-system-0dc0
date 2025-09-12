<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = User::students()
            ->with('program')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nric_no', 'like', "%{$search}%");
            })
            ->when($request->program, function ($query, $program) {
                $query->where('program_id', $program);
            })
            ->latest()
            ->paginate(15);

        $programs = Program::active()->get();

        return Inertia::render('students/index', [
            'students' => $students,
            'programs' => $programs,
            'filters' => $request->only(['search', 'program']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::active()->get();

        return Inertia::render('students/create', [
            'programs' => $programs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'student';
        $data['email_verified_at'] = now();

        $student = User::create($data);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $student->load(['program', 'invoices.items.feeType', 'payments.invoice']);

        $totalOutstanding = $student->invoices()
            ->where('status', '!=', 'paid')
            ->sum('total_amount') - $student->invoices()
            ->where('status', '!=', 'paid')
            ->sum('paid_amount');

        return Inertia::render('students/show', [
            'student' => $student,
            'totalOutstanding' => $totalOutstanding,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $programs = Program::active()->get();

        return Inertia::render('students/edit', [
            'student' => $student,
            'programs' => $programs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $data = $request->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $student->update($data);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}