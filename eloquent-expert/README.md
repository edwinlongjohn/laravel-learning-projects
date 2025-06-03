## 1. make:model - Less-Known Possible Options

Summary of this lesson:

-   Understanding all available options for make:model command
-   Using interactive model creation prompts
-   Generating related files (migrations, controllers, etc.)
-   Creating resource controllers with automatic model binding

if you want to use artisan commands
You don't need to remember them. All available options can be checked by providing -h or --help to the artisan command.
here is a simple example
I think creating a Migration and Controller together with the Model is the most common.
the code **php artisan make:model -mc**
The resource Controller will be created if you provide the -r option.
In the Controller, we have seven methods, and to them, the Route Model Binding is injected automatically.

The same is if you create the Form Request with the resource Controller.
the code **php artisan make:model -mcR**
All Form Requests are injected automatically.

Or if you need everything using the option -a or --all, everything will be generated.

## 2. Singular or Plural Models? What about multiple words?

to change the conventional naming method use
class Role extends Model
{
protected $table = 'user_roles';
}

## 3. saving a model $fillable and $guarded

in the project

## 4. Model Properties: Tables, Keys, Increments, Pages and Dates

Summary of this lesson:

-   Customizing database table names and primary keys
-   Configuring auto-increment settings
-   Setting up pagination defaults
-   Managing timestamp properties and naming

class Task extends Model
{
protected $table = 'project_tasks';
}

Customize Primary Key
The next thing you can override is the primary key. By default, the primary key is id. But you may change it in the migration to, for example, task_id.

public function up(): void
{
Schema::create('tasks', function (Blueprint $table) {
$table->id('task_id');
$table->timestamps();
});
}

Then, in the Model, you must provide the $primaryKey.

class Task extends Model
{
protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

}

Customize Auto-Increments
What if you don't want it to be auto increment? Maybe you will set it up yourself manually with some logic. Then, you will set the $incrementing to false.

class Task extends Model
{
protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

}

And then, in your migration, probably, you should have unsignedBigInteger() without auto-increment, instead of id().

Or, if you want to use UUIDs or ULIDs specifically for that there is a trait

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Task extends Model
{
use HasUuids;

    protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

}

Customize Pagination
The next thing you can provide or override is a property called per page, which is, by default, 15. It would be used in all of your pagination requests. So, for example, we can set it to 10.

class Task extends Model
{
protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

    protected $perPage = 10;

}

Customize Timestamps: Don't Use Them
And a few things about timestamps. By default, in your migration, you have timestamps that are created_at and updated_at. You can refuse to use those.

You may have some other logic for timestamps or want to use something other than that. In your Model, you can set $timestamps to false.

class Task extends Model
{
protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

    protected $perPage = 10;

    public $timestamps = false;

}

Then you don't need the timestamps() in the Migration.

Customize Timestamps: Rename Them
In another case, the names of your timestamps are different. For example, if you have a database from an older project, not even Laravel, maybe you have not created the created_at and updated_at, but time_created and time_updated. Then, you can override the constants.
class Task extends Model
{
protected $table = 'project_tasks';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

    protected $perPage = 10;

    public $timestamps = false;

    const CREATED_AT = 'time_created';

    const UPDATED_AT = 'time_updated';

}
And, of course, then, in the migration, you don't use timestamps(). You create those fields manually.

Bonus: Quickly Check Table/Model Properties
If you made custom changes to your Model or DB Table, you may have forgotten about some of them. Laravel can help you to perform a quick check.

It has two artisan commands showing information about the database and specific database tables.

You can use the db:show artisan command to see information about database and tables. This command will show you general information about your database and all your migrated tables with their sizes.

Another artisan command is model:show, which shows information about a provided table. This command will show all the table's fields, relationships, and observers.

## 5. Customize Model Default Template with Stubs

Summary of this lesson:

-   Publishing and customizing model stubs
-   Modifying default model template structure
-   Removing default traits like HasFactory
-   Understanding stub customization options

The default Eloquent Model is generated with a structure as below.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
use HasFactory;
}

And it has a trait HasFactory. What if you want to remove it because you won't use it in the project and want all new Models not to have it?

You can overwrite the default structure by publishing stubs.
php artisan stub:publish
Now, you have a new folder /stubs at the root of your project. You can change more than a Model stub if your project needs it. The Model stub looks as below.
stubs/model.stub:


 
namespace {{ namespace }};
 
