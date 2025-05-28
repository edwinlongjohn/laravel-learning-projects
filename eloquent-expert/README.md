
## 1. make:model - Less-Known Possible Options

Summary of this lesson:
- Understanding all available options for make:model command
- Using interactive model creation prompts
- Generating related files (migrations, controllers, etc.)
- Creating resource controllers with automatic model binding

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
- Customizing database table names and primary keys
- Configuring auto-increment settings
- Setting up pagination defaults
- Managing timestamp properties and naming

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
- Publishing and customizing model stubs
- Modifying default model template structure
- Removing default traits like HasFactory
- Understanding stub customization options

The default Eloquent Model is generated with a structure as below.
<?php
 
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

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
