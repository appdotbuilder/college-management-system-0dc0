import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { 
    BookOpen, 
    Folder, 
    LayoutGrid, 
    Users, 
    GraduationCap, 
    DollarSign, 
    FileText, 
    CreditCard,
    Settings
} from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth.user;

    const getMainNavItems = (): NavItem[] => {
        const baseItems: NavItem[] = [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ];

        if (!user) return baseItems;

        // Student navigation
        if (user.role === 'student') {
            return [
                ...baseItems,
                {
                    title: 'My Invoices',
                    href: '/invoices',
                    icon: FileText,
                },
                {
                    title: 'My Payments',
                    href: '/payments',
                    icon: CreditCard,
                },
            ];
        }

        // Staff navigation
        if (user.role === 'staff') {
            return [
                ...baseItems,
                {
                    title: 'Students',
                    href: '/students',
                    icon: Users,
                },
                {
                    title: 'Invoices',
                    href: '/invoices',
                    icon: FileText,
                },
                {
                    title: 'Payments',
                    href: '/payments',
                    icon: CreditCard,
                },
            ];
        }

        // Admin and Super Admin navigation
        if (user.role === 'admin' || user.role === 'super_admin') {
            const adminItems: NavItem[] = [
                ...baseItems,
                {
                    title: 'Students',
                    href: '/students',
                    icon: Users,
                },
                {
                    title: 'Programs',
                    href: '/programs',
                    icon: GraduationCap,
                },
                {
                    title: 'Fee Types',
                    href: '/fee-types',
                    icon: DollarSign,
                },
                {
                    title: 'Invoices',
                    href: '/invoices',
                    icon: FileText,
                },
                {
                    title: 'Payments',
                    href: '/payments',
                    icon: CreditCard,
                },
            ];

            // Super Admin gets additional settings access
            if (user.role === 'super_admin') {
                adminItems.push({
                    title: 'Settings',
                    href: '/settings',
                    icon: Settings,
                });
            }

            return adminItems;
        }

        return baseItems;
    };

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={getMainNavItems()} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}