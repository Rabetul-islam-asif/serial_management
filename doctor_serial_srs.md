You are an elite software architect, senior PHP engineer, product designer, and UI/UX specialist with 15+ years of experience building premium healthcare products.



Your goal is NOT to generate a typical AI CRUD project.



Build a premium, handcrafted, production-ready Doctor Serial Management Platform that feels like it was designed and developed by a professional software company.

The application is built specifically for a single doctor (1 doctor) with their dedicated profile and settings. It supports exactly 3 roles:

• Doctor/Admin (The doctor owns the app, manages settings, manages receptionists, and writes prescriptions)

• Receptionist (Staff who register patients, print tokens, and control the live queue)

• User / Patient (Patients who book appointments, view live queue, and login via OTP to view prescriptions)



The UI must look modern, polished, premium, and unique.



Avoid generic Bootstrap admin templates.



The entire project should feel similar to Linear, Notion, Stripe Dashboard, Vercel Dashboard, Raycast, or Apple's design language.



==================================================

PROJECT NAME

==================================================



Doctor Serial Cloud



Tagline:



"Smart Queue. Digital Prescription. Modern Clinic."



==================================================

MAIN MODULES

==================================================



1. Doctor Public Profile



2. Smart Serial Management



3. Prescription Cloud



==================================================

TECH STACK

==================================================



Backend



• PHP 8.3

• MVC Architecture

• PDO

• REST API

• MySQL 8



Frontend



• HTML5

• CSS3

• SCSS

• Bootstrap 5 (Customized heavily)

• Vanilla JavaScript

• Alpine.js (optional)

• AJAX

• Chart.js



Use modern reusable components.



No jQuery spaghetti code.



==================================================

DESIGN LANGUAGE

==================================================



The application MUST NOT look AI generated.



Design should feel handcrafted.



Requirements



✓ Large spacing



✓ Rounded corners (16-20px)



✓ Soft shadows



✓ Beautiful typography



✓ Modern icons



✓ Smooth animations



✓ Glass morphism where appropriate



✓ Premium cards



✓ Sticky navigation



✓ Floating Action Buttons



✓ Empty states



✓ Skeleton loading



✓ Beautiful tables



✓ Animated statistics cards



✓ Micro interactions



Use a design system.



Primary Color



#2563EB



Accent



#14B8A6



Danger



#EF4444



Background



#F8FAFC



Dark Mode



Supported



==================================================

PROJECT STRUCTURE

==================================================



/app



/controllers



/models



/views



/helpers



/middleware



/config



/public



/assets



/css



/js



/images



/icons



/uploads



/database



/routes



/storage



/logs



==================================================

MODULE 1



DOCTOR PUBLIC PROFILE

==================================================



The doctor gets a public profile page.



Profile includes



• Cover Image



• Doctor Photo



• Name



• Degree



• Specialization



• BMDC Number



• Hospital



• Chamber



• Visiting Schedule



• Consultation Fee



• Years of Experience



• Languages



• Biography



• Awards



• Education



• Services



• Google Map



• Gallery



• Live Queue Status



• Current Token



• Average Waiting Time



• Book Appointment



• Share Profile



• QR Code



==================================================

MODULE 2



SMART SERIAL MANAGEMENT

==================================================



The receptionist has complete queue control.



Features



Automatic Serial



Manual Serial



Walk-in



Appointment



Emergency



VIP



Follow-up



Report Patient



Senior Citizen



Pregnant



Custom Priority



Receptionist Queue Engine



Allow receptionist to configure



Example



3 Normal Patients



↓



2 Report Patients



↓



Repeat



Allow



5 Normal



↓



1 VIP



↓



2 Report



↓



Repeat



Missed Patient Logic



Original Serial never changes.



If Serial 4 misses.



Running is 6.



Receptionist chooses



Rejoin after



3 patients



System automatically places



7



8



9



↓



Serial 4



Queue Status



Waiting



Called



Hold



Skipped



Missed



Emergency



VIP



Completed



Cancelled



No Show



Queue Actions



Call



Recall



Hold



Skip



Emergency



VIP



Report



Edit



Cancel



Complete



Move Up



Move Down



Drag & Drop



Print Token



Queue Settings



Configure custom queue rules (e.g. normal/report patient ratios, missed patient rejoin logic).



==================================================

LIVE QUEUE BOARD

==================================================



Public



No Login Required



Large Screen Mode



TV Mode



Reception Display



Display



Now Serving



Current Token



Next Token



Estimated Time



Chamber



Room



Announcements



Beautiful animation



Auto Refresh



==================================================

ESTIMATED WAITING TIME

==================================================



Calculate dynamically.



Formula



Average Consultation Time



×



Patients Before You



Update continuously.



==================================================

MODULE 3



PRESCRIPTION CLOUD

==================================================



Patients never create an account.



The mobile number used during appointment becomes the login identity.



Authentication



Enter Mobile Number



↓



Receive OTP



↓



Login



Patient Dashboard



View



Download



Print



All Prescriptions



Timeline



Laboratory Reports



Medical History



Invoices



Doctor Notes



Search Prescription



Filter



Download PDF



==================================================

DOCTOR PANEL

==================================================



Today's Queue



Current Patient



Next Patient



Search Patient



History



Prescription Editor



Medicine Suggestions



Favorites



Diagnosis



Lab Requests



Complete Visit



==================================================

RECEPTION PANEL

==================================================



Dashboard



Today's Statistics



Quick Register



Quick Search



Today's Queue



Queue Settings



Token Print



Appointment



Patient Registration



Reports



==================================================

DOCTOR / ADMIN PANEL

==================================================



Receptionists



Users / Patients



Chambers



Audit Logs



Analytics



Settings



==================================================

ANALYTICS DASHBOARD

==================================================



Today's Patients



Today's Revenue



Average Waiting Time



Completed



Pending



Cancelled



Chamber Performance



Charts



==================================================

DATABASE

==================================================



Design a fully normalized database.



Include



Chambers



Patients



Appointments



Serials



Visits



Prescriptions



Medicines



Prescription Items



Reports



Invoices



Payments



Users



Roles



Permissions



Audit Logs



Notifications



==================================================

SECURITY

==================================================



PDO Prepared Statements



CSRF Protection



XSS Protection



Session Security



Rate Limiting



OTP Login



Role Permissions



Activity Logs



==================================================

PERFORMANCE

==================================================



Optimize SQL



Indexes



Caching



Lazy Loading



AJAX Updates



Reusable Components



==================================================

UX DETAILS

==================================================



Every page should have



Loading State



Empty State



Success Animation



Error Animation



Confirmation Dialog



Undo Actions



Keyboard Shortcuts



Toast Notifications



Search Everywhere



Quick Actions



==================================================

DO NOT

==================================================



Do NOT generate generic CRUD pages.



Do NOT create Bootstrap-looking dashboards.



Do NOT use default DataTables styling.



Do NOT use repetitive card layouts.



Do NOT use ugly forms.



Do NOT use AI-style UI.



==================================================

EXPECTED QUALITY

==================================================



The application should look like a commercial SaaS product sold for $200–500.



Every screen should feel handcrafted.



Every component should be reusable.



Every animation should feel natural.



The code should be clean enough for a professional development team to maintain.



Generate the project incrementally, module by module, with proper architecture, database migrations, controllers, models, views, reusable UI components, and documentation.