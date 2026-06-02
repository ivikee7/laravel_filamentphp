# Student Admin Panel - Form & Infolist Schemas

This document provides an overview of all the schema classes created for the Student admin panel resources in the Filament PHP application.

## Directory Structure

```
app/Filament/Student/
├── Resources/
│   ├── MyCourseResource/
│   │   ├── Schemas/
│   │   │   └── CourseInfolist.php
│   │   ├── Tables/
│   │   └── Pages/
│   ├── MyExamResource/
│   │   ├── Schemas/
│   │   │   └── ExamInfolist.php
│   │   ├── Tables/
│   │   └── Pages/
│   └── Schemas/
│       ├── StudentProfileInfolist.php
│       ├── EnrollmentInfolist.php
│       ├── AttendanceInfolist.php
│       ├── ExamResultInfolist.php
│       ├── CourseLessonInfolist.php
│       └── CourseMaterialInfolist.php
└── Resources/Threads/Schemas/
    └── ConversationInfolist.php
```

## Schema Classes

### 1. **CourseInfolist** 
**Location:** `app/Filament/Student/Resources/MyCourseResource/Schemas/CourseInfolist.php`

Displays comprehensive course information for students viewing their enrolled courses.

**Sections:**
- **Course Overview**: Course title, code, thumbnail image, subject, and description
- **Course Information**: Instructor, academic year, status, and course status badge
- **Course Content**: Lesson count, course materials count, and associated exams
- **Capacity Information**: Enrolled students, total capacity, and usage percentage
- **Metadata**: Creation and update timestamps

**Usage:**
```php
use App\Filament\Student\Resources\MyCourseResource\Schemas\CourseInfolist;

public static function infolist(Schema $schema): Schema
{
    return CourseInfolist::configure($schema);
}
```

### 2. **ExamInfolist**
**Location:** `app/Filament/Student/Resources/MyExamResource/Schemas/ExamInfolist.php`

Displays detailed exam information including schedule, marking criteria, and attempt rules.

**Sections:**
- **Exam Overview**: Exam title, description, and details
- **Exam Details**: Course, exam type, academic year, and publication status
- **Exam Schedule**: Exam date, start time, and duration
- **Marking & Performance**: Total marks, passing marks, and question count
- **Exam Rules & Attempts**: Maximum attempts allowed and exam instructions
- **Metadata**: Creation and update timestamps

**Usage:**
```php
use App\Filament\Student\Resources\MyExamResource\Schemas\ExamInfolist;

public static function infolist(Schema $schema): Schema
{
    return ExamInfolist::configure($schema);
}
```

### 3. **StudentProfileInfolist**
**Location:** `app/Filament/Student/Resources/Schemas/StudentProfileInfolist.php`

Complete student profile information including personal, academic, and contact details.

