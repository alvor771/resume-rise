@if(isset($cvData['summary']) && $cvData['summary'])
<div class="summary-section">
    <h2 class="section-title">
        <i class="fas fa-user-tie"></i>
        Professional Summary
    </h2>
    <div class="summary-content">
        {!! nl2br(e($cvData['summary'])) !!}
    </div>
</div>

<style>
.summary-section {
    margin-bottom: 1.5rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: #4f46e5;
}

.summary-content {
    color: #4b5563;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .summary-section {
        padding: 0.75rem 0;
    }
    
    .section-title {
        font-size: 1.1rem;
    }
    
    .summary-content {
        font-size: 0.9rem;
    }
}
</style>
@endif
