<div class="header">
    <div class="header-content">
        @if(isset($header['photo']) && $header['photo'])
        <div class="photo-container">
            <img src="{{ $header['photo'] }}" alt="Profile Photo" class="profile-photo">
        </div>
        @endif
        <div class="header-info">
            <h1>{{ $header['fullName'] ?? 'Your Name' }}</h1>
            <p class="title">{{ $header['title'] ?? 'Professional Title' }}</p>
            <div class="contact-info">
                @if(isset($header['email']))<div><i class="fas fa-envelope"></i> {{ $header['email'] }}</div>@endif
                @if(isset($header['phone']))<div><i class="fas fa-phone"></i> {{ $header['phone'] }}</div>@endif
                @if(isset($header['location']))<div><i class="fas fa-map-marker-alt"></i> {{ $header['location'] }}</div>@endif
            </div>
        </div>
    </div>
</div>
