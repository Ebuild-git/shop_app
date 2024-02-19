<tbody class="table-border-bottom-0">
    @forelse ($liste as $item)
    <tr>
      <td>
        <img src="{{ Storage::url($item->icon) }}" alt="{{ $item->icon }}" style="height: 30px !important">
      </td>
        <td >
          <span class="fw-medium">
            {{ $item->titre }}
          </span>
        </td>
        <td >
            {{ $item->description}}
        </td>
        <td>
            {{ $item->getPost->count() }}
        </td>
        <td><span class="badge bg-label-primary me-1">
        {{ $item->created_at}}    
        </span></td>
        <td>
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="ti ti-dots-vertical"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#modalToggle-{{ $item->id }}" href="javascript:void(0);"
                ><i class="ti ti-pencil me-1"></i> Modifier</a
              >
              <a class="dropdown-item" href="javascript:void(0)" wire:click="delete( {{$item->id}})">
                <i class="ti ti-trash me-1"></i> Supprimer </a
              >
            </div>
          </div>
        </td>
      </tr>
      @include('Admin.categories.modal-update', ['item'=>$item] )
    @empty
    <tr>
        <td colspan="6">
            No Data Found!
        </td>
    </tr>
    @endforelse
  </tbody>