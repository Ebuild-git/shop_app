 <!-- Ajax Sourced Server-side -->


 <div class="card">
     <div class="d-flex justify-content-between">
         <h5 class="card-header">
             Liste des publications
             @if ($deleted)
                 <span class="text-danger">
                     (Supprimés)
                 </span>
             @endif
             <span wire:loading>
                 <x-loading></x-loading>
             </span>
         </h5>
         <a href="{{ route('liste_publications_supprimer') }}" class="btn text-danger">
             <b>
                 <i class="bi bi-trash3"></i>
                 Corbeille ( {{ $trashCount }} )
             </b>
         </a>
     </div>
     <div class="row p-2">
         <div class="col-sm-12 my-auto">
             <form wire:submit="filtre">
                 <div class="input-group mb-3">
                     <input type="text" class="form-control" wire:model="mot_key"
                         placeholder="Titre,Auteur,Description">
                     <select wire:model ="type" class="form-control">
                         <option value="" selected>Toutes les publications</option>
                         <option value="validation">En cour de validation</option>
                         <option value="vente">En vente</option>
                         <option value="vendu">vendu</option>
                         <option value="livraison">en cour de livraison</option>
                         <option value="livré">livré</option>
                     </select>
                     <select wire:model ="region_key" class="form-control">
                         <option value="" selected>Toutes les regions</option>
                         @foreach ($regions as $item)
                             <option value="{{ $item->id }}">{{ $item->nom }}</option>
                         @endforeach
                     </select>
                     <select wire:model ="categorie_key" class="form-control">
                         <option value="" selected>Toutes les catégories</option>
                         @foreach ($categories as $item)
                             <option value="{{ $item->id }}">
                                 {{ $item->titre }}
                                 {{ $item->luxury == true ? '(Luxury)' : '' }}
                             </option>
                         @endforeach
                     </select>
                     <select wire:model="signalement" class="form-control">
                         <option value="">Signalements</option>
                         <option value="Asc">Plus signaler au moins</option>
                         <option value="Des">Moins signaler au plus</option>
                     </select>
                     <input type="month" name="date" wire:model="date" class="form-control" id="">
                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>
                 </div>
             </form>
         </div>
     </div>
     <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Scrollable -->
        <div class="card">
          <h5 class="card-header">Scrollable Table</h5>
          <div class="card-datatable text-nowrap">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"><div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0"></label></div></div></div><div class="dataTables_scroll"><div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;"><div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 933.25px; padding-right: 0px;"><table class="dt-scrollableTable table dataTable no-footer" style="margin-left: 0px; width: 933.25px;"><thead>
                <tr><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 39.7375px;" aria-label="Name: activate to sort column ascending">Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 63.2125px;" aria-label="Position: activate to sort column ascending">Position</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 41.3875px;" aria-label="Email: activate to sort column ascending">Email</th><th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 30.1px;" aria-label="City: activate to sort column descending" aria-sort="ascending">City</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 33.5875px;" aria-label="Date: activate to sort column ascending">Date</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 52.825px;" aria-label="Salary: activate to sort column ascending">Salary</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 27.175px;" aria-label="Age: activate to sort column ascending">Age</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 83.85px;" aria-label="Experience: activate to sort column ascending">Experience</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 50.4px;" aria-label="Status: activate to sort column ascending">Status</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 58.975px;" aria-label="Actions">Actions</th></tr>
              </thead></table></div></div><div class="dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%; max-height: 300px; height: 300px;"><table class="dt-scrollableTable table dataTable no-footer" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 938px;"><thead>
                <tr style="height: 0px;"><th class="sorting sorting_asc" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 39.7375px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-sort="ascending" aria-label="Name: activate to sort column descending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Name</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 63.2125px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Position: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Position</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 41.3875px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Email: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Email</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 30.1px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="City: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">City</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 33.5875px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Date: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Date</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 52.825px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Salary: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Salary</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 27.175px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Age: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Age</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 83.85px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Experience: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Experience</div></th><th class="sorting" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 50.4px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Status: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Status</div></th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 58.975px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Actions"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Actions</div></th></tr>
              </thead>
              <tbody><tr class="odd"><td valign="top" colspan="10" class="dataTables_empty">No data available in table</td></tr></tbody>
            </table></div></div><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div></div><div class="col-sm-12 col-md-6"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link"><i class="ti ti-chevron-left ti-sm"></i></a></li><li class="paginate_button page-item next disabled" id="DataTables_Table_0_next"><a aria-controls="DataTables_Table_0" aria-disabled="true" role="link" data-dt-idx="next" tabindex="-1" class="page-link"><i class="ti ti-chevron-right ti-sm"></i></a></li></ul></div></div></div></div>
          </div>
        </div>
        <!--/ Scrollable -->

        <hr class="my-12">

        <!-- Fixed Header -->
        <div class="card">
          <h5 class="card-header">Fixed Header</h5>
          <div class="card-datatable table-responsive">
            <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper dt-bootstrap5"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="DataTables_Table_1_length"><label>Show <select name="DataTables_Table_1_length" aria-controls="DataTables_Table_1" class="form-select"><option value="7">7</option><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="75">75</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"><div id="DataTables_Table_1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_1"></label></div></div></div><table class="dt-fixedheader table dataTable dtr-column collapsed" id="DataTables_Table_1" aria-describedby="DataTables_Table_1_info" style="width: 1396px;"><thead>
                <tr><th class="control sorting_disabled" rowspan="1" colspan="1" style="width: 58px;" aria-label=""></th><th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 56.025px;" data-col="1" aria-label=""><input type="checkbox" class="form-check-input"></th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_1" rowspan="1" colspan="1" style="width: 159px;" aria-label="Name: activate to sort column ascending">Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_1" rowspan="1" colspan="1" style="width: 162.25px;" aria-label="Email: activate to sort column ascending">Email</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_1" rowspan="1" colspan="1" style="width: 143.913px;" aria-label="Date: activate to sort column ascending">Date</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_1" rowspan="1" colspan="1" style="width: 190.3px;" aria-label="Salary: activate to sort column ascending">Salary</th><th class="sorting dtr-hidden" tabindex="0" aria-controls="DataTables_Table_1" rowspan="1" colspan="1" style="width: 183.825px; display: none;" aria-label="Status: activate to sort column ascending">Status</th><th class="sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 190.688px; display: none;" aria-label="Actions">Actions</th></tr>
              </thead>
              <tbody><tr class="odd"><td valign="top" colspan="6" class="dataTables_empty">Loading...</td></tr></tbody>
              <tfoot>
                <tr><th class="control" rowspan="1" colspan="1" style=""></th><th rowspan="1" colspan="1"></th><th rowspan="1" colspan="1">Name</th><th rowspan="1" colspan="1">Email</th><th rowspan="1" colspan="1">Date</th><th rowspan="1" colspan="1">Salary</th><th rowspan="1" colspan="1" class="dtr-hidden" style="display: none;">Status</th><th rowspan="1" colspan="1" class="dtr-hidden" style="display: none;">Action</th></tr>
              </tfoot>
            </table><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="DataTables_Table_1_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div></div><div class="col-sm-12 col-md-6"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_1_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_1_previous"><a aria-controls="DataTables_Table_1" aria-disabled="true" role="link" data-dt-idx="previous" tabindex="-1" class="page-link"><i class="ti ti-chevron-left ti-sm"></i></a></li><li class="paginate_button page-item next disabled" id="DataTables_Table_1_next"><a aria-controls="DataTables_Table_1" aria-disabled="true" role="link" data-dt-idx="next" tabindex="-1" class="page-link"><i class="ti ti-chevron-right ti-sm"></i></a></li></ul></div></div></div></div>
          </div>
        </div>
        <!--/ Fixed Header -->

        <hr class="my-12">

        <!-- Fixed Columns -->
        <div class="card">
          <h5 class="card-header">Fixed Columns</h5>
          <div class="card-datatable text-nowrap">
            <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer"><div class="d-flex justify-content-between align-items-center row"><div class="col-sm-12 col-md-2 d-flex"><div id="DataTables_Table_2_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_2"></label></div></div><div class="col-sm-12 col-md-10 d-none"></div></div><div class="dataTables_scroll"><div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;"><div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 933.25px; padding-right: 14px;"><table class="dt-fixedcolumns table table-bordered dataTable no-footer" style="margin-left: 0px; width: 933.25px;"><thead>
                <tr><th class="sorting sorting_asc" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 39.7375px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 63.2125px;" aria-label="Position: activate to sort column ascending">Position</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 41.3875px;" aria-label="Email: activate to sort column ascending">Email</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 30.1px;" aria-label="City: activate to sort column ascending">City</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 33.5875px;" aria-label="Date: activate to sort column ascending">Date</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 52.825px;" aria-label="Salary: activate to sort column ascending">Salary</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 27.175px;" aria-label="Age: activate to sort column ascending">Age</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 83.85px;" aria-label="Experience: activate to sort column ascending">Experience</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 50.4px;" aria-label="Status: activate to sort column ascending">Status</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 58.975px;" aria-label="Actions">Actions</th></tr>
              </thead></table></div></div><div class="dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%; max-height: 300px;"><table class="dt-fixedcolumns table table-bordered dataTable no-footer" id="DataTables_Table_2" style="width: 938px;"><thead>
                <tr style="height: 0px;"><th class="sorting sorting_asc" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 39.7375px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-sort="ascending" aria-label="Name: activate to sort column descending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Name</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 63.2125px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Position: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Position</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 41.3875px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Email: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Email</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 30.1px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="City: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">City</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 33.5875px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Date: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Date</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 52.825px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Salary: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Salary</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 27.175px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Age: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Age</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 83.85px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Experience: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Experience</div></th><th class="sorting" aria-controls="DataTables_Table_2" rowspan="1" colspan="1" style="width: 50.4px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Status: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Status</div></th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 58.975px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Actions"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Actions</div></th></tr>
              </thead>
              <tbody><tr class="odd"><td valign="top" colspan="10" class="dataTables_empty">Loading...</td></tr></tbody>
            </table></div></div></div>
          </div>
        </div>
        <!--/ Fixed Columns -->


