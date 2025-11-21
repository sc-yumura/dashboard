import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type Product } from '@/types';
import { Link } from '@inertiajs/react';
import { show } from "@/routes/products"

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Example',
        href: 'example.com',
    },
];

export default function Index({
    products
}: {
    products: Product[]
}) {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <ul>
                {products.map((product) => (
                    <Link href={show(product.id)}>
                        {product.product_name}
                    </Link>
                    // <li key={product.id}>{product.product_name}</li>
                ))}
            </ul>
        </AppLayout>
    );
}
