<?php
session_start();

// Initialize session to store applicants if not already set
if (!isset($_SESSION['applicants'])) {
    $_SESSION['applicants'] = [];
}

// Initialize variables to store messages
$successMessage = "";
$errorMessage = "";

// Determine current page (default is page 1)
$step = isset($_POST['next_step']) ? (int)$_POST['next_step'] : 1;

// Process the final submission on Page 2
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalize_submission'])) {
    // Collect data from hidden fields (from page 1)
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $educationLevel = htmlspecialchars(trim($_POST['educationLevel'] ?? ''));
    $gpa = htmlspecialchars(trim($_POST['gpa'] ?? ''));
    $motivation = htmlspecialchars(trim($_POST['motivation'] ?? ''));

    // Handle File Uploads (In this simulation, we store the filenames)
    $uploadedFiles = [
        'ID Card' => $_FILES['idCard']['name'] ?? 'No file',
        'Family Card' => $_FILES['familyCard']['name'] ?? 'No file',
        'Student Card' => $_FILES['studentCard']['name'] ?? 'No file',
        'Active Student Certificate' => $_FILES['activeCertificate']['name'] ?? 'No file',
        'Transcript' => $_FILES['transcript']['name'] ?? 'No file',
        'Disability Certificate' => $_FILES['disabilityCertificate']['name'] ?? 'No file'
    ];

    // Simple validation
    if (!empty($fullName) && !empty($email)) {
        // Add to the list
        $_SESSION['applicants'][] = [
            'name' => $fullName,
            'email' => $email,
            'level' => $educationLevel,
            'gpa' => $gpa,
            'files' => $uploadedFiles,
            'date' => date('Y-m-d H:i')
        ];
        $successMessage = "Thank you, <strong>$fullName</strong>! Your application and documents have been successfully submitted.";
        $step = 3; // Move to the list page
    } else {
        $errorMessage = "Submission failed. Please ensure your session is active.";
        $step = 1;
    }
}