{{ factoryImport }}
use Illuminate\Database\Eloquent\Model;
 
class {{ class }} extends Model
{
    {{ factory }}
}

You can add and remove what you need from the stub.

stubs/model.stub:

<php
 
namespace {{ namespace }};
 
{{ factoryImport }} to be removed 
use Illuminate\Database\Eloquent\Model;
 
class {{ class }} extends Model
{
    {{ factory }}   to be removed
}
## 6. Model casts(): Dates, Enum and More
Summary of this lesson:
- Implementing attribute casting in models
- Working with datetime casts and Carbon
- Setting up Enum casts
- Understanding differences between Laravel 10 and 11 casting syntax

In your Eloquent Models, you can provide casts to automatically cast database columns to some type that you would need to use separately with that type of logic. Probably the most popular example is about date type.

In the User Model, by default, the email_verified_at is cast to datetime.
class User extends Authenticatable
{
    // ...
 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', 
            'password' => 'hashed',
        ];
    }
}

In Laravel 11, you can use either a method or a property, with method having higher importance.

Another example is if you use PHP Enum classes, you must cast the database column to that Enum.

For example, an Enum class with some levels in DB would be stored as an integer
app/Enums/UserLevel.php:
to make enums you use php artisan make:enums UserLevel

enum UserLevel: int
{
    case Junior = 1;
    case Mid = 2;
    case Senior = 3;
}

In the User Model, the field is casted to that enum class.

use App\Enums\UserLevel;
 
class User extends Authenticatable
{
    // ...
 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_level' => UserLevel::class, 
        ];
    }
}


## 7. FirstOrCreate, and Other 2-in-1 Methods

Example 2: firstOrNew()
Similar to the first example, but here the record isn't saved to the database. You may want to do some more magic or more operations with that.

use App\Models\User;
 
class HomeController extends Controller
{
    public function index()
    {
        $user = User::firstOrNew(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => 'password']
        );
 
        return $user->id;
    }
}

So, the result is the user object in the variable, but it still needs to be saved into the database.

Example 4: upsert()
And the final example is upsert(). If you have, for example, some Excel sheet with the updated data for the user and you don't want to do a foreach loop and update them automatically one by one. You want to have one sentence.

So imagine that the first two lines come with email, name, and password from the Excel sheet, and those should be the new values for the users.

use App\Models\User;
 
class HomeController extends Controller
{
    public function index()
    {
        User::upsert([
            ['email' => 'admin@admin.com', 'name' => 'Admin 1', 'password' => 'password'],
            ['email' => 'admin2@admin.com', 'name' => 'Admin 2', 'password' => 'password'],
        ], ['email'], ['name', 'password']);
    }
}

Now, how do we identify the users? We identify them by email and update name and password fields. For every line, it searches for the email and updates the record with values in the last parameter, name, and password.

So, these are helpful Eloquent two-in-one methods to help you avoid two or more sentences. Instead, doing something more compactly.

## 8. WasCreated, IsDirty and Other Checks If Model Was Changed

Summary of this lesson:
- Using wasRecentlyCreated() to check model status
- Implementing isDirty() for tracking changes
- Understanding wasChanged() for saved changes
- Comparing pre and post-save state detection

Example 1: wasRecentlyCreated()
So there is firstOrCreate(), which tries to find the first record by email or creates that with name and password.

use App\Models\User;
 
class HomeController extends Controller
{
    public function index()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => 'password']
        );
 
        dump($user->wasRecentlyCreated ? 'Created' : 'Found');
    }
}
And there's wasRecentlyCreated. This is a property, not a function.

And if you launch that code on an empty database the first time, it will say created because it created the object, but if you relaunch it the second time, it will say found because it didn't create the object during that second request.

Example 2: isDirty()
The second helpful method is isDirty(). If you change any Eloquent method property during the request before saving, you will launch user save at some point, but in the meantime, you've changed some properties.

Then, you can check whether some property or all properties were changed.

use App\Models\User;
 
class HomeController extends Controller
{
    public function index()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => 'password']
        );
 
        dump($user->wasRecentlyCreated ? 'Created' : 'Found');
 
        $user->name = 'Admin updated'; 
        dump($user->isDirty() ? 'Edited' : 'Unedited');
        dump($user->isDirty('name') ? 'Name edited' : 'Name not edited');
        dump($user->isDirty('email') ? 'Email Edited' : 'Email not edited'); 
    }
}

