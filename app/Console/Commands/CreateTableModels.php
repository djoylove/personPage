<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\DB;

class CreateTableModels extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'model:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Model By DB. php artisan model:create DatabaseName tableName';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $dbname = $this->argument('dbName');
        $tablename = $this->argument('tableName');
        if ($dbname == null) {
            $this->error('You Need Input DataBase Name!  \n Use: php artisan model:create dbName=databaseName');
            return;
        }
        if ($tablename == null) {
            $this->error('You Need Input $tablename !  \n Use: php artisan model:create tablename=$tablename');
            return;
        }
        $this->createBaseModel($dbname);
        $this->createSubModel($dbname, $tablename);
    }

    public function createBaseModel($dbname)
    {

        $db = DB::connection("{$dbname}");


        $path = dirname(__DIR__) . "/../Models";
        $str = '<?php

namespace App\Models\{dbname};

use Illuminate\Database\Eloquent\Model;

class {ClassTableName} extends Model {

}';


//创建数据库model目录
        $dbNameTmp = $this->getClassName($dbname);
        $path = $path . "/" . $dbNameTmp;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $str = str_replace('{dbname}', $dbNameTmp, $str);


        $ClassTableName = $dbNameTmp . "BaseModel";
        $file = $path . "/" . $ClassTableName . ".php";
        $content = str_replace('{ClassTableName}', $ClassTableName, $str);
        if (!file_exists($file)) {
            file_put_contents($file, $content);
            $this->info('Create ' . $ClassTableName);
        }
    }

    public function createSubModel($dbname, $tablename)
    {

        $db = DB::connection("{$dbname}");

        $path = dirname(__DIR__) . "/../Models";
        $str = '<?php

namespace App\Models\{dbname};

class {ClassTableName} extends {baseModel} {
    public $timestamps = true;
    protected $guarded = [\'id\',\'created_at\', \'updated_at\', \'deleted_at\'];

    /**
     * The database table used by the model.
     *
     * @var string
     	 */
    protected $table = "{dbOriName}.{tableName}";


    /**
     * The attributes excluded from the model s JSON form.
     *
     * @var array
     	 */
    protected $hidden = [];

}';


//创建数据库model目录
        $dbNameTmp = $this->getClassName($dbname);
        $path = $path . "/" . $dbNameTmp;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $str = str_replace('{dbname}', $dbNameTmp, $str);

        $str = str_replace('{dbOriName}', $dbname, $str);

        $str = str_replace('{baseModel}', $dbNameTmp . "BaseModel", $str);

//        $sql = "show tables from {$dbname}";
        $sql = "show tables from {$dbname} like '{$tablename}'";
        $rows = $db->select($sql);
        if(count($rows) == 0) {
            $this->error("error!{$dbname}.{$tablename} not exists, pls make sure the table is exists");
            return;
        }
//        foreach ($rows as $row) {
//            $key = "Tables_in_{$dbname}";
        $tableName = $tablename;
        $ClassTableName = $this->getClassName($tableName);
        $file = $path . "/" . $ClassTableName . ".php";
        $content = str_replace('{ClassTableName}', $ClassTableName, $str);
        $content = str_replace('{tableName}', $tableName, $content);
        if (!file_exists($file)) {
            file_put_contents($file, $content);
            $this->info('Create ' . $ClassTableName);
        } else {
            $this->error('Create failed, ' . $ClassTableName. ' already exists!');
        }
//        }

    }


    public function getClassName($tableName)
    {

        $className = "";
        $arr = explode("_", $tableName);

        foreach ($arr as $value) {
            $className .= ucfirst($value);
        }

        return $className;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['dbName', InputArgument::REQUIRED, 'Use: php artisan model:create  DatabaseName TableName'],
            ['tableName', InputArgument::REQUIRED, 'Use: php artisan model:create DatabaseName TableName'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

}