// Logic for navigating to the list directly via navigation (optional)
if (isset($_GET['view']) && $_GET['view'] == 'list') {
    $step = 3;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Dream Scholarship — Beasiswa Impian Emas</title>
    <!-- Import Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom configuration for Tailwind colors if needed -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a', // Dark blue
                        secondary: '#3b82f6', // Light blue
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800 min-h-screen flex flex-col">

    <!-- Header Section (Same as previous) -->
    <header class="bg-primary text-white py-6 shadow-md">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Golden Dream Scholarship — Beasiswa Impian Emas</h1>
                <p class="mt-2 text-blue-200">Empowering future leaders through education. Apply now for the 2026 Academic Year.</p>
            </div>
            <?php if($step == 3): ?>
                <a href="index.php" class="bg-white text-primary px-4 py-2 rounded-md font-bold text-sm hover:bg-blue-50 transition">Back to Form</a>
            <?php else: ?>
                <a href="?view=list" class="bg-blue-800 text-white px-4 py-2 rounded-md font-bold text-sm hover:bg-blue-700 transition">View Applicants</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        
        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6 shadow-sm" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo $successMessage; ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 shadow-sm" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $errorMessage; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
        <!-- STEP 1: REGISTRATION FORM (Identical to original) -->
        
        <!-- Explanation / Introduction Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-3">About This Program</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Welcome to our official scholarship application portal. This program aims to provide financial assistance to outstanding students who demonstrate exceptional academic performance and strong leadership potential. 
            </p>
            <div class="bg-blue-50 border-l-4 border-secondary p-4 rounded-r-md">
                <h3 class="font-medium text-primary mb-1">Instructions:</h3>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                    <li>Please ensure all provided information is accurate and up-to-date.</li>
                    <li>Fields marked with an asterisk (<span class="text-red-500">*</span>) are mandatory.</li>
                    <li>Your motivation letter should be concise but clearly explain why you deserve this scholarship.</li>
                </ul>
            </div>
        </div>

        <!-- Scholarship Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Requirements -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Eligibility Requirements
                </h3>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                    <li>Indonesian Citizen (WNI)</li>
                    <li>Registered as an active student (D3/S1/S2) in college</li>
                    <li>Having a disability condition (physical, sensory, intellectual, or mental) which is proven by an official letter</li>
                    <li>Not currently receiving other scholarships (or allowed, depending on the policy)</li>
                    <li>Have a minimum GPA (for example 2.75 or 3.00)</li>
                </ul>
            </div>

            <!-- Benefits -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Scholarship Benefits
                </h3>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                    <li>Education fee assistance (UKT/SPP)</li>
                    <li>Facility support for people with disabilities (aids or accessibility)</li>
                    <li>Self-development opportunities (training/seminar)</li>
                    <li>Accompaniment during the study period</li>
                </ul>
            </div>
            
            <!-- Required Documents -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Required Documents
                </h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600 list-disc list-inside">
                    <li>Photocopy of ID Card and Family Card</li>
                    <li>Student Identification Card (KTM)</li>
                    <li>Disability certificate from the hospital/related institution</li>
                    <li>Transcript of the last grade or GPA</li>
                    <li>Certificate of active college</li>
                </ul>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Application Form</h2>
            </div>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="p-6 sm:p-8 space-y-6">
                <input type="hidden" name="next_step" value="2">
                
                <!-- Section 1: Personal Information -->
                <div>
                    <h3 class="text-md font-medium text-primary border-b pb-2 mb-4">1. Personal Information</h3>
                    <p class="text-xs text-gray-500 mb-4">Provide your legal name and current contact details so we can reach you easily.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Full Legal Name <span class="text-red-500">*</span></label>
                            <input type="text" name="fullName" id="fullName" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors" 
                                placeholder="Name You"
                                value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>">
                            <p class="text-xs text-gray-500 mt-1">Enter your name exactly as it appears on your official documents.</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors" 
                                placeholder="Email You"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <p class="text-xs text-gray-500 mt-1">We will send all notifications to this email.</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors" 
                                placeholder="active number"
                                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <p class="text-xs text-gray-500 mt-1">Include your country code if applying from abroad.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Academic Background -->
                <div class="pt-4">
                    <h3 class="text-md font-medium text-primary border-b pb-2 mb-4">2. Academic Background</h3>
                    <p class="text-xs text-gray-500 mb-4">Tell us about your current academic standing.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Education Level -->
                        <div>
                            <label for="educationLevel" class="block text-sm font-medium text-gray-700 mb-1">Current Education Level <span class="text-red-500">*</span></label>
                            <select name="educationLevel" id="educationLevel" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors bg-white">
                                <option value="" disabled <?php echo empty($_POST['educationLevel']) ? 'selected' : ''; ?>>Select a level...</option>
                                <option value="High School" <?php echo (isset($_POST['educationLevel']) && $_POST['educationLevel'] == 'High School') ? 'selected' : ''; ?>>High School (SMA/SMK equivalent)</option>
                                <option value="Bachelor's Degree" <?php echo (isset($_POST['educationLevel']) && $_POST['educationLevel'] == "Bachelor's Degree") ? 'selected' : ''; ?>>Bachelor's Degree (S1)</option>
                                <option value="Master's Degree" <?php echo (isset($_POST['educationLevel']) && $_POST['educationLevel'] == "Master's Degree") ? 'selected' : ''; ?>>Master's Degree (S2)</option>
                            </select>
                        </div>

                        <!-- GPA -->
                        <div>
                            <label for="gpa" class="block text-sm font-medium text-gray-700 mb-1">Cumulative GPA <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" max="4.0" name="gpa" id="gpa" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors" 
                                placeholder="3.75"
                                value="<?php echo isset($_POST['gpa']) ? htmlspecialchars($_POST['gpa']) : ''; ?>">
                            <p class="text-xs text-gray-500 mt-1">Enter your GPA on a 4.0 scale.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Motivation -->
                <div class="pt-4">
                    <h3 class="text-md font-medium text-primary border-b pb-2 mb-4">3. Motivation Letter</h3>
                    <p class="text-xs text-gray-500 mb-4">This is your chance to stand out. Share your goals, achievements, and financial needs.</p>
                    
                    <div>
                        <label for="motivation" class="block text-sm font-medium text-gray-700 mb-1">Why do you deserve this scholarship?</label>
                        <textarea name="motivation" id="motivation" rows="5" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-secondary focus:border-secondary transition-colors resize-y" 
                            placeholder="Write your motivation here..."><?php echo isset($_POST['motivation']) ? htmlspecialchars($_POST['motivation']) : ''; ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 100 words recommended. Be authentic and clear.</p>
                    </div>
                </div>

                <!-- Next Step Button -->
                <div class="pt-6 flex items-center justify-end border-t border-gray-200">
                    <button type="submit" class="bg-primary hover:bg-blue-800 text-white font-medium py-2.5 px-6 rounded-md shadow transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Next: Upload Documents →
                    </button>
                </div>
            </form>
        </div>

        <?php elseif ($step == 2): ?>
        <!-- STEP 2: DOCUMENT UPLOAD (New Page) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden max-w-3xl mx-auto border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Document Upload — Step 2 of 2</h2>
            </div>
            
            <form action="index.php" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                <!-- Pass data from Page 1 as hidden fields -->
                <input type="hidden" name="fullName" value="<?php echo htmlspecialchars($_POST['fullName'] ?? ''); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                <input type="hidden" name="educationLevel" value="<?php echo htmlspecialchars($_POST['educationLevel'] ?? ''); ?>">
                <input type="hidden" name="gpa" value="<?php echo htmlspecialchars($_POST['gpa'] ?? ''); ?>">
                <input type="hidden" name="motivation" value="<?php echo htmlspecialchars($_POST['motivation'] ?? ''); ?>">
                
                <h3 class="text-md font-medium text-primary border-b pb-2 mb-4 uppercase tracking-wide">English Document Submission</h3>
                <p class="text-sm text-gray-600 mb-6 italic">Please upload high-quality scans or photos of the following documents (PDF, JPG, or PNG).</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- ID Card (KTP) -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">1. Identity Card (KTP) *</label>
                        <input type="file" name="idCard" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Family Card (KK) -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">2. Family Card (KK) *</label>
                        <input type="file" name="familyCard" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Student ID (KTM) -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">3. Student ID Card (KTM) *</label>
                        <input type="file" name="studentCard" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Active Student Cert -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">4. Certificate of Active College *</label>
                        <input type="file" name="activeCertificate" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Transcript -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">5. Academic Transcript *</label>
                        <input type="file" name="transcript" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Disability Cert -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase mb-2">6. Disability Certificate *</label>
                        <input type="file" name="disabilityCertificate" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="pt-8 flex items-center justify-between border-t border-gray-200">
                    <button type="button" onclick="window.history.back()" class="text-gray-600 hover:text-primary font-medium">← Back to Info</button>
                    <button type="submit" name="finalize_submission" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-10 rounded-md shadow transition duration-150 ease-in-out">
                        Finalize & Submit Application
                    </button>
                </div>
            </form>
        </div>

        <?php elseif ($step == 3): ?>
        <!-- STEP 3: APPLICANT LIST (New Page) -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Registered Applicants List</h2>
                    <p class="text-sm text-gray-500">Monitoring all submitted applications for the Golden Dream Scholarship.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-gray-400 uppercase">Status</span><br>
                    <span class="text-green-600 font-bold">● Active Intake</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-600 border-b">Applicant Name</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-600 border-b">Education/GPA</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-600 border-b">Uploaded Files</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-600 border-b text-right">Submission Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($_SESSION['applicants'])): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">No applicants found yet. Be the first to apply!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach (array_reverse($_SESSION['applicants']) as $applicant): ?>
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900"><?php echo $applicant['name']; ?></div>
                                        <div class="text-xs text-gray-500 font-medium"><?php echo $applicant['email']; ?></div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-bold mb-1"><?php echo $applicant['level']; ?></span>
                                        <div class="text-sm font-semibold text-gray-700">GPA: <?php echo $applicant['gpa']; ?></div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-wrap gap-1">
                                            <?php foreach ($applicant['files'] as $key => $filename): ?>
                                                <span class="bg-gray-200 text-gray-600 text-[10px] px-1.5 py-0.5 rounded flex items-center" title="<?php echo $filename; ?>">
                                                    <svg class="w-2.5 h-2.5 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                    <?php echo $key; ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-xs font-medium text-gray-500"><?php echo $applicant['date']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">Only authorized personnel can view detailed documents and motivation letters.</p>
        </div>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-auto">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <p class="text-sm text-gray-400">&copy; 2026 Golden Dream Foundation. All rights reserved.</p>
            <p class="text-xs text-gray-500 mt-1">For technical issues, please contact support@scholarship-example.com</p>
        </div>
    </footer>

</body>
</html>