So, at the start, we have a name as "admin", and then we change the name. If you try to launch this code, the general isDirty() should return "Edited".

With the name field, it should return "Name edited", but with the email field, it should return "Email not edited".

Example 3: wasChanged()
The isDirty() method works before you save the data into the database.

If you want to check whether the object was changed and saved to the database, then there's a separate method wasChanged().

If you want to check whether the database has the newer data, you use wasChanged() with the same logic, either without parameters or with a parameter of a specific column.

use App\Models\User;
 
class HomeController extends Controller
{
    public function index()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => 'password']
        );
 
        dump($user->wasRecentlyCreated ? 'Created' : 'Found');
 
        $user->name = 'Admin updated';
        dump($user->isDirty() ? 'Edited' : 'Unedited');
        dump($user->isDirty('name') ? 'Name edited' : 'Name not edited');
        dump($user->isDirty('email') ? 'Email edited' : 'Email not edited');
 
        dump($user->wasChanged() ? 'Changed' : 'Unchanged'); 
        $user->save();
        dump($user->wasChanged() ? 'Changed' : 'Unchanged');
        dump($user->wasChanged('name') ? 'Name changed' : 'Name not changed');
        dump($user->wasChanged('email') ? 'Email changed' : 'Email not changed'); 
    }
}

Now, if you launch the code, at first, you will see it unchanged before the save call.

Then, when the save is called, you will see the change becomes true.

The name change becomes true because the name was changed, but the email wasn't changed.

## 9. Model Observers and Their Methods
Summary of this lesson:
- Creating and configuring Model Observers
- Understanding Observer lifecycle methods
- Registering Observers using different approaches
- Implementing pre and post-event Observer methods

If you want to perform some action when the Eloquent object is created or updated, it is usually done with the Observer class. It's like events and listeners in Laravel, but all the listeners related to the same Eloquent Model are grouped into one class called Observer.

Generate Observers
Observer can be generated using the Artisan command. Typically, you should prefix Observer with the Model name for the name. Additionally, you can pass --model option and provide the Model name.

php artisan make:observer UserObserver --model=User

The command will generate the UserObserver class in the app/Observers folder. And then, in that Observer class, you will find generated methods, created(), updated(), deleted(), restored(), and forcedDeleted(). The last two are for Soft Deletes.

<php
 
namespace App\Observers;
 
use App\Models\User;
 
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // ...
    }
 
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // ...
    }
 
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // ...
    }
 
    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        // ...
    }
 
    /**
     * Handle the User "forceDeleted" event.
     */
    public function forceDeleted(User $user): void
    {
        // ...
    }
}

For example, you can send a notification to someone, notify the admin that the user was created, or inform the user themselves with some welcome email.

In this example, I will log that the user was created with the email.

**app/Observers/UserObserver.php:**
use App\Models\User;
 
class UserObserver
{
    public function created(User $user): void
    {
        info('User was created: ' . $user->email); 
    }
 
    // ...
}

Register Observers
It's not enough to generate an Observer. We need to register that into the system. Registration can be done in two ways. The first one uses the PHP attribute ObservedBy on the Model.

app/Models/User.php:

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
#[ObservedBy(UserObserver::class)] 
class User extends Authenticatable
{
    // ...
}

Equivalent pre-PHP 8.0 code
Before attributes, this would typically be done in documentation blocks:
/**
 * @ObservedBy(UserObserver::class)
 */
class User { ... }

Or it can be registered in the AppServiceProvider.

app/Providers/AppServiceProvider.php:
use App\Models\User;
use App\Observers\UserObserver;
 
class AppServiceProvider extends ServiceProvider
{
    // ...
 
    public function boot(): void
    {
        User::observe(UserObserver::class); 
    }
}

In Laravel 10 and below, instead of AppServiceProvider, you would use EventServiceProvider.

If you create a user, there should be a new message in the logs.

storage/logs/laravel.log:
[2024-03-01 12:19:52] local.INFO: User was created: test@test.com

More Methods in Observers
In addition to the methods that are automatically generated when you run the artisan command, you can create methods for the events that Laravel also supports.

For example, created() and updated() are separate methods, but one method is saved(), which will be automatically launched in both cases in the created and updated.

