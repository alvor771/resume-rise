<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $header['fullName'] ?? 'My CV' }} - Resume</title>
    <style>
        /* Base Styles */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        * {
            box-sizing: border-box;
        }
        
        .resume {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 35%;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 40px 30px;
        }
        
        .photo-container {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 25px;
            border: 4px solid #3498db;
            background: #fff;
        }
        
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .name {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .title {
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #bdc3c7;
            text-align: center;
            margin: 0 0 30px 0;
            font-style: italic;
        }
        
        .contact-info {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }
        
        .contact-info li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #ecf0f1;
        }
        
        .contact-info i {
            margin-right: 10px;
            color: #3498db;
            width: 20px;
            text-align: center;
        }
        
        .section {
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .section-title {
            color: #3498db;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 50px;
            height: 2px;
            background: #3498db;
        }
        
        /* Main Content Styles */
        .main-content {
            width: 65%;
            padding: 50px;
        }
        
        /* Experience & Education Items */
        .timeline-item {
            margin-bottom: 25px;
            position: relative;
            padding-left: 30px;
            border-left: 2px solid #3498db;
        }
        
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        
        .timeline-period {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .timeline-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }
        
        .timeline-subtitle {
            font-size: 16px;
            color: #3498db;
            margin: 0 0 10px 0;
            font-weight: 500;
        }
        
        .timeline-description {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }
        
        /* Skills */
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .skill-tag {
            background: #f1f5f9;
            color: #2c3e50;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            margin-right: 8px;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
        }
        
        /* Languages */
        .language-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }
        
        .language-name {
            font-weight: 500;
        }
        
        .language-level {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Header */
        .header {
            margin-bottom: 30px;
        }
        
        .header-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header-title {
            font-size: 18px;
            color: #3498db;
            margin: 0 0 20px 0;
            font-weight: 500;
        }
        
        /* Print Styles */
        @page {
            size: A4;
            margin: 0;
        }
        
        @media print {
            body {
                background: none;
            }
            
            .resume {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="resume">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Photo -->
            <div class="photo-container">
                @if(isset($header['photo']) && $header['photo'])
                    <img src="{{ $header['photo'] }}" alt="{{ $header['fullName'] }}" class="photo">
                @else
                    <div style="background: #f1f5f9; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 14px;">
                        PROFILE PHOTO
                    </div>
                @endif
            </div>
            
            <!-- Contact Info -->
            <h3 class="section-title">Contact</h3>
            <ul class="contact-info">
                @if(isset($header['email']) && $header['email'])
                    <li><i>✉</i> {{ $header['email'] }}</li>
                @endif
                @if(isset($header['phone']) && $header['phone'])
                    <li><i>📱</i> {{ $header['phone'] }}</li>
                @endif
                @if(isset($header['location']) && $header['location'])
                    <li><i>📍</i> {{ $header['location'] }}</li>
                @endif
                @if(isset($header['website']) && $header['website'])
                    <li><i>🌐</i> {{ $header['website'] }}</li>
                @endif
                @if(isset($header['linkedin']) && $header['linkedin'])
                    <li><i>🔗</i> {{ $header['linkedin'] }}</li>
                @endif
            </ul>
            
            <!-- Skills -->
            <div class="section">
                <h3 class="section-title">Skills</h3>
                <div class="skills-container">
                    @if(isset($skills) && count($skills) > 0)
                        @foreach($skills as $skill)
                            <span class="skill-tag">{{ $skill['name'] ?? 'Skill' }}</span>
                        @endforeach
                    @else
                        <span class="skill-tag">Web Development</span>
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">PHP</span>
                        <span class="skill-tag">Laravel</span>
                        <span class="skill-tag">Vue.js</span>
                        <span class="skill-tag">HTML/CSS</span>
                        <span class="skill-tag">MySQL</span>
                        <span class="skill-tag">Git</span>
                    @endif
                </div>
            </div>
            
            <!-- Languages -->
            <div class="section">
                <h3 class="section-title">Languages</h3>
                @if(isset($languages) && count($languages) > 0)
                    @foreach($languages as $language)
                        <div class="language-item">
                            <span class="language-name">{{ $language['name'] ?? 'Language' }}</span>
                            <span class="language-level">{{ $language['level'] ?? 'Fluent' }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="language-item">
                        <span class="language-name">English</span>
                        <span class="language-level">Fluent</span>
                    </div>
                    <div class="language-item">
                        <span class="language-name">Russian</span>
                        <span class="language-level">Native</span>
                    </div>
                @endif
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h1 class="header-name">{{ $header['fullName'] ?? 'Your Name' }}</h1>
                <h2 class="header-title">{{ $header['title'] ?? 'Professional Title' }}</h2>
                @if(isset($header['summary']) && $header['summary'])
                    <p class="summary">{{ $header['summary'] }}</p>
                @endif
            </div>
            
            <!-- Experience -->
            <div class="section">
                <h2 class="section-title">Work Experience</h2>
                @if(isset($experience) && count($experience) > 0)
                    @foreach($experience as $exp)
                        <div class="timeline-item">
                            <div class="timeline-period">
                                {{ $exp['startDate'] ?? 'Start Date' }} - {{ $exp['endDate'] ?? 'Present' }}
                                @if(isset($exp['location']) && $exp['location'])
                                    | {{ $exp['location'] }}
                                @endif
                            </div>
                            <h3 class="timeline-title">{{ $exp['position'] ?? 'Job Position' }}</h3>
                            <h4 class="timeline-subtitle">{{ $exp['company'] ?? 'Company Name' }}</h4>
                            @if(isset($exp['description']) && $exp['description'])
                                <div class="timeline-description">
                                    {!! nl2br(e($exp['description'])) !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="timeline-item">
                        <div class="timeline-period">Jan 2020 - Present | New York, USA</div>
                        <h3 class="timeline-title">Senior Web Developer</h3>
                        <h4 class="timeline-subtitle">Tech Company Inc.</h4>
                        <div class="timeline-description">
                            <p>Lead developer responsible for building and maintaining web applications using modern technologies. Key achievements include:</p>
                            <ul>
                                <li>Developed and launched 10+ web applications</li>
                                <li>Improved application performance by 40%</li>
                                <li>Mentored junior developers</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Education -->
            <div class="section">
                <h2 class="section-title">Education</h2>
                @if(isset($education) && count($education) > 0)
                    @foreach($education as $edu)
                        <div class="timeline-item">
                            <div class="timeline-period">
                                {{ $edu['startDate'] ?? 'Start Date' }} - {{ $edu['endDate'] ?? 'Present' }}
                                @if(isset($edu['location']) && $edu['location'])
                                    | {{ $edu['location'] }}
                                @endif
                            </div>
                            <h3 class="timeline-title">{{ $edu['degree'] ?? 'Degree' }}</h3>
                            <h4 class="timeline-subtitle">{{ $edu['institution'] ?? 'Institution Name' }}</h4>
                            @if(isset($edu['description']) && $edu['description'])
                                <div class="timeline-description">
                                    {!! nl2br(e($edu['description'])) !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="timeline-item">
                        <div class="timeline-period">2018 - 2020 | Boston, USA</div>
                        <h3 class="timeline-title">Master of Computer Science</h3>
                        <h4 class="timeline-subtitle">University of Technology</h4>
                        <div class="timeline-description">
                            <p>Specialized in Web Development and Data Science. Graduated with honors.</p>
                            <p>Relevant Coursework: Advanced Web Development, Machine Learning, Database Systems</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Projects (Optional) -->
            @if(isset($projects) && count($projects) > 0)
                <div class="section">
                    <h2 class="section-title">Projects</h2>
                    @foreach($projects as $project)
                        <div class="timeline-item">
                            <h3 class="timeline-title">{{ $project['name'] ?? 'Project Name' }}</h3>
                            @if(isset($project['url']))
                                <p><a href="{{ $project['url'] }}" target="_blank">{{ $project['url'] }}</a></p>
                            @endif
                            @if(isset($project['description']) && $project['description'])
                                <div class="timeline-description">
                                    {!! nl2br(e($project['description'])) !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Certifications (Optional) -->
            @if(isset($certifications) && count($certifications) > 0)
                <div class="section">
                    <h2 class="section-title">Certifications</h2>
                    @foreach($certifications as $cert)
                        <div class="timeline-item">
                            <h3 class="timeline-title">{{ $cert['name'] ?? 'Certification Name' }}</h3>
                            <div class="timeline-period">
                                {{ $cert['date'] ?? '' }}
                                @if(isset($cert['issuer']) && $cert['issuer'])
                                    | {{ $cert['issuer'] }}
                                @endif
                            </div>
                            @if(isset($cert['credentialId']) && $cert['credentialId'])
                                <p>Credential ID: {{ $cert['credentialId'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</body>
</html>