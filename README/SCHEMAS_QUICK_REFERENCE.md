# Student Schemas - Quick Reference Guide

## Quick Start

### Using CourseInfolist in MyCourseResource

```php
<?php

namespace App\Filament\Student\Resources\MyCourseResource;

use App\Filament\Student\Resources\MyCourseResource\Schemas\CourseInfolist;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use App\Models\Course;

class MyCourseResource extends Resource
{
    protected static ?string $model = Course::class;

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }
}
```

### Using ExamInfolist in MyExamResource

```php
<?php

namespace App\Filament\Student\Resources\MyExamResource;

use App\Filament\Student\Resources\MyExamResource\Schemas\ExamInfolist;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use App\Models\Exam;

class MyExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    public static function infolist(Schema $schema): Schema
    {
        return ExamInfolist::configure($schema);
    }
}
```

---

## Common Patterns

### Pattern 1: Display-Only Infolist

For read-only views showing resource details:

```php
public static function infolist(Schema $schema): Schema
{
    return YourSchemaClass::configure($schema);
}
```

**Use in:**
- View pages
- Show modals
- Dashboards

---

### Pattern 2: Combining With Tables

Display a table list with infolist detail view:

```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListCourses::route('/'),
        'view' => Pages\ViewCourse::route('/{record}'),
    ];
}
```

When user clicks a row in the table, they see the infolist in the view page.

---

### Pattern 3: Conditional Sections

Make sections visible based on data:

```php
Section::make('Section Title')
    ->schema([...])
    ->visible(fn($record) => $record->some_condition)
```

**Example:**
```php
Section::make('Capacity Information')
    ->visible(fn($record) => $record->max_students !== null)
```

---

### Pattern 4: Collapsible Sections

Hide metadata by default to reduce clutter:

```php
Section::make('Metadata')
    ->schema([...])
    ->collapsed()
```

---

### Pattern 5: Color-Coded Badges

Highlight status information:

```php
TextEntry::make('status')
    ->badge()
    ->color(fn(string $state): string => match ($state) {
        'active' => 'success',
        'inactive' => 'danger',
        'pending' => 'warning',
        default => 'gray',
    })
    ->formatStateUsing(fn(string $state): string => ucfirst($state))
```

---

## Customization Examples

### Example 1: Add Custom Sections to CourseInfolist

```php
// In your custom schema or resource
use App\Filament\Student\Resources\MyCourseResource\Schemas\CourseInfolist;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

public static function infolist(Schema $schema): Schema
{
    $baseSchema = CourseInfolist::configure($schema);
    
    // Add custom section
    return $baseSchema->components([
        // ... existing components from CourseInfolist ...
        Section::make('Instructor Bio')
            ->schema([
                TextEntry::make('instructor.bio')
                    ->html(),
            ]),
    ]);
}
```

### Example 2: Filter Related Records

```php
// In StudentProfileInfolist or custom resource
TextEntry::make('enrollments')
    ->label('Active Enrollments')
    ->formatStateUsing(fn($record) => 
        $record->enrollments()
            ->where('status', 'active')
            ->count()
    )
```

### Example 3: Custom Formatting

```php
TextEntry::make('exam.total_marks')
    ->label('Total Marks')
    ->formatStateUsing(fn($state, $record) => 
        $state 
            ? "Out of " . round($state, 2) . " marks"
            : "Not specified"
    )
```

---

## Available Infolist Components

### Text & Display
- `TextEntry::make('field')` - Simple text display
- `TextEntry::make('field')->badge()` - Colored badge display
- `TextEntry::make('field')->html()` - HTML content support
- `TextEntry::make('field')->copyable()` - Copy to clipboard button

### Media
- `ImageEntry::make('field')` - Image display
- `ImageEntry::make('field')->circular()` - Circular image
- `ImageEntry::make('field')->square()` - Square image

### Layout
- `Section::make('title')` - Grouped section
- `Group::make()` - Flexible grouping
- `Grid::make()` - Grid layout

### Utilities
- `.label('Label')` - Custom label
- `.placeholder('N/A')` - Placeholder for empty values
- `.columnSpanFull()` - Full-width column
- `.columns(n)` - Number of columns
- `.collapsed()` - Initially collapsed
- `.visible(fn)` - Conditional visibility
- `.color('success')` - Color coding

---

## Data Type Formatting

### Date/DateTime Formatting

