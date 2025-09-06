@if(isset($experience) && count($experience) > 0)
<section class="section experience-section">
    <h2 class="section-title">Experience</h2>
    <div class="section-content">
        @foreach($experience as $exp)
        <div class="experience-item">
            <div class="experience-header">
                <h3>{{ $exp['position'] ?? '' }}</h3>
                <div class="experience-period">
                    @if(isset($exp['startDate']))
                        {{ date('M Y', strtotime($exp['startDate'])) }} - 
                        {{ isset($exp['endDate']) ? date('M Y', strtotime($exp['endDate'])) : 'Present' }}
                    @endif
                </div>
            </div>
            <div class="company">{{ $exp['company'] ?? '' }}</div>
            @if(isset($exp['description']))
            <div class="description">{!! nl2br(e($exp['description'])) !!}</div>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif
