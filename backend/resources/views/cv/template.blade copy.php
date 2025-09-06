<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $header['fullName'] ?? 'Resume' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* min-height: 100vh; */
            padding: 20px 0;
        }
        
        .resume {
            display: flex;
            /* width: 210mm;
            min-height: 297mm; */
            margin: 0;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            width: 35%;
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
        }
        
        .main-content {
            /* width: 65%; */
            padding: 40px;
        }
        
        .photo-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid rgba(255,255,255,0.2);
        }
        
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .name {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
            color: white;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .title {
            font-size: 16px;
            color: #ecf0f1;
            text-align: center;
            margin: 0 0 30px 0;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .experience-item, .education-item {
            margin-bottom: 20px;
        }
        
        .experience-title, .education-title {
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        
        .experience-company, .education-institution {
            font-weight: 500;
            color: #3498db;
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        
        .experience-duration, .education-duration {
            color: #7f8c8d;
            font-size: 13px;
            margin: 0 0 8px 0;
        }
        
        .experience-description, .education-description {
            font-size: 14px;
            color: #555;
            margin: 8px 0 0 0;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .skill-tag {
            background: #3498db;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .languages-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .language-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .language-name {
            font-weight: 500;
        }
        
        .language-level {
            color: #7f8c8d;
        }
        
        @page {
            size: A4;
            margin: 0;
        }
        
       
    </style>
</head>
<body>
    <div class="resume">
        <main class="main-content">
            <div class="section">
                <h2 class="section-title">Professional Summary</h2>
                <p>{{ $summary ?? 'Results-driven professional with extensive experience in web development. Skilled in modern web technologies and frameworks. Strong problem-solving abilities and a passion for creating efficient, scalable applications.' }}</p>
            </div>
            
            <div class="section">
                <h2 class="section-title">Work Experience</h2>
                @if(isset($experience) && is_array($experience) && count($experience) > 0)
                    @foreach($experience as $job)
                        <div class="experience-item">
                            <h3 class="experience-title">{{ $job['position'] ?? 'Job Position' }}</h3>
                            <p class="experience-company">{{ $job['company'] ?? 'Company Name' }}</p>
                            <p class="experience-duration">
                                {{ $job['start_date'] ?? 'Jan 2020' }} - {{ $job['end_date'] ?? 'Present' }}
                                @if(isset($job['location']) && $job['location'])
                                    | {{ $job['location'] }}
                                @endif
                            </p>
                            @if(isset($job['description']) && $job['description'])
                                <p class="experience-description">{{ $job['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="experience-item">
                        <h3 class="experience-title">Senior Web Developer</h3>
                        <p class="experience-company">Tech Solutions Inc.</p>
                        <p class="experience-duration">Jan 2020 - Present | New York, USA</p>
                        <p class="experience-description">Leading a team of developers in creating innovative web applications using modern technologies. Responsible for architecture decisions and code reviews.</p>
                    </div>
                    
                    <div class="experience-item">
                        <h3 class="experience-title">Web Developer</h3>
                        <p class="experience-company">Digital Creations</p>
                        <p class="experience-duration">Mar 2018 - Dec 2019 | San Francisco, USA</p>
                        <p class="experience-description">Developed and maintained multiple client websites using PHP, JavaScript, and various CMS platforms.</p>
                    </div>
                @endif
            </div>
            
            <div class="section">
                <h2 class="section-title">Education</h2>
                @if(isset($education) && is_array($education) && count($education) > 0)
                    @foreach($education as $edu)
                        <div class="education-item">
                            <h3 class="education-title">{{ $edu['degree'] ?? 'Degree' }}</h3>
                            <p class="education-institution">{{ $edu['institution'] ?? 'Institution Name' }}</p>
                            <p class="education-duration">
                                {{ $edu['start_date'] ?? '2014' }} - {{ $edu['end_date'] ?? '2018' }}
                                @if(isset($edu['location']) && $edu['location'])
                                    | {{ $edu['location'] }}
                                @endif
                            </p>
                            @if(isset($edu['description']) && $edu['description'])
                                <p class="education-description">{{ $edu['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="education-item">
                        <h3 class="education-title">Master of Computer Science</h3>
                        <p class="education-institution">University of Technology</p>
                        <p class="education-duration">2016 - 2018 | Boston, USA</p>
                        <p class="education-description">Specialized in Web Technologies and Software Engineering.</p>
                    </div>
                    
                    <div class="education-item">
                        <h3 class="education-title">Bachelor of Science in Computer Science</h3>
                        <p class="education-institution">State University</p>
                        <p class="education-duration">2012 - 2016 | Chicago, USA</p>
                    </div>
                @endif
            </div>
        </main>
</body>
</html>