But more useful are methods like creating(). So for all of those created(), updated(), and deleted(), which happen after the record is saved in the database, you can also define creating(), updating(), and deleting(), which would happen before the record is changed in the database. For these methods, the parameter is the same Model.

For example, if you want your user email to be verified automatically.

**app/Observers/UserObserver.php:**
use App\Models\User;
 
class UserObserver
{
    public function updating(User $user): void 
    {
        $user->email_verified_at = now();
    }
 
    public function created(User $user): void
    {
        info('User was created: ' . $user->email);
    }
 
    // ...
}

If you launch the user creation code without specifying the email_verified_at column in the DB, you should still see the column value with the date.


## Attributes: Accessors and Mutators

Summary of this lesson:
- Implementing Eloquent Accessors and Mutators
- Working with attribute transformations
- Understanding new vs old syntax
- Handling date formatting with attributes

Let's get familiar with a few more terms about Eloquent which are Accessors and Mutators or combined, they are called Attributes from Laravel 9.

If you work with projects before Laravel 9, there may be different syntax in older tutorials or examples. I will show you the old syntax at the end of this lesson.

So, Accessors and Mutators are used if you want to change the value when getting or setting the data. Now, why would you do that?

Example 1: Accessors
For example, you have a created_at field but want to show it in a human format.

Because created_at is automatically casted to a datetime and is a Carbon object, you can use diffForHumans() directly on the field. Now, of course, you can do that in the blade or the API response directly. But what if you want to reuse it in all cases where you use the model, so it makes sense to have some kind of function inside the Model, which is an Accessor.

So, you define the Accessor as an attribute. Interestingly, the Accessor method name is camel case, but it is returned as snake case.

app/Models/User.php:

use Illuminate\Database\Eloquent\Casts\Attribute;
 
class User extends Authenticatable
{
    // ...
 
    protected function createdDiff(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->created_at->diffForHumans(),
        );
    }
}
use Illuminate\Database\Eloquent\Casts\Attribute;
 
class User extends Authenticatable
{
    // ...
 
    protected function createdDiff(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->created_at->diffForHumans(),
        );
    }
}

[image](https://laraveldaily.com/uploads/2024/03/accessor-attribute-created-diff.png)

Example 2: Mutators
Mutators are the opposite of Accessors: they are used when you need to change some value when saving the data.

For example, you're creating a user, and the user is provided with a lowercase name, like "taylor". But you want to respect people and save that with the first uppercase letter anyway.

To do that, you define the name as an attribute, and instead of get, you define a set.

app/Models/User.php:

use Illuminate\Database\Eloquent\Casts\Attribute;
 
class User extends Authenticatable
{
    // ...
 
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn($value) => ucfirst($value),
        );
    }
}

When creating a user, the name will be set to the first uppercase letter before saving it into the database.

Example 3: Accessor & Mutator together
Let's get to the example of mixing of Accessor and Mutator, using get and set in the same field.

A typical example would be date formatting before and after. For example, you have a birth date formatted in some non-standard format, different from the database. In the database, the date should be saved in Y-m-d, but your users may provide it in a different format.

So, when saving the data, you must format it into the MySQL format. When getting the data, you need to format it back to the user format, so users won't see how it is stored in the database.

To do that, we would define a birthDate attribute. The actual user field is birth_date with underscore in the database.

app/Models/User.php:

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
 
class User extends Authenticatable
{
    // ...
 
    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y'),
            set: fn($value) => Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d'),
        );
    }
}

You can try to create a User.
User::create([
    'name' => 'Taylor',
    'email' => 'test@test.com',
    'password' => 'password',
    'birth_date' => '01/25/1990',
]);

