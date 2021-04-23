<article class="auth__image" style="background-image: url({{ $login->image }})">
    @if($company->logo)
        <img class="auth__logo {{ $login->position_logo }}" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo" />
    @else
        <img class="auth__logo {{ $login->position_logo }}" src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
    @endif
</article>
