<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateToSqlite extends Command
{
    public $mssqlConnection = 'sqlsrv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:sqlite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        $this->copyCategories();
        $this->copyDepartments();

    }

    public function copyCategories()
    {
        $counter = 0;
        DB::connection($this->mssqlConnection)
            ->table('categories')
            ->orderBy('id')
            ->chunk(100, function($categories) use ($counter) {
                $toInsert = [];
                foreach($categories as $cat) {
                    $this->info('Processed: '.++$counter);
                    $toInsert[] = [
                        'name' => $cat->name,
                        '_lft' => $cat->_lft,
                        '_rgt' => $cat->_rgt,
                        'parent_id' => $cat->parent_id,
                        'created_at' => $cat->created_at,
                        'updated_at' => $cat->updated_at,
                    ];
                };
                Category::insert($toInsert);
            });
    }

    public function copyDepartments()
    {
        $currentProcessing = 0;

        DB::connection($this->foreignConnection)
            ->table('departments')
            ->orderBy('id')
            ->chunk(100, function($departments) use ($currentProcessing) {

                $toInsert = [];
                foreach($departments as $department) {
                    $this->info(++$currentProcessing);

                    $region_id = $this->findRegionId($department);

                    $toInsert[] = [
                        'code' => $department->code,
                        'description' => $department->description,
                        'head_department' => $department->head_department,
                        'region_id' => $region_id,
                    ];
                }

                Department::insert($toInsert);
            });
    }
}
