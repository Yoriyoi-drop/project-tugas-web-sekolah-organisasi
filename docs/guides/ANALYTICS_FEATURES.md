# 📊 Analytics & Reporting Features

## 📋 Overview

Sistem analytics dan reporting yang komprehensif untuk monitoring dan evaluasi performa organisasi sekolah. Sistem ini menyediakan:

- ✅ **Analytics Dashboard** - Real-time metrics dan trends
- ✅ **Performance Metrics** - Scoring system dengan grade A-F
- ✅ **Reporting System** - Auto-generated reports dengan multiple formats
- ✅ **Data Export** - PDF, Excel, CSV export capabilities
- ✅ **Comparison Tools** - Organization-to-organization comparison
- ✅ **Trend Analysis** - Historical data tracking

## 🗄️ Database Schema

### Tables Created:

1. **`organization_analytics`** - Daily analytics data
   - `organization_id`, `date` - Primary keys
   - **Membership Metrics** - total_members, active_members, new_members, member_growth_rate
   - **Activity Metrics** - total_activities, upcoming_activities, completed_activities, total_participants, attendance_rate
   - **Engagement Metrics** - total_discussions, discussion_replies, total_announcements, total_notifications, read_notifications
   - **Performance Scores** - activity_score, engagement_score, overall_score (0-100)

2. **`activity_analytics`** - Activity-specific analytics
   - `activity_id`, `date` - Primary keys
   - **Registration Metrics** - total_registrations, new_registrations, confirmed_registrations, cancelled_registrations
   - **Engagement Metrics** - views, unique_views, shares, likes
   - **Performance Metrics** - registration_rate, cancellation_rate, engagement_rate

3. **`member_analytics`** - Individual member performance
   - `member_id`, `date` - Primary keys
   - **Participation Metrics** - activities_registered, activities_attended, activities_completed
   - **Engagement Metrics** - discussions_started, discussion_replies, announcements_read, notifications_read
   - **Performance Scores** - attendance_rate, engagement_score, participation_score

4. **`performance_metrics`** - Overall performance evaluation
   - `organization_id`, `period_id`, `date` - Context
   - **Membership Performance** - member_retention_rate, member_acquisition_rate, member_satisfaction_score
   - **Activity Performance** - activity_completion_rate, average_participation_rate, activity_satisfaction_score
   - **Financial Performance** - budget_utilization, cost_per_member, roi_score
   - **Engagement Performance** - discussion_engagement_rate, announcement_read_rate, notification_effectiveness
   - **Overall Performance** - overall_performance_score, performance_grade, growth_rate, benchmark_score

5. **`reports`** - Generated reports management
   - `organization_id`, `created_by` - Ownership
   - **Report Details** - title, description, type, status, format
   - **Configuration** - filters, parameters
   - **File Management** - file_path, file_name, file_size, generated_at, expires_at
   - **Usage Tracking** - download_count

6. **`report_templates`** - Reusable report templates
   - **Template Configuration** - sections, fields, filters, formatting
   - **Metadata** - is_active, is_default, created_by

7. **`benchmark_data`** - Industry benchmarks
   - **Benchmark Values** - benchmark_value, percentiles (25th, 50th, 75th, 90th)
   - **Context** - organization_type, metric_name, benchmark_date, sample_size

8. **`kpi_targets`** - Key Performance Indicator targets
   - **Target Details** - kpi_name, description, category, target_value, current_value
   - **Status Tracking** - status, achievement_percentage
   - **Timeline** - target_start_date, target_end_date, set_by

## 🎨 Models & Relationships

### OrganizationAnalytics Model
```php
// Key relationships
$analytics->organization()

// Scopes for filtering
OrganizationAnalytics::forOrganization($orgId)
OrganizationAnalytics::dateRange($start, $end)
OrganizationAnalytics::recent($days)
OrganizationAnalytics::byMonth($year, $month)

// Score calculation
$analytics->calculateScores() // Returns activity, engagement, membership, overall scores

// Static methods
OrganizationAnalytics::generateAnalytics($orgId, $date)
OrganizationAnalytics::getTrends($orgId, $days)
OrganizationAnalytics::getComparison($orgId, $period1, $period2)
```

