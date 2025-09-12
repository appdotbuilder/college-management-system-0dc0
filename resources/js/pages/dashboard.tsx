import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { type User, type Invoice, type Payment, type Program } from '@/types/college';
import { Head, Link, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    studentData?: {
        invoices: Invoice[];
        recentPayments: Payment[];
        totalOutstanding: number;
        program: Program;
    };
    staffData?: {
        recentInvoices: Invoice[];
        recentPayments: Payment[];
        todayPayments: number;
        pendingInvoices: number;
        overdueInvoices: number;
    };
    adminData?: {
        totalStudents: number;
        totalStaff: number;
        activePrograms: number;
        thisMonthRevenue: number;
        pendingInvoices: number;
        overdueInvoices: number;
        recentStudents: User[];
    };
}

export default function Dashboard({ studentData, staffData, adminData }: DashboardProps) {
    const { auth } = usePage<{ auth: { user: User } }>().props;
    const user = auth.user;

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-SG', {
            style: 'currency',
            currency: 'SGD',
        }).format(amount);
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString('en-SG');
    };

    const getStatusBadge = (status: string) => {
        const statusColors: Record<string, string> = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'paid': 'bg-green-100 text-green-800',
            'overdue': 'bg-red-100 text-red-800',
            'partial': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
        };
        return statusColors[status] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                        Welcome back, {user.name}! 👋
                    </h1>
                    <div className="text-sm text-gray-600 dark:text-gray-400">
                        Role: <span className="capitalize font-medium">{user.role.replace('_', ' ')}</span>
                    </div>
                </div>

                {/* Student Dashboard */}
                {user.role === 'student' && studentData && (
                    <div className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Program</h3>
                                <p className="text-2xl font-bold text-blue-600">{studentData.program?.name || 'Not Assigned'}</p>
                                <p className="text-sm text-gray-600 dark:text-gray-400">Code: {studentData.program?.code}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Outstanding Amount</h3>
                                <p className="text-2xl font-bold text-red-600">{formatCurrency(studentData.totalOutstanding)}</p>
                                <p className="text-sm text-gray-600 dark:text-gray-400">Total unpaid</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Recent Invoices</h3>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{studentData.invoices.length}</p>
                                <Link href={route('invoices.index')} className="text-sm text-blue-600 hover:underline">
                                    View all invoices
                                </Link>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Invoices</h3>
                                <div className="space-y-3">
                                    {studentData.invoices.slice(0, 5).map((invoice) => (
                                        <div key={invoice.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                            <div>
                                                <p className="font-medium">{invoice.invoice_number}</p>
                                                <p className="text-sm text-gray-600 dark:text-gray-400">Due: {formatDate(invoice.due_date)}</p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-bold">{formatCurrency(invoice.total_amount)}</p>
                                                <span className={`px-2 py-1 rounded-full text-xs ${getStatusBadge(invoice.status)}`}>
                                                    {invoice.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <Link href={route('invoices.index')} className="block mt-4 text-center text-blue-600 hover:underline">
                                    View All Invoices
                                </Link>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Payments</h3>
                                <div className="space-y-3">
                                    {studentData.recentPayments.slice(0, 5).map((payment) => (
                                        <div key={payment.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                            <div>
                                                <p className="font-medium">{payment.payment_number}</p>
                                                <p className="text-sm text-gray-600 dark:text-gray-400">
                                                    {formatDate(payment.payment_date)} • {payment.payment_method}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-bold text-green-600">{formatCurrency(payment.amount)}</p>
                                                <span className={`px-2 py-1 rounded-full text-xs ${getStatusBadge(payment.status)}`}>
                                                    {payment.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <Link href={route('payments.index')} className="block mt-4 text-center text-blue-600 hover:underline">
                                    View All Payments
                                </Link>
                            </div>
                        </div>
                    </div>
                )}

                {/* Staff Dashboard */}
                {user.role === 'staff' && staffData && (
                    <div className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Today's Payments</h3>
                                <p className="text-2xl font-bold text-green-600">{formatCurrency(staffData.todayPayments)}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pending Invoices</h3>
                                <p className="text-2xl font-bold text-yellow-600">{staffData.pendingInvoices}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Overdue Invoices</h3>
                                <p className="text-2xl font-bold text-red-600">{staffData.overdueInvoices}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                                <Link href={route('invoices.create')} className="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Create Invoice
                                </Link>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Invoices</h3>
                                <div className="space-y-3">
                                    {staffData.recentInvoices.slice(0, 5).map((invoice) => (
                                        <div key={invoice.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                            <div>
                                                <p className="font-medium">{invoice.invoice_number}</p>
                                                <p className="text-sm text-gray-600 dark:text-gray-400">{invoice.student?.name}</p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-bold">{formatCurrency(invoice.total_amount)}</p>
                                                <span className={`px-2 py-1 rounded-full text-xs ${getStatusBadge(invoice.status)}`}>
                                                    {invoice.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Payments</h3>
                                <div className="space-y-3">
                                    {staffData.recentPayments.slice(0, 5).map((payment) => (
                                        <div key={payment.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                            <div>
                                                <p className="font-medium">{payment.payment_number}</p>
                                                <p className="text-sm text-gray-600 dark:text-gray-400">{payment.student?.name}</p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-bold text-green-600">{formatCurrency(payment.amount)}</p>
                                                <span className={`px-2 py-1 rounded-full text-xs ${getStatusBadge(payment.status)}`}>
                                                    {payment.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Admin Dashboard */}
                {(user.role === 'admin' || user.role === 'super_admin') && adminData && (
                    <div className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Total Students</h3>
                                <p className="text-2xl font-bold text-blue-600">{adminData.totalStudents}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Staff Members</h3>
                                <p className="text-2xl font-bold text-purple-600">{adminData.totalStaff}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Active Programs</h3>
                                <p className="text-2xl font-bold text-green-600">{adminData.activePrograms}</p>
                            </div>
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">This Month Revenue</h3>
                                <p className="text-2xl font-bold text-green-600">{formatCurrency(adminData.thisMonthRevenue)}</p>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">Invoice Status</h3>
                                <div className="space-y-3 mt-4">
                                    <div className="flex justify-between">
                                        <span>Pending Invoices</span>
                                        <span className="font-bold text-yellow-600">{adminData.pendingInvoices}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span>Overdue Invoices</span>
                                        <span className="font-bold text-red-600">{adminData.overdueInvoices}</span>
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                                <div className="grid grid-cols-2 gap-3">
                                    <Link href={route('students.create')} className="bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                                        Add Student
                                    </Link>
                                    <Link href={route('programs.create')} className="bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                                        Add Program
                                    </Link>
                                    <Link href={route('fee-types.create')} className="bg-purple-600 text-white px-4 py-2 rounded text-center hover:bg-purple-700">
                                        Add Fee Type
                                    </Link>
                                    <Link href={route('invoices.create')} className="bg-orange-600 text-white px-4 py-2 rounded text-center hover:bg-orange-700">
                                        Create Invoice
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Students</h3>
                            <div className="space-y-3">
                                {adminData.recentStudents.map((student) => (
                                    <div key={student.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <div>
                                            <p className="font-medium">{student.name}</p>
                                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                                {student.program?.name} • {student.nric_no}
                                            </p>
                                        </div>
                                        <div className="text-sm text-gray-600 dark:text-gray-400">
                                            Enrolled: {student.intake_date && formatDate(student.intake_date)}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}