**Sections:**
- **Personal Information**: Avatar, name, email, account status, DOB, gender, blood group
- **Academic Information**: Current academic year, class, section, quota, admission number
- **Contact Information**: Primary/secondary contact numbers, email, address details
- **Parents/Guardian Information**: Father's name, mother's name, local guardian details
- **Enrollment Status**: Current status, TC status, leaving date, exit reason
- **GSuite Account**: GSuite email and password (collapsed)
- **Record Metadata**: Created by, updated by, timestamps (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\StudentProfileInfolist;

public static function infolist(Schema $schema): Schema
{
    return StudentProfileInfolist::configure($schema);
}
```

### 4. **EnrollmentInfolist**
**Location:** `app/Filament/Student/Resources/Schemas/EnrollmentInfolist.php`

Displays course enrollment details and progress tracking.

**Sections:**
- **Enrollment Details**: Course thumbnail, title, code, enrollment status, instructor, subject
- **Course Progress**: Progress percentage, completion status, lesson/material counts
- **Enrollment Timeline**: Enrollment date, start date, completion date, last update
- **Course Information**: Detailed course description (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\EnrollmentInfolist;

public static function infolist(Schema $schema): Schema
{
    return EnrollmentInfolist::configure($schema);
}
```

### 5. **AttendanceInfolist**
**Location:** `app/Filament/Student/Resources/Schemas\AttendanceInfolist.php`

Shows individual attendance record with status and remarks.

**Sections:**
- **Attendance Record**: Class, section, subject, and attendance date
- **Attendance Status**: Status badge (present/absent/late/excused), remarks
- **Additional Information**: Teacher name, academic year, recording timestamp (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\AttendanceInfolist;

public static function infolist(Schema $schema): Schema
{
    return AttendanceInfolist::configure($schema);
}
```

### 6. **ExamResultInfolist**
**Location:** `app/Filament\Student\Resources\Schemas\ExamResultInfolist.php`

Comprehensive exam result display with performance metrics.

**Sections:**
- **Exam Details**: Exam title, course, type, academic year
- **Exam Schedule**: Exam date, start time, duration, attempt number
- **Marks & Performance**: Obtained marks, total marks, passing marks, percentage
- **Result Status**: Status badge, grade, feedback
- **Submission Information**: Submission date, grading date, timestamps (collapsed)
- **Question Performance**: Total questions, correct/incorrect/unanswered counts (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\ExamResultInfolist;

public static function infolist(Schema $schema): Schema
{
    return ExamResultInfolist::configure($schema);
}
```

### 7. **CourseLessonInfolist**
**Location:** `app/Filament/Student/Resources/Schemas/CourseLessonInfolist.php`

Displays individual lesson content and learning information.

**Sections:**
- **Lesson Overview**: Title, course, lesson number, sequence order
- **Lesson Content**: Description, detailed lesson content, learning objectives
- **Learning Resources**: Learning objectives, estimated duration, skill level
- **Lesson Status**: Publication status, active status, content availability
- **Additional Resources**: Material count, external resource URL
- **Metadata**: Creation and update timestamps (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\CourseLessonInfolist;

public static function infolist(Schema $schema): Schema
{
    return CourseLessonInfolist::configure($schema);
}
```

### 8. **CourseMaterialInfolist**
**Location:** `app/Filament/Student/Resources/Schemas\CourseMaterialInfolist.php`

Shows course material details including file information and learning outcomes.

**Sections:**
- **Material Information**: Title, course, related lesson, material type
- **Material Description**: Detailed description with HTML support
- **File Information**: File path, size, type, download count (conditional)
- **External Resource**: Resource URL, platform (conditional)
- **Material Status**: Publication status, required flag, sequence, difficulty level
- **Learning Information**: Learning outcomes, estimated learning time (collapsed)
- **Metadata**: Creation and update timestamps (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Schemas\CourseMaterialInfolist;

public static function infolist(Schema $schema): Schema
{
    return CourseMaterialInfolist::configure($schema);
}
```

### 9. **ConversationInfolist**
**Location:** `app/Filament/Student/Resources/Threads/Schemas/ConversationInfolist.php`

Displays conversation/thread information for student discussions.

**Sections:**
- **Conversation Details**: Subject, description of the conversation
- **Participants**: Number of participants, conversation starter
- **Conversation Activity**: Message count, last message timestamp, active status
- **Conversation Timeline**: Creation date, last update (collapsed)

**Usage:**
```php
use App\Filament\Student\Resources\Threads\Schemas\ConversationInfolist;

public static function infolist(Schema $schema): Schema
{
    return ConversationInfolist::configure($schema);
}
```

## Common Features Across Schemas

### 1. **Modular Structure**
All schemas use the `configure()` static method pattern for reusability:
```php
public static function configure(Schema $schema): Schema
{
    return $schema->components([...]);
}
```

### 2. **Color Coding & Badges**
Status fields are displayed as colored badges for quick visual identification:
- `success` - Active/Published/Passed states
- `danger` - Absent/Failed/Suspended states
- `warning` - Draft/Late/Pending states
- `info` - Informational/Completed states
- `gray` - Default/Unknown states

### 3. **Conditional Visibility**
Some sections are conditionally visible based on data:
```php
->visible(fn(Model $record) => condition)
```

### 4. **Collapsed Sections**
Metadata and less important sections use the `collapsed()` modifier to reduce clutter:
```php
->collapsed()
```

### 5. **Rich Content Support**
Descriptions and content fields support HTML rendering:
```php
->html()
```

### 6. **Responsive Grid Layout**
All schemas use responsive grid columns (typically 2-4 columns) for better layouts on different screen sizes.

### 7. **Copyable Fields**
Email and URL fields are copyable for user convenience:
```php
->copyable()
```

## Integration with Resources

### Example: Integrating with MyCourseResource
```php
class MyCourseResource extends Resource
{
    protected static ?string $model = Course::class;

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    // Other methods...
}
```

### Example: Using in View Pages
```php
class ViewMyCourse extends ViewRecord
{
    protected static string $resource = MyCourseResource::class;
    
    // The infolist schema will automatically be used in the view
}
```

## Best Practices

### 1. **Consistency**
All schemas follow the same structural pattern with similar section naming conventions.

### 2. **Performance**
Schemas use relationship loading (`with()`) to minimize N+1 queries.

### 3. **User Experience**
- Important information is displayed first
- Metadata and less critical info is collapsed
- All status fields use visual indicators (badges, colors)

### 4. **Accessibility**
- All entries have proper labels
- Color coding includes text values for colorblind users
- HTML content is properly escaped

### 5. **Internationalization**
Future i18n support can be added by:
```php
->label(__('key'))
```

## Adding New Schemas

To create a new schema:

1. Create a new file in the appropriate Schemas directory
2. Create a class that implements the `configure(Schema $schema)` static method
3. Add components using Filament's component structure
4. Use consistent naming and section organization

### Template:
```php
<?php

namespace App\Filament\Student\Resources\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class YourNewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section Title')
                    ->schema([
                        TextEntry::make('field_name')
                            ->label('Display Label'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
```

## Future Enhancements

- [ ] Add form schemas for editable student information
- [ ] Implement conditional form fields based on user roles
- [ ] Add validation schemas
- [ ] Create action buttons within infolists
- [ ] Add export functionality
- [ ] Implement dynamic field visibility based on permissions

## Related Documentation

- [Filament Forms Documentation](https://filamentphp.com/docs/3.x/forms)
- [Filament Infolists Documentation](https://filamentphp.com/docs/3.x/infolists)
- [Filament Schemas Documentation](https://filamentphp.com/docs/3.x/schemas)

## Support

For questions or issues with these schemas, please refer to the main Filament documentation or contact the development team.

