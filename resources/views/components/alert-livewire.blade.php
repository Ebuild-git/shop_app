
@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        
    </div>
@enderror



@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible" style="background-color: #008080ea !important;color: white !important;" role="alert">
        {{ session('success') }}
    </div>
@enderror



@if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible" role="alert">
        {{ session('warning') }}
    </div>
@enderror



@if (session()->has('info'))
    <div class="alert alert-info alert-dismissible" role="alert">
        {{ session('info') }}
    </div>
@enderror



@if (session()->has('dark'))
    <div class="alert alert-dark alert-dismissible mb-0" role="alert">
        {{ session('dark') }}
    </div>
@enderror