### PerformanceMetric Model
```php
// Key relationships
$metric->organization()
$metric->period()

// Scopes for filtering
PerformanceMetric::forOrganization($orgId)
PerformanceMetric::highPerforming() // Grade A, B
PerformanceMetric::lowPerforming()  // Grade D, F

// Grade calculation
$metric->calculateGrade() // A-F based on overall score
$metric->calculateOverallScore() // Weighted scoring algorithm

// Benchmark comparison
$metric->compareWithBenchmark() // Percentile ranking

// Static methods
PerformanceMetric::generateMetrics($orgId, $date)
```

### Report Model
```php
// Key relationships
$report->organization()
$report->creator()

// Scopes for filtering
Report::byOrganization($orgId)
Report::byType('membership')
Report::completed()
Report::notExpired()

// File operations
$report->generate() // Auto-generate report
$report->download() // Download with count tracking
$report->deleteFile() // Clean up file storage

// Static factory methods
Report::generateMembershipReport($orgId, $filters, $userId)
Report::generateActivityReport($orgId, $filters, $userId)
Report::generateEngagementReport($orgId, $filters, $userId)
Report::generatePerformanceReport($orgId, $filters, $userId)
```

## 🎯 Features Implemented

### 1. Analytics Dashboard
- **Overview Cards** - Key metrics at glance
- **Growth Trends** - 30-day trend charts
- **Top Performers** - Best performing organizations
- **Recent Activity** - Latest discussions, activities, announcements
- **Organization Overview** - Quick stats table

### 2. Performance Metrics System
- **Scoring Algorithm** - Weighted scoring (Activity 40%, Engagement 40%, Membership 20%)
- **Grade System** - A (90-100), B (80-89), C (70-79), D (60-69), F (<60)
- **Benchmark Comparison** - Percentile ranking vs similar organizations
- **Growth Tracking** - Month-over-month growth rates
- **KPI Targets** - Customizable performance targets

### 3. Reporting System
- **Report Types** - Membership, Activity, Engagement, Performance, Custom
- **Multiple Formats** - PDF, Excel, CSV export
- **Auto-generation** - Scheduled and on-demand reports
- **Filtering Options** - Date ranges, organizations, metrics
- **Usage Tracking** - Download counts and expiry management

### 4. Data Export Capabilities
- **PDF Reports** - Formatted reports with charts and tables
- **Excel Export** - Raw data with formulas and formatting
- **CSV Export** - Comma-separated values for data analysis
- **Template System** - Reusable report templates
- **File Management** - Automatic cleanup and storage management

### 5. Comparison Tools
- **Organization Comparison** - Side-by-side performance comparison
- **Period Comparison** - Year-over-year, month-over-month analysis
- **Benchmark Analysis** - Industry standards and percentile rankings
- **Trend Analysis** - Historical performance tracking

## 📊 Scoring Algorithms

### Activity Score (40% weight)
- **Activity Completion (30 points)** - Completed vs total activities
- **Attendance Rate (30 points)** - Participant attendance percentage
- **Participation Rate (20 points)** - Member participation in activities
- **Activity Variety (20 points)** - Diversity of activity types

### Engagement Score (40% weight)
- **Discussion Engagement (30 points)** - Replies per discussion ratio
- **Announcement Effectiveness (30 points)** - Read rate percentage
- **Content Creation (20 points)** - Discussions and announcements created
- **Member Engagement (20 points)** - Engagement per member ratio

### Membership Score (20% weight)
- **Member Growth (40 points)** - New member acquisition rate
- **Member Retention (30 points)** - Active member retention percentage
- **Member Acquisition (30 points)** - New member vs total ratio

### Overall Performance Score
- **Weighted Average** - Activity (40%) + Engagement (40%) + Membership (20%)
- **Grade Assignment** - A (90+), B (80-89), C (70-79), D (60-69), F (<60)

## 🎨 Frontend Integration

### Analytics Dashboard (`/admin/analytics`)
- **Overview Cards** - Real-time statistics
- **Growth Trends Chart** - Interactive Chart.js visualization
- **Top Organizations** - Performance ranking
- **Recent Activity Feed** - Latest system activity
- **Organization Overview Table** - Quick stats

### Reports Management (`/admin/analytics/reports`)
- **Report Statistics** - Total, completed, pending, downloads
- **Reports Table** - Searchable, filterable list
- **Generate Modal** - New report creation form
- **Download Management** - File access and tracking

### Performance Metrics (`/admin/analytics/performance`)
- **Grade Distribution Chart** - Doughnut chart visualization
- **Performance Summary** - Key statistics
- **Top/Low Performers** - Best and worst performers
- **Detailed Metrics Table** - Comprehensive data view

