# Better ERP – Product Requirements Document (PRD)

> **Version:** 1.0  
> **Status:** Draft  
> **Type:** General Purpose ERP Starter Kit

---

# 1. Overview

Better ERP is a **multi-tenant**, **modular**, and **API-first** Enterprise Resource Planning (ERP) platform designed to serve multiple companies from a single application.

The primary goal is to build a reusable ERP foundation that can be used as a starter kit for future ERP projects. Organizations can enable only the modules they require while sharing a common authentication, authorization, administration, and configuration system.

The platform is designed with scalability, maintainability, and extensibility in mind, allowing new business modules to be added without affecting existing functionality.

---

# 2. Goals

- Build a reusable ERP starter kit
- Support multiple companies from a single application
- Modular architecture
- API-first development
- Enterprise-grade Role & Permission system
- Easy to extend with new modules
- Easy to maintain
- Modern frontend experience
- High performance and scalability

---

# 3. Non Goals

The following modules are outside the scope of the initial release:

- Accounting
- CRM
- Inventory
- POS
- Asset Management

These modules can be added later without modifying the core system.

---

# 4. Technology Stack

## Backend

- Laravel
- REST API
- MySQL / PostgreSQL
- Laravel Tenancy
- Spatie Laravel Permission
- Spatie Laravel Media Library
- Laravel Queue
- Laravel Scheduler
- Laravel Notifications

## Frontend

- Next.js
- TypeScript
- React Query
- Axios
- Tailwind CSS
- shadcn/ui
- React Hook Form
- Zod

---

# 5. Core Principles

- API First
- Multi Tenant
- Modular
- Scalable
- Secure
- Reusable
- Extensible
- Maintainable

---

# 6. System Architecture

```text
Next.js Frontend
        │
        ▼
 Laravel REST API
        │
        ▼
 Core ERP Platform
    ├── Authentication
    ├── Authorization
    ├── Company Management
    ├── User Management
    ├── Employee Management
    ├── Leave Management
    ├── Attendance
    ├── Payroll
    ├── Recruitment
    ├── Notification
    ├── Media
    └── Settings
        │
        ▼
    Database
```

---

# 7. Multi-Tenant Architecture

The platform serves multiple companies using a single application.

Each company has isolated business data while sharing the same application infrastructure.

## Features

- Unlimited companies
- Shared application
- Shared codebase
- Company-based data isolation
- Tenant-aware APIs
- Tenant-aware permissions
- Shared global configuration

---

# 8. User Types

## Super Admin

Platform administrator.

Scope:

- Global

Responsibilities:

- Manage all companies
- Manage subscriptions
- Manage global settings
- Manage platform modules
- Manage global roles
- Manage platform users
- Access every company

---

## Company Admin

Company administrator.

A company admin may manage one or more companies.

Scope:

- Company

Responsibilities:

- Manage employees
- Manage company settings
- Configure company modules
- Assign company roles
- Configure leave policies
- Configure attendance
- Configure payroll
- View reports

---

## Manager

Department or Team Manager.

Scope:

- Department

Responsibilities:

- Approve leave
- Approve attendance corrections
- View team reports
- Manage team members

---

## Employee

Regular employee.

Scope:

- Self

Responsibilities:

- View profile
- Attendance
- Apply for leave
- View payslips
- Update personal information

---

# 9. Role Hierarchy

```text
Super Admin
    │
    ├── Company Admin
            │
            ├── Manager
                    │
                    └── Employee
```

---

# 10. Access Control (RBAC)

The system uses **Spatie Laravel Permission**.

There are two permission scopes:

## Global Roles

Applicable across the entire platform.

Examples:

- Super Admin
- Platform Support
- Platform Auditor

---

## Company Roles

Applicable only within assigned companies.

Examples:

- Company Admin
- HR
- Finance
- Manager
- Employee

---

## Permission Examples

```text
employee.view
employee.create
employee.update
employee.delete

leave.view
leave.create
leave.approve
leave.reject

attendance.view
attendance.edit

payroll.generate
payroll.approve

recruitment.manage
```

---

# 11. Core Modules

## User Access Management (UAM)

Features

- Authentication
- Authorization
- Users
- Roles
- Permissions
- Company Assignment
- Multi-company Access
- Session Management

---

## Company Management

Features

- Company Profile
- Company Settings
- Timezone
- Currency
- Fiscal Year
- Branding
- Holiday Calendar

---

## Employee Management

Features

- Employee Profile
- Employment Information
- Documents
- Contact Information
- Emergency Contact
- Organization Structure

---

## Leave Management

Features

- Leave Types
- Leave Policies
- Leave Requests
- Approval Workflow
- Leave Balance
- Carry Forward
- Leave Encashment

---

## Attendance

Features

- Check In
- Check Out
- Shift Management
- Roster
- Attendance Adjustment
- Late Rules
- Overtime

---

## Payroll

Features

- Salary Structure
- Payroll Generation
- Allowances
- Deductions
- Bonus
- Tax
- Payslip

---

## Recruitment

Features

- Job Posts
- Candidates
- Interview Management
- Interview Panel
- Hiring Pipeline
- Offer Letter

---

## Notification

Features

- Email Notifications
- In-App Notifications
- SMS (Future)

---

## Media Management

Powered by **Spatie Laravel Media Library**

Features

- Employee Documents
- Company Logo
- Images
- Attachments
- File Storage

---

# 12. Common Module Features

Every module should support:

- CRUD Operations
- Search
- Filtering
- Sorting
- Pagination
- Import
- Export
- Audit Logs
- Activity History
- Attachments
- Notes
- Comments
- Status Management
- Soft Delete

---

# 13. Modular Architecture

Each module is independent.

```text
Modules/

├── Employee
├── Attendance
├── Leave
├── Payroll
├── Recruitment
├── Training
├── Inventory
├── CRM
```

Every module contains:

- Routes
- Controllers
- Requests
- Services
- Repositories (optional)
- Policies
- Models
- Resources
- Migrations
- Seeders
- Tests

---

# 14. Authentication

Features

- Login
- Logout
- Forgot Password
- Reset Password
- Change Password
- Multi-company Selection
- Remember Me

Future:

- MFA
- Social Login
- SSO

---

# 15. API Design

RESTful API

```text
/api/v1/auth

/api/v1/users

/api/v1/companies

/api/v1/employees

/api/v1/leaves

/api/v1/attendance

/api/v1/payroll

/api/v1/recruitment
```

---

# 16. Frontend Principles

- Responsive Design
- Mobile Friendly
- Dark Mode
- Reusable Components
- Server State with React Query
- Form Validation with Zod
- Consistent UI using shadcn/ui

---

# 17. Security

- Role-Based Access Control (RBAC)
- Tenant Isolation
- API Authentication
- Rate Limiting
- Secure Password Hashing
- Audit Logs
- Sensitive Data Encryption

---

# 18. Future Modules

- Accounting
- Inventory
- CRM
- Procurement
- Asset Management
- Project Management
- Fleet Management
- Visitor Management
- Learning Management
- Performance Management
- Travel Management
- Help Desk
- Document Management

---

# 19. Success Criteria

- Support unlimited companies from one deployment
- Fully modular architecture
- Consistent RBAC implementation
- Reusable for future ERP projects
- API-first architecture
- Easy to onboard new modules
- Enterprise-ready foundation