In the database, the birth date is in the correct format.
[image to database](https://laraveldaily.com/uploads/2024/03/attribute-birthdate-db.png)

And, when the field is called, the format is different.
[different](https://laraveldaily.com/uploads/2024/03/attribute-birthdate-get-value.png)

Old Syntax
The old syntax still works, but it's not in the official documentation of Laravel: the newer syntax aimed to combine getters and setters in the same function.

So, the old syntax for the function name consists of three parts:

Prefix of get or set
The column name (CamelCase)
The word Attribute
The example for a birth_date column would be getBirthDateAttribute() and setBirthDateAttribute().

app/Models/User.php:
use Illuminate\Support\Carbon;
 
class User extends Authenticatable
{
    // ...
 
    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
    }
 
    public function getBirthDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
}

Notice: For columns like created_at and updated_at, be careful when using accessors to override the values of the same fields. For more explanation on why, you can check the YouTube video Eloquent Accessors: Dates, Casts, and "Wrong Way"

Performance Considerations with Accessors - not fully understand
While Accessors are powerful, they can lead to performance issues if not used carefully.

A common mistake is loading relationships within Accessors or Attribute methods in Eloquent models.

Here's an example. In a User model, you might have an Accessor that determines a user's identity which calls functions that call relationships:

app/Models/User.php:

protected function identity(): Attribute
{
    return Attribute::make(
        get: function () {
            if ($this->is_full_identified()) {
                return 1;
            }
 
            if ($this->is_ghost()) {
                return 3;
            }
 
            return 0;
        },
    );
}
 
public function is_full_identified(): bool
{
    return $this->networks->isNotEmpty() || (!is_null($this->name) && !is_null($this->phone));
}
 
public function is_ghost(): bool
{
    return $this->ghosts->isNotEmpty() && is_null($this->email) && $this->networks->isEmpty();
}

The task is to calculate how many users are:

Fully identified
Ghosts
Or guests
For calculating users, there is a service with the method get_identification_status() that accepts a Collection of users. Inside this method, the $user->identity Attribute is called in a foreach loop.

app/Services/UserService.php:

## 11. touch() Method To Work With updated_at
Summary of this lesson:
- Using touch() to update timestamps
- Applying touch() to multiple models
- Managing relationship timestamps
- Controlling automatic timestamp updates

A "quick tip" lesson. Laravel has a few helper methods to update your Eloquent Model updated_at timestamp. For example, on the user Model, you have some users, and you want to update just its updated_at value without touching any other data.

[image](https://laraveldaily.com/uploads/2024/03/updated-at-field.png)
You can use touch() on the Model, which will update only the updated_at field.
[after touch](https://laraveldaily.com/uploads/2024/03/touch-updated-at.png)
Since Laravel 9.25, there's a possibility to use touch() on multiple models with Eloquent query.

[multiple model](https://laraveldaily.com/uploads/2024/03/touch-multiple-models.png)
Records with an ID bigger than two will be updated, and the result is how many records are updated. In this example, two.

You can also touch a parent relationship Model. The relationships to be touched are defined on the Model in the $touches property as an array.

For example, you have Post and User Models. Users have many Tasks.
// In Task model
protected $touches = ['user'];

// When this task is updated...
$task->update(['duration' => '3 hours']);

// The associated user's updated_at will also be updated
// In tinker
$task = Task::first();
$user = $task->user;
$originalUpdated = $user->updated_at;

// Update the task
$task->update(['name' => 'New Name']);

// Verify user was touched
$user->refresh();
$user->updated_at->gt($originalUpdated); // Returns true

Finally, the other way around. If you don't want to auto-update the timestamp during the data update, you can set timestamps to false for that update request.
$comment->timestamps = false;
$comment->update([ ... ]);

## 12. Model API Docs and 3 More Random Methods
Summary of this lesson:
- Exploring Laravel API documentation
- Understanding hidden Model methods
- Working with increment/decrement operations
- Using quiet save operations

The final thing I want to do in this section about various methods and properties of Eloquent Model structure is to refer you to the official API docs. Only a few people know that, but in addition to the official Laravel documentation, API documentation lists all potential methods, properties, and syntax options.

So if you go to api.laravel.com and look for an example, specifically for Model which is inside of Illuminate/Database/Eloquent, there's a considerable amount. You can find a lot of hidden gems.
For example, what I haven't shown you in this course, increment(). There are a few methods to increment or decrement some values in your Eloquent Model instead of doing plus one and save or update you just increment or decrement by some amount.

Also, there's an interesting method called is(). Instead of comparing two models and comparing their IDs manually, you can call on the Model is() method and pass another Model into the method. Eloquent would compare those objects and their IDs and return true or false.

Another example of interesting method called saveQuietly() or updateQuietly(). Using these methods when saving or updating events won't be fired. If you have observers, listeners on events, or something like that, they would not be fired if you save or update quietly.

These are just a few examples of interesting methods or methods that are fired under the hood when we call other methods. So for those, I recommend you plan some time because it's a very long page, or you can do that in batches daily. Analyze this page, and you will find something interesting you have yet to use.

## 13. find(), all(), first() and Their Extra Options
- Understanding different all() and find() method variations
- Using firstOrFail() and firstOr() methods
- Working with specific field selections
- Handling model not found scenarios

 Did you know you can specify the fields? The all() method accepts an array of fields to be returned.
 [showing all example with arrays of fields](https://laraveldaily.com/uploads/2024/03/eloquent-all-with-fields.png)
 Next, instead of all(), we can use find() to find a specific object. In the find() method, you specify the primary key value, ID, by default.
 Did you know you can specify columns as a second parameter?
  [example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-find-with-fields.png)
Also, you can specify an array of primary keys. This way, it will return a collection instead of an object.
[example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-find-multiple.png)

Another well-known and well-used method is findOrFail(), which means that it tries to find the records with provided keys, and if it doesn't find it, it throws an Exception.
There are also less-known methods like firstOr(), and then you can provide a callback function to perform whatever you want. For example, instead of 404, throw a different status error.
[example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-firstor.png)
Similarly, you can do findOr() and the second parameter a closure.
[example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-findor.png)

Speaking of optimizations with these Eloquent methods, it's important to remember that selecting only the specific columns you need can significantly improve performance.

When we use methods like all() or find() without specifying columns, Laravel loads every field from the table.

The select() method is especially useful when you want to select columns from a relationship:
$posts = Post::select('title', 'user_id')
    ->with(['user' => function($query) {
        $query->select('id', 'name', 'email');
    }])
    ->get();
Or, a shorter version
{
    $posts = Post::select('title', 'user_id')
    ->with('user:id,name,email')
    ->get();
}

Just remember to always include the primary and foreign keys needed for the relationships to work properly. In the example above, we need user_id from posts and id from users.

This technique is particularly valuable when your tables contain large text fields or when you're building APIs where response size matters.




## 14. whereDate() and other whereX Methods
Summary of this lesson:
- Using whereDate() and related date query methods
- Understanding different date filtering approaches
- Working with DB::raw() for date queries
- Best practices for column-specific where clauses

Example 1: DB::raw()
Because created_at is a datetime column, the SQL date function can be used.
User::where(\DB::raw('DATE(created_at)'), '2024-03-01')->first();

Example 2: whereDate()
Instead of doing DB::raw() and using SQL functions, you can use the whereDate() Eloquent method.
User::whereDate('created_at', '2024-03-01')->first();

Also, instead of date, you can check year, month, and day.
User::whereYear('created_at', '2024')->first();
User::whereMonth('created_at', '03')->first();
User::whereDay('created_at', '1')->first();

A "Trick" That I Don't Recommend
I will show you a technique that is not recommended, and I haven't even found that in the documentation, but it's a nifty trick to know. I never used it, and I don't recommend it.

You can prefix where with the column name. For example, typically, when searching for email, you would do the following:
User::where('email', 'test@test.com')->first();

But you can do it like this:
User::whereEmail('test@test.com')->first();

Also, you can provide more columns with the word and.
User::whereEmailAndCreatedAt('test@test.com', '2023-03-01')->first();


## 15. Brackets Between "and" / "or" Conditions
Summary of this lesson:
- Managing complex where conditions
- Understanding query brackets and priorities
- Using proper query grouping techniques
- Implementing whereAny() and whereAll() methods

Imagine the scenario where you want to filter the users with email_verified_at and some other condition with or. For example, we're filtering users with email_verified_at not null and where the day of created_at is equal to 4 or 5.

$users = User::whereNotNull('email_verified_at')
    ->whereDay('created_at', 4)
    ->orWhereDay('created_at', 5)
    ->get();
 
foreach ($users as $user) {
    dump($user->id . ': ' . $user->name);
}

In the database, I have three users.
[three user's shown ](https://laraveldaily.com/uploads/2024/03/and-or-db-users.png)
Two of them are with verified email, and all are created on the fourth or fifth day. What should this query return?
Probably two users, because we're querying the email_verified_at, which should be true for two out of three records. But the result is all three records:
[example explaining this ](https://laraveldaily.com/uploads/2024/03/and-or-bad-result.png)

Let's check the SQL query.
select * from "users"
    where "email_verified_at" is not null
        and strftime('%d', "created_at") = cast('04' as text)
        or strftime('%d', "created_at") = cast('05' as text)

NOTE: The SQL query syntax is for SQLite.

If you know the theory of SQL, then the order of that sequence would be exactly this: email, and day, and then or day.

Which means the filter query is either "email_verified_at" is not null and strftime('%d', "created_at") = cast('04' as text) or strftime('%d', "created_at") = cast('05' as text).

In this example, even if the first filter for verified email and the fourth day is false, the second filter for the fifth day is true. So, you must add the dates filter in the brackets.

To add date filters in the brackets they must go into another where clause. Then, this additional where will make the date filter one sub statement

use Illuminate\Database\Eloquent\Builder;
 
$users = User::whereNotNull('email_verified_at')
    ->where(function (Builder $query) { 
        $query->whereDay('created_at', 4)
            ->orWhereDay('created_at', 5);
    }) 
    ->get();

If we check the SQL query now, the date conditions are in the brackets.
    where "email_verified_at" is not null
        and (strftime('%d', "created_at") = cast('04' as text)
        or strftime('%d', "created_at") = cast('05' as text))

Now the result is correct with only two users because the query is correct:

The point here is that if you have and and or conditions, be careful in which order they execute and whether they return the correct results.

Quote from the official documentation:

You should always group orWhere calls in order to avoid unexpected behavior when global scopes are applied.

Additionally, since Laravel 10.47, if you want to search multiple fields for the same keyword instead of using where() and providing where columns in a closure:

$search = $request->input('search');
 
$users = User::whereNotNull('email_verified_at')
    ->where(function (Builder $query) use ($search) {
        $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%");
    })
    ->get();
You can use a whereAny() method and provide columns as an array.
$search = $request->input('search');
 
$users = User::whereNotNull('email_verified_at')
    ->whereAny([
        'name',
        'email'
    ], 'LIKE', "%{$search}%")
    ->get();

Or if you need every column to have the keyword, then the whereAll() method could be used.
$search = $request->input('search');
 
$users = User::whereNotNull('email_verified_at')
    ->whereAll([
        'name',
        'email'
    ], 'LIKE', "%{$search}%")
    ->get();

The whereAll() will make a query with a AND operator, and whereAny() will use the OR operator.


## 16. Local and Global Scopes for Repeating Conditions
Now, let's talk about repeating queries in Eloquent. For example, you have a where() condition, and you want to repeat the same condition in other parts of your application and other Controllers, in other classes, and so on.
$users = User::whereNotNull('email_verified_at')->get();
 
foreach ($users as $user) {
    dump($user->id . ': ' . $user->name);
}

You may want to extract that condition into some function, which you would be able to reference in a shorter way. For that, you can use scopes.


Local Scope
You can define that where() condition and put that in a Model in a function with the prefix scope and then scope name.

For example, we can call it scopeVerified(). The parameter is the Eloquent builder, and you provide the where statement in the function.

app/Models/User.php:

use Illuminate\Database\Eloquent\Builder;
 
class User extends Authenticatable
{
    // ...
 
    public function scopeVerified(Builder $query): void
    {
        $query->whereNotNull('email_verified_at');
    }
}

Since Laravel 12, there's another new syntax: instead of prefixing the method name like scopeXXXXX(), you can just add the PHP attribute on top and change the method from public to protected:

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
 
// ...
 
class User extends Authenticatable
{
    // ...
 
    #[Scope]
    protected function verified(Builder $query): void
    {
        $query->whereNotNull('email_verified_at');
    }
}

To use this scope, instead of the where() condition, we call the scope name, which in this case is verified().
$users = User::whereNotNull('email_verified_at')->get(); 
$users = User::verified()->get(); 
 
foreach ($users as $user) {
    dump($user->id . ': ' . $user->name);
}

Dynamic Scopes
Scopes may have parameters, and such scopes are called dynamic scopes. For example, a user has a type. In the scope, you can add parameters as many as you need.

app/Models/User.php:

$users = User::verified()->typeOf('admin')->get();
 
foreach ($users as $user) {
    dump($user->id . ': ' . $user->name);
}

These scopes are called local scopes, which you call locally from your Controller or wherever.

Global Scopes
Global scopes are applied automatically globally on all the Models. There are two ways to use global scopes.

Option 1: Callback Function
The first option is called Anonymous Global Scopes, where you add global scope in the Model inside the booted() method.

app/Models/User.php:

use Illuminate\Database\Eloquent\Builder;
 
class User extends Authenticatable
{
    // ...
 
    protected static function booted(): void
    {
        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->whereNotNull('email_verified_at');
        });
    }
}


If we get all the users, the global scope will be applied, and only the verified users will be shown.

$users = User::all();
 
foreach ($users as $user) {
    dump($user->id . ': ' . $user->name);
}

Option 2: Scope Class
The second option is to generate a global using the make:scope artisan command and add the query in the apply() method of the generated scope class.

php artisan make:scope VerifiedScope

Scopes are generated in the app/Models/Scopes folder. We can add the where statement to the VerifiedScope class.

app/Models/Scopes/VerifiedScope.php:
class VerifiedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNotNull('email_verified_at'); 
    }
}

Next, we must apply the global scope using the ScopedBy attribute on the Model.

app/Models/User.php:
use App\Models\Scopes\VerifiedScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
 
#[ScopedBy([VerifiedScope::class])] 
class User extends Authenticatable
{
    // ...
}

Or, you can register it in the Model's booted() method.

app/Models/User.php:

use App\Models\Scopes\VerifiedScope;
 
class User extends Authenticatable
{
    // ...
 
    protected static function booted(): void
    {
        static::addGlobalScope(new VerifiedScope);
    }
}

Removing Global Scopes
In some cases, you might need to remove the global scope from the query. You can use the withoutGlobalScope() method in the Eloquent query. Without any parameters, it will remove all global scopes.

// $users = User::withoutGlobalScopes()->get()

You can provide the global scope which you need to be removed.
use App\Models\Scopes\VerifiedScope;
 
#$users = User::withoutGlobalScope(VerifiedScope::class)->get();
Or, provide only a name.
#$users = User::withoutGlobalScope('verified')->get();
In the array, you can provide multiple scopes to be removed.
User::withoutGlobalScopes([
    FirstScope::class, SecondScope::class
])->get();

You can use scopes for repeating queries, but be specifically careful with global scopes because, in the future, some other developers may not even know that the scope exists and would have unexpected results for their queries.


## 17. Instead of Multiple If-Else, Use Eloquent When()

Summary of this lesson:
- Using when() for conditional queries
- Implementing dynamic query conditions
- Replacing if-else statements with when()
- Understanding conditional clause benefits

Now, instead of making an if statement, we can use the when method on the Eloquent query. The first parameter must be the condition, and the second parameter is the closure with the query.

use Illuminate\Database\Eloquent\Builder;
 
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::query() 
            ->when($request->integer('user_id'), function (Builder $query) use ($request) {
                $query->where('user_id', $request->integer('user_id'));
            })
            ->when($request->boolean('completed'), function (Builder $query) use ($request) {
                $query->where('is_completed', $request->boolean('completed'));
            })->get(); 
 
        if ($request->has('user_id')) { 
            $query->where('user_id', $request->integer('user_id'));
        }
 
        if ($request->has('completed')) {
            $query->where('is_completed', $request->boolean('completed'));
        }
 
        $tasks = $query->get(); 
 
        foreach ($tasks as $task) {
            dump($task->id . ': ' . $task->description);
        }
    }
}


## 18. SubQueries and SubSelects: One Step Towards Raw SQL
Summary of this lesson:
- Use addSelect() with subqueries to get specific data
- Relationship approach loads all models, while subqueries reduce memory usage significantly
- Subquery method executes a single query vs multiple queries with relationships
- Performance difference for relationships vs subqueries

This lesson explores techniques for retrieving the latest post for each user in a Laravel application, comparing relationship-based approaches with subselects.

The Challenge
Our task is to load the latest record (post) for each user in our system. Our expected output should display:

Username
The creation date of their latest post
There are two ways to achieve the result:

1. Eloquent relationship
2. Adding a subselect

Approach 1: Eloquent Relationships
One way to accomplish this is by defining a special relationship in your User model specifically for the latest post:

app/Models/User.php:

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class User extends Authenticatable
{
    // ...
 
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
 
    public function lastPost(): HasOne
    {
        return $this->hasOne(Post::class)->latest();
    }
}

Then in your controller:

$users = User::with('lastPost')->get();
And in the View to show the result:

@foreach ($users as $user)
    <div>{{ $user->name }}: {{ $user->lastPost->created_at }}</div>
@endforeach




[example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-findor.png)


## 12. Model API Docs and 3 More Random Methods


[example explaining this ](https://laraveldaily.com/uploads/2024/03/eloquent-findor.png)





## 12. Model API Docs and 3 More Random Methods
