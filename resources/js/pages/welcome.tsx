import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="College Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-900 lg:justify-center lg:p-8 dark:from-gray-900 dark:to-gray-800 dark:text-white">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-6xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-[335px] flex-col lg:max-w-6xl lg:flex-row lg:gap-12">
                        <div className="flex-1 rounded-2xl bg-white p-8 shadow-xl lg:p-12 dark:bg-gray-800 dark:shadow-2xl">
                            <div className="text-center lg:text-left">
                                <div className="mb-6">
                                    <div className="mb-4 inline-flex items-center gap-3 text-4xl lg:text-5xl">
                                        🎓 <span className="font-bold text-blue-600 dark:text-blue-400">EduManage</span>
                                    </div>
                                    <p className="text-xl text-gray-600 dark:text-gray-300 font-medium">
                                        Complete College Management System
                                    </p>
                                </div>

                                <p className="mb-8 text-lg text-gray-600 leading-relaxed dark:text-gray-400">
                                    Streamline your educational institution with our comprehensive management platform. 
                                    Handle student enrollment, financial tracking, and administrative tasks all in one place.
                                </p>

                                <div className="mb-10 grid gap-6 sm:grid-cols-2">
                                    <div className="rounded-lg bg-blue-50 p-6 text-left dark:bg-blue-900/20">
                                        <div className="mb-3 text-2xl">👥</div>
                                        <h3 className="mb-2 font-semibold text-gray-900 dark:text-white">Student Management</h3>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Manage student profiles, programs, and academic records with ease
                                        </p>
                                    </div>
                                    <div className="rounded-lg bg-green-50 p-6 text-left dark:bg-green-900/20">
                                        <div className="mb-3 text-2xl">💰</div>
                                        <h3 className="mb-2 font-semibold text-gray-900 dark:text-white">Financial Tracking</h3>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Handle invoicing, payments, and financial reporting seamlessly
                                        </p>
                                    </div>
                                    <div className="rounded-lg bg-purple-50 p-6 text-left dark:bg-purple-900/20">
                                        <div className="mb-3 text-2xl">📚</div>
                                        <h3 className="mb-2 font-semibold text-gray-900 dark:text-white">Program Management</h3>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Organize academic programs, courses, and curriculum efficiently
                                        </p>
                                    </div>
                                    <div className="rounded-lg bg-orange-50 p-6 text-left dark:bg-orange-900/20">
                                        <div className="mb-3 text-2xl">🔐</div>
                                        <h3 className="mb-2 font-semibold text-gray-900 dark:text-white">Role-Based Access</h3>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Secure access control for administrators, staff, and students
                                        </p>
                                    </div>
                                </div>

                                {!auth.user && (
                                    <div className="flex flex-col gap-4 sm:flex-row sm:justify-center lg:justify-start">
                                        <Link
                                            href={route('register')}
                                            className="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-8 py-3 font-medium text-white hover:bg-blue-700 transition-colors"
                                        >
                                            <span>Get Started</span>
                                            <span>→</span>
                                        </Link>
                                        <Link
                                            href={route('login')}
                                            className="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-8 py-3 font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            Sign In
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                        
                        <div className="mt-8 rounded-2xl bg-gradient-to-br from-blue-600 to-purple-700 p-8 text-white lg:mt-0 lg:max-w-md">
                            <h2 className="mb-6 text-2xl font-bold">System Features</h2>
                            <div className="space-y-4">
                                <div className="flex items-start gap-3">
                                    <div className="mt-1 h-2 w-2 rounded-full bg-white/80"></div>
                                    <div>
                                        <p className="font-medium">Multi-Role Support</p>
                                        <p className="text-sm text-white/80">Super Admin, Admin, Staff & Student roles</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3">
                                    <div className="mt-1 h-2 w-2 rounded-full bg-white/80"></div>
                                    <div>
                                        <p className="font-medium">Invoice Generation</p>
                                        <p className="text-sm text-white/80">Automated billing and payment tracking</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3">
                                    <div className="mt-1 h-2 w-2 rounded-full bg-white/80"></div>
                                    <div>
                                        <p className="font-medium">Payment Integration</p>
                                        <p className="text-sm text-white/80">Multiple payment methods supported</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3">
                                    <div className="mt-1 h-2 w-2 rounded-full bg-white/80"></div>
                                    <div>
                                        <p className="font-medium">Real-time Reports</p>
                                        <p className="text-sm text-white/80">Financial and academic analytics</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3">
                                    <div className="mt-1 h-2 w-2 rounded-full bg-white/80"></div>
                                    <div>
                                        <p className="font-medium">Secure & Scalable</p>
                                        <p className="text-sm text-white/80">Enterprise-grade security features</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    );
}