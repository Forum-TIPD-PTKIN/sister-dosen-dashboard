<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Profile - Dr. Sarah Johnson</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16a34a', // Changed to green
                        secondary: '#14532d', // Darker green shade
                        accent: '#f97316',
                        light: '#f8fafc',
                        dark: '#0f172a'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); // Adjusted to green gradient
        }
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .achievement-item {
            transition: all 0.3s ease;
        }
        .achievement-item:hover {
            transform: translateX(5px);
        }
        .skill-bar {
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
        }
        .skill-progress {
            height: 100%;
            border-radius: 5px;
        }
        .contact-card {
            transition: all 0.3s ease;
        }
        .contact-card:hover {
            transform: scale(1.02);
        }
        .social-icon {
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="min-h-screen text-gray-800">
    <!-- Header -->
    <header class="bg-primary text-white py-6 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">UIN Syekh Ali Hasan Ahmad Addary Padangsidimpuan</h1>
                <p class="text-green-100">Faculty of Science and Technology</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Profile Section -->
        <section class="mb-12">
            <div class="profile-card bg-white rounded-2xl shadow-xl p-6 md:p-8 flex flex-col md:flex-row items-center">
                <div class="relative mb-6 md:mb-0 md:mr-8">
                    <div class="w-48 h-48 md:w-64 md:h-64 rounded-full overflow-hidden border-4 border-white shadow-lg">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Dr. Sarah Johnson" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute bottom-4 right-4 bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Professor
                    </div>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold text-dark mb-2">Dr. Sarah Johnson</h1>
                    <p class="text-xl text-secondary mb-4">Head of Computer Science Department</p>
                    <p class="text-gray-600 max-w-2xl mb-6">Specializing in Artificial Intelligence, Machine Learning, and Data Science. With over 15 years of experience in academia and industry research.</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-6">
                        <span class="bg-green-100 text-primary px-3 py-1 rounded-full text-sm font-medium">Ph.D. Computer Science</span>
                        <span class="bg-green-100 text-primary px-3 py-1 rounded-full text-sm font-medium">15+ Years Experience</span>
                        <span class="bg-green-100 text-primary px-3 py-1 rounded-full text-sm font-medium">120+ Publications</span>
                    </div>
                    
                    <div class="flex justify-center md:justify-start space-x-4">
                        <a href="https://www.scopus.com/authid/detail.uri?authorId=123456789" class="social-icon bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary">
                            <i class="fas fa-book"></i>
                        </a>
                        <a href="https://scholar.google.com/citations?user=abcdefg" class="social-icon bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary">
                            <i class="fas fa-graduation-cap"></i>
                        </a>
                        <a href="https://sinta.kemdikbud.go.id/authors/profile/123456" class="social-icon bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary">
                            <i class="fas fa-user-graduate"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About & Skills Section -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- About -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">About Me</h2>
                <p class="text-gray-600 mb-4">Dr. Sarah Johnson is a distinguished professor and researcher in the field of Computer Science at the University of Excellence. Her research focuses on developing innovative machine learning algorithms for real-world applications.</p>
                <p class="text-gray-600 mb-4">She has led multiple funded research projects and has been recognized with several awards for her contributions to the field. Her work has been published in top-tier journals and conferences.</p>
                <p class="text-gray-600">When not in the lab or classroom, Dr. Johnson enjoys hiking, reading science fiction novels, and mentoring young researchers.</p>
            </div>
            
            <!-- Skills -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">Expertise</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">Machine Learning</span>
                            <span>95%</span>
                        </div>
                        <div class="skill-bar bg-gray-200">
                            <div class="skill-progress bg-primary w-[95%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">Data Science</span>
                            <span>90%</span>
                        </div>
                        <div class="skill-bar bg-gray-200">
                            <div class="skill-progress bg-primary w-[90%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">Deep Learning</span>
                            <span>85%</span>
                        </div>
                        <div class="skill-bar bg-gray-200">
                            <div class="skill-progress bg-primary w-[85%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">Natural Language Processing</span>
                            <span>80%</span>
                        </div>
                        <div class="skill-bar bg-gray-200">
                            <div class="skill-progress bg-primary w-[80%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">Research Leadership</span>
                            <span>92%</span>
                        </div>
                        <div class="skill-bar bg-gray-200">
                            <div class="skill-progress bg-primary w-[92%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Achievements & Contact -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Achievements -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">Achievements</h2>
                <div class="space-y-4">
                    <div class="achievement-item flex items-start p-4 bg-green-50 rounded-lg border-l-4 border-primary">
                        <div class="mr-4 mt-1 text-primary">
                            <i class="fas fa-trophy text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Best Research Paper Award</h3>
                            <p class="text-gray-600">International Conference on Machine Learning, 2022</p>
                        </div>
                    </div>
                    <div class="achievement-item flex items-start p-4 bg-green-50 rounded-lg border-l-4 border-primary">
                        <div class="mr-4 mt-1 text-primary">
                            <i class="fas fa-medal text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Outstanding Teaching Award</h3>
                            <p class="text-gray-600">University of Excellence, 2021</p>
                        </div>
                    </div>
                    <div class="achievement-item flex items-start p-4 bg-green-50 rounded-lg border-l-4 border-primary">
                        <div class="mr-4 mt-1 text-primary">
                            <i class="fas fa-book-open text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Published 120+ Research Papers</h3>
                            <p class="text-gray-600">Cited over 5,000 times in academic literature</p>
                        </div>
                    </div>
                    <div class="achievement-item flex items-start p-4 bg-green-50 rounded-lg border-l-4 border-primary">
                        <div class="mr-4 mt-1 text-primary">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Mentored 30+ Graduate Students</h3>
                            <p class="text-gray-600">Many now successful academics and industry professionals</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">Contact Information</h2>
                <div class="space-y-4">
                    <div class="contact-card flex items-center p-4 bg-light rounded-lg hover:bg-green-50">
                        <div class="mr-4 text-primary">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Email</h3>
                            <p class="text-gray-600">s.johnson@univ.edu</p>
                        </div>
                    </div>
                    <div class="contact-card flex items-center p-4 bg-light rounded-lg hover:bg-green-50">
                        <div class="mr-4 text-primary">
                            <i class="fas fa-phone-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Phone</h3>
                            <p class="text-gray-600">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    <div class="contact-card flex items-center p-4 bg-light rounded-lg hover:bg-green-50">
                        <div class="mr-4 text-primary">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Office</h3>
                            <p class="text-gray-600">Building B, Room 305<br>University Campus</p>
                        </div>
                    </div>
                    <div class="contact-card flex items-center p-4 bg-light rounded-lg hover:bg-green-50">
                        <div class="mr-4 text-primary">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Office Hours</h3>
                            <p class="text-gray-600">Monday & Wednesday: 2:00 PM - 4:00 PM<br>By appointment</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="font-bold text-lg mb-3">Send a Message</h3>
                    <div class="space-y-3">
                        <input type="text" placeholder="Your Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <input type="email" placeholder="Your Email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <textarea rows="3" placeholder="Your Message" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                        <button type="button" class="w-full bg-primary text-white py-2 rounded-lg font-medium hover:bg-secondary transition">Send Message</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses & Publications -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Courses -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">Current Courses</h2>
                <div class="space-y-4">
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-lg">CS 501: Advanced Machine Learning</h3>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-sm">Graduate</span>
                        </div>
                        <p class="text-gray-600 mt-2">In-depth study of modern machine learning techniques including deep learning, reinforcement learning, and unsupervised methods.</p>
                        <div class="mt-3 flex items-center text-sm text-gray-500">
                            <i class="far fa-calendar mr-2"></i>
                            <span>Fall 2023 Semester</span>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-lg">CS 305: Introduction to Data Science</h3>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-sm">Undergraduate</span>
                        </div>
                        <p class="text-gray-600 mt-2">Fundamentals of data analysis, visualization, and statistical inference with practical applications in Python.</p>
                        <div class="mt-3 flex items-center text-sm text-gray-500">
                            <i class="far fa-calendar mr-2"></i>
                            <span>Spring 2024 Semester</span>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-lg">CS 702: Research Seminar in AI</h3>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-sm">PhD</span>
                        </div>
                        <p class="text-gray-600 mt-2">Weekly seminar discussing current research papers and developments in artificial intelligence and related fields.</p>
                        <div class="mt-3 flex items-center text-sm text-gray-500">
                            <i class="far fa-calendar mr-2"></i>
                            <span>Ongoing</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Publications -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-dark mb-4 pb-2 border-b border-gray-200">Recent Publications</h2>
                <div class="space-y-4">
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <h3 class="font-bold text-lg">Transformers for Time Series Forecasting</h3>
                        <p class="text-gray-600 text-sm italic">Journal of Machine Learning Research, 2023</p>
                        <p class="text-gray-700 mt-2">Exploring the application of transformer architectures for multivariate time series forecasting with improved accuracy.</p>
                        <div class="mt-3 flex space-x-2">
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">Machine Learning</span>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">Time Series</span>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <h3 class="font-bold text-lg">Ethical Considerations in AI Development</h3>
                        <p class="text-gray-600 text-sm italic">AI Ethics Quarterly, 2023</p>
                        <p class="text-gray-700 mt-2">A comprehensive review of ethical frameworks and their implementation in modern AI systems development.</p>
                        <div class="mt-3 flex space-x-2">
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">AI Ethics</span>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">Policy</span>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition">
                        <h3 class="font-bold text-lg">Federated Learning in Healthcare Applications</h3>
                        <p class="text-gray-600 text-sm italic">IEEE Transactions on Medical Informatics, 2022</p>
                        <p class="text-gray-700 mt-2">Novel approaches to privacy-preserving machine learning in distributed healthcare data environments.</p>
                        <div class="mt-3 flex space-x-2">
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">Healthcare</span>
                            <span class="bg-green-100 text-primary px-2 py-1 rounded text-xs">Privacy</span>
                        </div>
                    </div>
                </div>
                <button class="mt-4 text-primary font-medium hover:underline">View All Publications →</button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-2">University of Excellence</h3>
                    <p class="text-gray-400">Advancing knowledge through education and research</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 pt-6 text-center text-gray-500 text-sm">
                <p>&copy; 2023 University of Excellence. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>