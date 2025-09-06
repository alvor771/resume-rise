<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $header['fullName'] ?? 'My CV' }} - Resume</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        
        .resume {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        /* Header Styles */
        .header {
            background: #4f46e5;
            color: white;
            padding: 2rem;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .photo-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 2rem;
            border: 4px solid white;
            flex-shrink: 0;
        }
        
        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .header-info h1 {
            font-size: 2.25rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .header-info .title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .contact-info {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .contact-info div {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        /* Section Styles */
        .section {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            color: #4f46e5;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        /* Experience & Education */
        .experience-item, .education-item {
            margin-bottom: 1.5rem;
        }
        
        .experience-item:last-child, .education-item:last-child {
            margin-bottom: 0;
        }
        
        .experience-header, .education-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .experience-item h3, .education-item h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
        }
        
        .company, .institution {
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        
        .experience-period, .period {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .description {
            color: #4b5563;
            line-height: 1.6;
            margin-top: 0.5rem;
        }
        
        /* Skills */
        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .skill-item {
            margin-bottom: 1rem;
        }
        
        .skill-name {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        .skill-level {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .skill-level-bar {
            height: 100%;
            background: #4f46e5;
            border-radius: 4px;
        }
        
        /* Languages */
        .languages-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .language-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .language-name {
            font-weight: 500;
        }
        
        .proficiency {
            display: flex;
            gap: 0.25rem;
        }
        
        .proficiency-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid #4f46e5;
        }
        
        .proficiency-dot.filled {
            background: #4f46e5;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: none;
            }
            
            .resume {
                box-shadow: none;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="resume">
        @include('cv.sections.header', ['header' => $header])
        
        <main class="main-content">
            @if(isset($summary) && $summary)
            <section class="section">
                <h2 class="section-title">About Me</h2>
                <div class="section-content">
                    <p>{!! nl2br(e($summary)) !!}</p>
                </div>
            </section>
            @endif

            @include('cv.sections.experience', ['experience' => $experience ?? []])
            @include('cv.sections.education', ['education' => $education ?? []])
            @include('cv.sections.skills', ['skills' => $skills ?? []])
            @include('cv.sections.languages', ['languages' => $languages ?? []])
        </main>
    </div>
</body>
</html>