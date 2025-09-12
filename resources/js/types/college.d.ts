export interface User {
    id: number;
    name: string;
    email: string;
    role: 'super_admin' | 'admin' | 'staff' | 'student';
    nric_no?: string;
    intake_date?: string;
    program_id?: number;
    program?: Program;
    created_at: string;
    updated_at: string;
}

export interface Program {
    id: number;
    name: string;
    code: string;
    description?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface FeeType {
    id: number;
    name: string;
    description?: string;
    default_amount?: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface Invoice {
    id: number;
    invoice_number: string;
    student_id: number;
    created_by: number;
    issue_date: string;
    due_date: string;
    total_amount: number;
    paid_amount: number;
    status: 'pending' | 'partial' | 'paid' | 'overdue';
    notes?: string;
    student?: User;
    creator?: User;
    items?: InvoiceItem[];
    payments?: Payment[];
    created_at: string;
    updated_at: string;
}

export interface InvoiceItem {
    id: number;
    invoice_id: number;
    fee_type_id: number;
    description: string;
    amount: number;
    quantity: number;
    total: number;
    fee_type?: FeeType;
    created_at: string;
    updated_at: string;
}

export interface Payment {
    id: number;
    payment_number: string;
    invoice_id: number;
    student_id: number;
    processed_by?: number;
    amount: number;
    payment_method: 'cash' | 'bank_transfer' | 'credit_card' | 'online';
    reference_number?: string;
    payment_date: string;
    status: 'pending' | 'completed' | 'failed' | 'refunded';
    notes?: string;
    invoice?: Invoice;
    student?: User;
    processor?: User;
    created_at: string;
    updated_at: string;
}

export interface PaginatedData<T> {
    data: T[];
    links: PaginationLink[];
    meta: PaginationMeta;
}

export interface PaginationLink {
    url?: string;
    label: string;
    active: boolean;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
}