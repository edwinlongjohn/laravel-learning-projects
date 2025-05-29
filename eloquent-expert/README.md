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

<?php
 
namespace {{ namespace }};
 
{{ factoryImport }}
use Illuminate\Database\Eloquent\Model;
 
class {{ class }} extends Model
{
    {{ factory }}
}

You can add and remove what you need from the stub.

stubs/model.stub:

<?php
 
namespace {{ namespace }};
 
{{ factoryImport }}   -- remove
use Illuminate\Database\Eloquent\Model;
 
class {{ class }} extends Model
{
    {{ factory }}   --remove 
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

<?php
 
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


## Contributing
