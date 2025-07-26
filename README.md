# SIP System - Student Internship Program Management System

## Table of Contents
1. [System Overview](#system-overview)
2. [Technology Stack](#technology-stack)
3. [User Roles and Access Control](#user-roles-and-access-control)
4. [Core Functionalities](#core-functionalities)
5. [Database Structure](#database-structure)
6. [API Endpoints](#api-endpoints)
7. [Features by User Role](#features-by-user-role)
8. [System Architecture](#system-architecture)
9. [Installation and Setup](#installation-and-setup)

## System Overview

The SIP (Student Internship Program) System is a comprehensive web-based application designed to manage student internship programs. It provides a complete solution for tracking student progress, managing documents, monitoring attendance, and facilitating communication between students, coordinators, and administrators.

### Key Features
- **Multi-role User Management**: Students, Coordinators, and Administrators
- **Document Management**: Upload, track, and manage internship requirements
- **Attendance Tracking**: Monitor student attendance with time-in/time-out records
- **Journal Management**: Students can submit weekly journals for review
- **Host Training Establishment Management**: Manage internship placement locations
- **School Year Management**: Organize students by academic periods
- **Progress Tracking**: Visual progress indicators for document completion
- **Export Functionality**: Generate attendance reports in Excel format
- **Chatbot Integration**: BotMan integration for automated interactions

## Technology Stack

### Backend
- **Framework**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL/PostgreSQL (with migrations)
- **Authentication**: Laravel Breeze with Sanctum
- **Frontend Integration**: Inertia.js
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite Excel
- **Chatbot**: BotMan with Web Driver

### Frontend
- **Framework**: Vue.js 3
- **Styling**: Tailwind CSS
- **Icons**: Material Design Icons (MDI)
- **Charts**: Chart.js integration
- **Build Tool**: Vite

### Development Tools
- **Package Management**: Composer (PHP), NPM (JavaScript)

## User Roles and Access Control

### 1. Student Role
- **Purpose**: Primary users who are participating in the internship program
- **Permissions**:
  - View and submit required documents
  - Track personal attendance
  - Submit weekly journals
  - View progress and placement information
  - Update profile information

### 2. Coordinator Role
- **Purpose**: Academic staff who oversee student internships
- **Permissions**:
  - View all students in their assigned course
  - Monitor student document completion
  - Review and provide feedback on journals
  - Track student attendance
  - Manage host training establishments
  - Export attendance reports
  - Filter students by class blocks
  - Update profile information

### 3. Admin Role
- **Purpose**: System administrators with full access
- **Permissions**:
  - Manage all users (students, coordinators)
  - Create and manage school years
  - Update profile information
    
## Core Functionalities

### 1. User Management
- **User Registration and Authentication**
  - Profile management with avatar upload
  - Role-based access control

- **Student Management**
  - Student registration with course and block assignment
  - Student number assignment
  - Host training establishment placement
  - School year assignment

- **Coordinator Management**
  - Coordinator registration and assignment to courses
  - Student oversight capabilities

### 2. Document Management System
- **Document Upload and Storage**
  - File upload functionality
  - Document categorization
  - Due date management
  - File download capabilities

- **Student Document Tracking**
  - Individual document completion status
  - Progress percentage calculation
  - Document submission tracking
  - File attachment support

- **Coordinator Document Oversight**
  - View all student document submissions
  - Track completion rates
  - Monitor submission deadlines

### 3. Attendance Management
- **Daily Attendance Tracking**
  - Time-in and time-out recording (AM/PM sessions)
  - Date-based attendance records
  - Student-specific attendance history

- **Attendance Monitoring**
  - Real-time attendance status
  - Daily attendance reports
  - Monthly attendance summaries
  - Attendance export functionality

- **Coordinator Attendance Oversight**
  - View all students' attendance for specific dates
  - Filter by class blocks
  - Attendance statistics and reports

### 4. Journal Management
- **Student Journal Submission**
  - Weekly journal entries
  - Rich text content support
  - Journal submission tracking

- **Coordinator Journal Review**
  - Review submitted journals
  - Provide feedback on entries
  - Mark journals as reviewed
  - Track unreviewed journals

- **Journal Analytics**
  - Latest journal submissions
  - Journal completion statistics
  - Review status tracking

### 5. Host Training Establishment (HTE) Management
- **HTE Registration and Management**
  - Company/organization registration
  - Contact information management
  - MOA (Memorandum of Agreement) file management
  - HTE status tracking

- **Student Placement**
  - Assign students to HTEs
  - Track placement status

- **HTE Documentation**
  - MOA file upload and storage
  - Document download capabilities
  - Agreement tracking

### 6. School Year Management
- **Academic Period Management**
  - Create and manage school years
  - Set active school year
  - Student enrollment by school year

- **Year-based Organization**
  - Organize students by academic year
  - Year-specific data isolation
  - Academic period transitions

### 7. Progress Tracking and Analytics
- **Student Progress Monitoring**
  - Document completion percentage
  - Attendance statistics
  - Journal submission tracking

- **Coordinator Analytics**
  - Course-wide progress overview
  - Attendance analytics

### 8. Export and Reporting
- **Attendance Export**
  - Excel format export
  - Date range selection
  - Student-specific reports

- **Document Reports**
  - Completion status reports
  - Submission tracking
  - Deadline monitoring

## Database Structure

### Core Tables
1. **users** - User accounts and profiles
2. **attendances** - Daily attendance records
3. **documents** - Required documents and templates
4. **student_document** - Student document submissions (pivot table)
5. **journals** - Student weekly journal entries
6. **host_training_establishments** - Internship placement locations
7. **school_years** - Academic year management
8. **password_reset_tokens** - Password reset functionality
9. **personal_access_tokens** - API authentication
10. **sessions** - User session management

### Key Relationships
- Users belong to School Years
- Users belong to Host Training Establishments
- Users have many Attendances
- Users have many Journals
- Users belong to many Documents (through student_document pivot)
- Host Training Establishments have many Users

## API Endpoints

### Authentication Routes
- `POST /login` - User authentication
- `POST /logout` - User logout
- `POST /forgot-password` - Password reset request
- `POST /reset-password` - Password reset
- `GET /verify-email` - Email verification

### User Management
- `GET /users/student` - List students (Admin)
- `GET /users/coordinator` - List coordinators (Admin)
- `GET /users/student-hte` - List student HTE placements (Coordinator)
- `POST /users` - Create user (Admin)
- `PUT /users/{id}` - Update user (Admin)
- `DELETE /users/{id}` - Delete user (Admin)

### Document Management
- `GET /documents` - List documents
- `POST /documents` - Create document (Admin/Coordinator)
- `GET /documents/{id}` - View document
- `PUT /documents/{id}` - Update document
- `DELETE /documents/{id}` - Delete document
- `POST /documents/{id}/upload` - Upload document file
- `GET /documents/download/{document}` - Download document
- `POST /student-document/completion` - Update completion status

### Attendance Management
- `GET /attendances` - List attendance records
- `POST /attendances` - Create attendance record
- `GET /attendances/{id}` - View attendance
- `PUT /attendances/{id}` - Update attendance
- `DELETE /attendances/{id}` - Delete attendance
- `GET /student-attendance/{id}` - View student attendance
- `GET /attendance/export` - Export attendance data

### Journal Management
- `GET /journals` - List journals
- `POST /journals` - Create journal entry
- `GET /journals/{id}` - View journal
- `PUT /journals/{id}` - Update journal
- `DELETE /journals/{id}` - Delete journal
- `POST /journals/{id}/mark-reviewed` - Mark journal as reviewed
- `POST /journals/{id}/feedback` - Add feedback to journal

### Host Training Establishment
- `GET /htes` - List HTEs
- `POST /htes` - Create HTE
- `GET /htes/{id}` - View HTE
- `PUT /htes/{id}` - Update HTE
- `DELETE /htes/{id}` - Delete HTE
- `GET /htes/{id}/download-moa` - Download MOA file
- `POST /htes/{id}/update-moa-file` - Update MOA file

### School Year Management
- `GET /schoolyears` - List school years
- `POST /schoolyears` - Create school year
- `PUT /schoolyears/{id}` - Update school year
- `DELETE /schoolyears/{id}` - Delete school year
- `POST /schoolyears/{id}/activate` - Activate school year
- `POST /schoolyears/{id}/deactivate` - Deactivate school year

## Features by User Role

### Student Dashboard Features
- **Home View**: Overview of personal progress, latest activities
- **Document Management**: View and submit required documents
- **Attendance Tracking**: Record daily attendance with time stamps
- **Journal Submission**: Submit weekly internship journals
- **Profile Management**: Update personal information and avatar

### Coordinator Dashboard Features
- **Student Overview**: View all students in assigned course
- **Document Oversight**: Monitor student document completion
- **Attendance Monitoring**: Track student attendance by date/class block
- **Journal Review**: Review and provide feedback on student journals
- **HTE Management**: Manage host training establishments
- **Student Placement**: Assign students to HTEs
- **Report Generation**: Export attendance and progress reports

### Admin Dashboard Features
- **User Management**: Create and manage all user accounts
- **School Year Management**: Configure academic periods
- **System Configuration**: Global system settings
- **Role Assignment**: Assign roles to users
- **System Monitoring**: Overview of system usage

## System Architecture

### Frontend Architecture
- **Vue.js Components**: Modular component-based architecture
- **Inertia.js**: Seamless SPA experience with server-side routing
- **Tailwind CSS**: Utility-first CSS framework
- **Responsive Design**: Mobile-first responsive layout
- **State Management**: Vue.js reactive state management

### Backend Architecture
- **MVC Pattern**: Model-View-Controller architecture
- **Eloquent ORM**: Database abstraction layer
- **Middleware**: Request filtering and authentication
- **Service Layer**: Business logic separation
- **Repository Pattern**: Data access abstraction

### Security Features
- **Authentication**: Laravel Breeze with Sanctum
- **Authorization**: Role-based access control
- **CSRF Protection**: Cross-site request forgery protection
- **Input Validation**: Comprehensive form validation
- **File Upload Security**: Secure file handling

## Installation and Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Installation Steps
1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd sip-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

