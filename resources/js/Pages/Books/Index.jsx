import { Head, useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ books }) {
    const { data, setData, post, processing, reset } = useForm({ title: '', description: '' });

    return (
        <AuthenticatedLayout>
            <Head title="My Books" />
            <div className="max-w-3xl mx-auto p-6">
                <h1 className="text-2xl font-semibold mb-4">Books</h1>

                <form onSubmit={e => { e.preventDefault(); post(route('books.store'), { onSuccess: () => reset() }); }}
                    className="mb-6 space-y-2">
                    <input className="border p-2 w-full" placeholder="Title" value={data.title}
                        onChange={e => setData('title', e.target.value)} />
                    <textarea className="border p-2 w-full" placeholder="Description"
                        value={data.description || ''} onChange={e => setData('description', e.target.value)} />
                    <button disabled={processing} className="bg-blue-600 text-white px-3 py-1 rounded">Create</button>
                </form>

                <ul className="space-y-2">
                    {books.map(b => (
                        <li key={b.id} className="border p-3 rounded flex justify-between">
                            <div>
                                <div className="font-medium">{b.title}</div>
                                <div className="text-sm text-gray-500">{b.description}</div>
                            </div>
                            <Link className="text-blue-600" href={route('books.show', b.id)}>Open</Link>
                        </li>
                    ))}
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}