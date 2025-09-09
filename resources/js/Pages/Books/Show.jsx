import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, Link, usePage } from '@inertiajs/react';
import SectionTree from '../../Shared/SectionTree';

export default function Show({ book, tree, is_author, collaborators }) {
    const buildOptions = (nodes, prefix = '') =>
        nodes.flatMap(n => ([
            { value: n.id, label: n.title },
            ...buildOptions(n.children ?? [], `${prefix} `)
        ]));

    const parentOptions = [{ value: '', label: '— None (top level) —' }, ...buildOptions(tree)];
    const { flash } = usePage().props;
    const form = useForm({ parent_id: null, title: '', content: '' });
    const invite = useForm({ email: '' });
    const { delete: destroy } = useForm();

    const submit = (e) => {
        e.preventDefault();
        form.post(route('sections.store', book.id), { preserveScroll: true, onSuccess: () => form.reset() });
    };

    const removeCollaborator = (userId) => {
        if (!confirm('Remove this collaborator?')) return;
        destroy(route('books.members.destroy', [book.id, userId]), {
            preserveScroll: true,
        });
    };


    return (
        <AuthenticatedLayout>
            <Head title={book.title} />
            <div className="max-w-5xl mx-auto p-6">

                <div className="flex items-center justify-between mb-4">
                    <h1 className="text-2xl font-semibold mb-4">{book.title}</h1>
                    <Link className="text-blue-600 hover:underline text-sm" href={route('books.index')} >
                        ← Back
                    </Link>
                </div>
                <div className="grid md:grid-cols-2 gap-6">
                    {flash?.error && (
                        <div className="mb-3 rounded border border-red-300 bg-red-50 p-2 text-red-700">
                            {flash.error}
                        </div>
                    )}
                    {flash?.success && (
                        <div className="mb-3 rounded border border-green-300 bg-green-50 p-2 text-green-700">
                            {flash.success}
                        </div>
                    )}
                    <div className="border rounded p-4">
                        <h2 className="font-medium mb-2">Add Section/Subsection</h2>
                        <form onSubmit={submit} className="space-y-2">
                            <input className="border p-2 w-full" placeholder="Title"
                                value={form.data.title} onChange={e => form.setData('title', e.target.value)} />
                            <textarea className="border p-2 w-full" placeholder="Content"
                                value={form.data.content || ''} onChange={e => form.setData('content', e.target.value)} />
                            {/* <input className="border p-2 w-full" placeholder="Parent Section ID (optional)"
                                value={form.data.parent_id || ''} onChange={e => form.setData('parent_id', e.target.value || null)} /> */}
                            <select
                                className="border p-2 w-full"
                                value={form.data.parent_id ?? ''}
                                onChange={e => form.setData('parent_id', e.target.value || null)}
                            >
                                {parentOptions.map(o => (
                                    <option key={o.value || 'none'} value={o.value}>{o.label}</option>
                                ))}
                            </select>

                            <button className="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                        </form>
                        {is_author && (
                            <form onSubmit={e => { e.preventDefault(); invite.post(route('books.members.store', book.id), { preserveScroll: true, onSuccess: () => invite.reset() }); }}
                                className="space-y-2 mt-6">
                                <input className="border p-2 w-full" placeholder="user@email.com"
                                    value={invite.data.email} onChange={e => invite.setData('email', e.target.value)} />
                                <button className="bg-blue-600 text-white px-3 py-1 rounded">Invite</button>
                            </form>
                        )}
                        {is_author && (
                            <div className="mb-6 border rounded p-4">
                                <h2 className="font-medium mb-2">Collaborators</h2>
                                {collaborators.length === 0 ? (
                                    <div className="text-sm text-gray-500">No collaborators yet.</div>
                                ) : (
                                    <ul className="divide-y">
                                        {collaborators.map(c => (
                                            <li key={c.id} className="flex items-center justify-between py-2">
                                                <div>
                                                    <div className="font-medium">{c.name}</div>
                                                    {c.name && <div className="text-sm text-gray-500">{c.email}</div>}
                                                </div>
                                                <button
                                                    className="bg-red-600 text-white px-3 py-1 rounded"
                                                    onClick={() => removeCollaborator(c.id)}
                                                >
                                                    Remove
                                                </button>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>
                        )}
                    </div>

                    <div className="border rounded p-4">
                        <h2 className="font-medium mb-2">Outline (infinite nesting)</h2>
                        <SectionTree nodes={tree} bookId={book.id} />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}