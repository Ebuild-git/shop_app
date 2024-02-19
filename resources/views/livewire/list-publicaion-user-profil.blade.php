<table class="datatables-projects table border-top">
    <thead>
        <tr>
            <th></th>
            <th>titre</th>
            <th>Date</th>
            <th>Prix</th>
            <th>Action</th>
        </tr>
    </thead>
    @forelse ($posts as $post)
        <tr>
            <td></td>
            <td> {{ $post->titre }} </td>
            <td>{{ $post->created_at }}</td>
            <td>{{ $post->prix }}</td>
            <td>
                <button class="btn btn-sm btn-secondary"
                onclick="document.location.href='/admin/publication/{{ $post->id }}/view'">
                <i class="bi bi-eye"></i> &nbsp; Voir
            </button>
            </td>
        </tr>
    @empty
    <tr>
        <td colspan="6" class="p-3 text-center">
            Aucune publication pour le moment.
        </td>
    </tr>
        
    @endforelse
</table>