### Comparison Tools (`/admin/analytics/compare`)
- **Organization Selection** - Multi-select comparison
- **Date Range Selection** - Custom time periods
- **Side-by-Side Comparison** - Performance metrics comparison
- **Visual Comparison** - Charts and graphs

## 📈 Data Visualization

### Chart Types Used
- **Line Charts** - Growth trends over time
- **Doughnut Charts** - Grade distribution
- **Bar Charts** - Performance comparisons
- **Progress Bars** - Score visualization
- **Badges** - Status indicators

### Interactive Features
- **Real-time Updates** - Auto-refresh data
- **Filter Controls** - Dynamic filtering
- **Search Functionality** - Text-based search
- **Export Options** - Download capabilities
- **Responsive Design** - Mobile-friendly layouts

## 🔄 Data Generation & Updates

### Automated Analytics Generation
```php
// Daily analytics generation
OrganizationAnalytics::generateAnalytics($orgId, $date);

// Performance metrics calculation
PerformanceMetric::generateMetrics($orgId, $date);
```

### Score Calculation Process
1. **Collect Raw Data** - Members, activities, discussions, announcements
2. **Calculate Rates** - Percentages and ratios
3. **Apply Scoring** - Category-specific scoring algorithms
4. **Weight Scores** - Apply category weights
5. **Assign Grades** - Convert scores to letter grades
6. **Update Benchmarks** - Compare with industry standards

## 📊 Seeder Data

### AnalyticsSeeder Features
- **30-Day History** - Realistic historical data
- **Growth Simulation** - Progressive improvement over time
- **Score Variation** - Realistic performance ranges
- **Sample Reports** - Pre-generated reports for testing
- **Benchmark Data** - Industry standard values

### Generated Data Types
- **Organization Analytics** - Daily metrics for all organizations
- **Performance Metrics** - Monthly performance scores
- **Sample Reports** - Various types and formats
- **Global Reports** - System-wide analytics reports

## 🛠️ Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Run Seeders
```bash
php artisan db:seed --class=AnalyticsSeeder
```

### 3. Clear Caches
```bash
php artisan cache:clear
php artisan view:clear
```

### 4. Schedule Analytics Generation
```php
// Add to app/Console/Kernel.php
$schedule->command('analytics:generate')->daily();
```

## 📝 Usage Examples

### Generate Organization Analytics
```php
$analytics = OrganizationAnalytics::generateAnalytics($orgId);
$scores = $analytics->calculateScores();
```

### Create Performance Report
```php
$report = Report::generatePerformanceReport($orgId, [
    'start_date' => '2024-01-01',
    'end_date' => '2024-12-31'
], $userId);
```

### Compare Organizations
```php
$comparison = OrganizationAnalytics::getComparison($orgId, $period1, $period2);
```

### Get Performance Trends
```php
$trends = OrganizationAnalytics::getTrends($orgId, 30);
```

## 🔍 Monitoring & Maintenance

### Automated Cleanup
```php
// Clean up expired reports
Report::cleanupExpired();

// Clean up old analytics (keep 1 year)
OrganizationAnalytics::where('date', '<', now()->subYear())->delete();
```

### Performance Monitoring
- **Query Optimization** - Indexed queries for large datasets
- **Cache Strategy** - Redis caching for frequently accessed data
- **Background Jobs** - Async report generation
- **Storage Management** - Automatic file cleanup

## 🚀 Future Enhancements

### Advanced Analytics
- **Predictive Analytics** - ML-based performance prediction
- **Sentiment Analysis** - Text analysis for discussions
- **Network Analysis** - Member interaction patterns
- **Custom KPIs** - User-defined metrics

### Enhanced Reporting
- **Interactive Dashboards** - Real-time data visualization
- **Scheduled Reports** - Automatic report delivery
- **API Integration** - External data sources
- **Mobile Reports** - Optimized mobile experience

### Performance Improvements
- **Real-time Updates** - WebSocket integration
- **Data Warehousing** - Optimized analytics storage
- **Advanced Caching** - Multi-level caching strategy
- **Query Optimization** - Database performance tuning

---

**Status**: ✅ **Priority 3 Complete** - Analytics & Reporting Fully Implemented

Sistem analytics sekarang menyediakan monitoring performa yang komprehensif dengan scoring algorithms, reporting capabilities, dan visualization tools untuk evaluasi organisasi sekolah yang data-driven.