```php
// Date
TextEntry::make('date_field')->date()

// DateTime
TextEntry::make('timestamp_field')->dateTime()

// Custom format
TextEntry::make('date_field')
    ->dateTime('Y-m-d g:i A')
```

### Number Formatting

```php
// Integer
TextEntry::make('count')

// Decimal
TextEntry::make('marks')
    ->formatStateUsing(fn($state) => round($state, 2))

// Currency
TextEntry::make('price')
    ->formatStateUsing(fn($state) => '₹' . number_format($state, 2))

// Percentage
TextEntry::make('percentage')
    ->formatStateUsing(fn($state) => $state . '%')
```

---

## Related Resources

### Schemas Used Together

1. **Courses & Lessons**
   - `CourseInfolist` → `CourseLessonInfolist`
   - Show course, then its lessons

2. **Courses & Materials**
   - `CourseInfolist` → `CourseMaterialInfolist`
   - Show course, then its materials

3. **Exams & Results**
   - `ExamInfolist` → `ExamResultInfolist`
   - Show exam, then results

4. **Enrollments & Progress**
   - `EnrollmentInfolist` → `ExamResultInfolist`
   - Track course progress and exam performance

5. **Student Profile & Attendance**
   - `StudentProfileInfolist` → `AttendanceInfolist`
   - View student, then attendance history

---

## Common Issues & Solutions

### Issue: Related data not showing
**Solution:** Make sure relationships are eager-loaded in your query:
```php
->with(['relationship', 'nested.relationship'])
```

### Issue: Null values showing as blank
**Solution:** Use `placeholder()`:
```php
TextEntry::make('field')
    ->placeholder('Not specified')
```

### Issue: Sections too long
**Solution:** Use `collapsed()`:
```php
Section::make('Details')
    ->collapsed()
```

### Issue: Badge color not showing
**Solution:** Combine `badge()` with `color()`:
```php
TextEntry::make('status')
    ->badge()
    ->color(fn(string $state) => match($state) {
        'active' => 'success',
        default => 'gray',
    })
```

---

## Performance Tips

1. **Eager Load Relationships**
   ```php
   $record->load('relationship', 'nested.relationship');
   ```

2. **Use withCount() for Counts**
   ```php
   ->withCount(['enrollments', 'exams'])
   ```

3. **Avoid N+1 Queries**
   ```php
   public static function getEloquentQuery(): Builder
   {
       return parent::getEloquentQuery()
           ->with(['relationships', 'nested'])
           ->withCount(['counts']);
   }
   ```

---

## File Structure for New Schemas

When creating new schemas, follow this template:

```php
<?php

namespace App\Filament\Student\Resources\YourResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class YourNewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Section One')
                    ->schema([
                        TextEntry::make('field1'),
                        TextEntry::make('field2'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Section Two')
                    ->schema([
                        TextEntry::make('field3'),
                    ])
                    ->collapsed(),
            ]);
    }
}
```

---

## Directory Reference

Quick paths to all schema files:

```
student/Schemas/
├── StudentProfileInfolist ........... Student profile info
├── EnrollmentInfolist ............... Course enrollments
├── AttendanceInfolist ............... Attendance records
├── ExamResultInfolist ............... Exam results
├── CourseLessonInfolist ............. Individual lessons
└── CourseMaterialInfolist ........... Course materials

MyCourseResource/Schemas/
└── CourseInfolist ................... Course details

MyExamResource/Schemas/
└── ExamInfolist ..................... Exam details

Threads/Schemas/
└── ConversationInfolist ............. Discussions
```

---

## Testing Your Schemas

### Test 1: Load a Resource with Infolist

```php
// In your browser
// Visit: /admin/courses/1
// Should display CourseInfolist with all sections
```

### Test 2: Check Responsive Design

- View on desktop (4 columns)
- View on tablet (2 columns)
- View on mobile (1 column)

### Test 3: Verify Related Data

- Check that related records load properly
- Verify no 404 errors
- Confirm N+1 queries are minimized

---

## Getting Help

1. Check `STUDENT_SCHEMAS_GUIDE.md` for comprehensive documentation
2. Review existing schema files for patterns
3. Refer to [Filament Docs](https://filamentphp.com/docs)
4. Check the provided examples in this file

---

## Version Info

- **Created:** June 1, 2026
- **PHP Version:** 8.0+
- **Laravel Version:** 10.x+
- **Filament Version:** 3.x+
- **Status:** Production Ready ✓

