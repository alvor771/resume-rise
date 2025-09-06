@if(isset($skills) && count($skills) > 0)
<section class="section skills-section">
    <h2 class="section-title">Skills</h2>
    <div class="section-content">
        <div class="skills-container">
            @foreach($skills as $skill)
            <div class="skill-item">
                <span class="skill-name">{{ $skill['name'] ?? '' }}</span>
                @if(isset($skill['level']))
                <div class="skill-level">
                    <div class="skill-level-bar" style="width: {{ $skill['level'] }}%"></div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
