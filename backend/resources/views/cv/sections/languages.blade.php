@if(isset($languages) && count($languages) > 0)
<section class="section languages-section">
    <h2 class="section-title">Languages</h2>
    <div class="section-content">
        <div class="languages-container">
            @foreach($languages as $language)
            <div class="language-item">
                <span class="language-name">{{ $language['name'] ?? '' }}</span>
                @if(isset($language['proficiency']))
                <div class="proficiency">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="proficiency-dot {{ $i <= $language['proficiency'] ? 'filled' : '' }}"></span>
                    @endfor
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
