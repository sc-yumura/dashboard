import { type BreadcrumbItem, type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type Product } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Example',
        href: 'example.com',
    },
];

export default function Show({
    product
}: {
    product: Product
}) {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <h1>{product.product_name}</h1>
            <p>{product.description}</p>
            <p>ISBN: {product.isbn}</p>
        </AppLayout>
    );
}
