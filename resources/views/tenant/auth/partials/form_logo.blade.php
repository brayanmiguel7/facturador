@if ($login->show_logo_in_form)
    @if($company->logo)
    <img class="auth__logo-form" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo" width="120" />
    @else
    <img class="auth__logo-form" src="{{asset('logo/700x300.jpg')}}" alt="Logo" width="120" />
    @endif
@endif
<br>
