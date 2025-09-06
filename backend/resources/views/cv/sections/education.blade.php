@if(isset($education) && count($education) > 0)
<section class="section education-section">
    <h2 class="section-title">Education</h2>
    <div class="section-content">
        @foreach($education as $edu)
        <div class="education-item">
            <h3>{{ $edu['degree'] ?? '' }}</h3>
            <div class="institution">{{ $edu['institution'] ?? '' }}</div>
            <div class="period">
                @if(isset($edu['startDate']))
                    {{ date('Y', strtotime($edu['startDate'])) }} - 
                    {{ isset($edu['endDate']) ? date('Y', strtotime($edu['endDate'])) : 'Present' }}
                @endif
            </div>
            @if(isset($edu['description']))
            <div class="description">{!! nl2br(e($edu['description'])) !!}</div>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif
