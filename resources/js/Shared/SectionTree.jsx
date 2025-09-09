import { useForm } from '@inertiajs/react';

const Node = ({ n, bookId }) => {
  const { data, setData, patch, delete:destroy } = useForm({ title: n.title, content: n.content });

  const save = (e) => { e.preventDefault(); patch(route('sections.update', [bookId, n.id]), { preserveScroll: true }); };
  const remove = () => { if (confirm('Delete section?')) destroy(route('sections.destroy', [bookId, n.id])); };

  return (
    <li className="mb-2">
      <details open>
        <summary className="cursor-pointer font-medium">{n.title}</summary>
        <form onSubmit={save} className="space-y-2 ml-4 mt-2">
          <input className="border p-1 w-full" value={data.title} onChange={e=>setData('title', e.target.value)} />
          <textarea className="border p-1 w-full" value={data.content||''} onChange={e=>setData('content', e.target.value)} />
          <div className="flex gap-2">
            <button className="bg-green-600 text-white px-2 py-0.5 rounded">Update</button>
            <button type="button" onClick={remove} className="bg-red-600 text-white px-2 py-0.5 rounded">Delete</button>
          </div>
        </form>
        {n.children?.length > 0 && (
          <ul className="ml-6 list-disc">
            {n.children.map(c => <Node key={c.id} n={c} bookId={bookId} />)}
          </ul>
        )}
      </details>
    </li>
  );
};

export default function SectionTree({ nodes, bookId }) {
  return (
    <ul className="list-disc">
      {nodes.map(n => <Node key={n.id} n={n} bookId={bookId} />)}
    </ul>
  );
}