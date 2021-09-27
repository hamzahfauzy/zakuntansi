<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryTypeAccount;
use Illuminate\Support\Facades\Artisan;

class dbcreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MySQL database based on the database config file or the provided name';

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
     * @return int
     */
    public function handle()
    {
        $schemaName = $this->argument('name') ?: config("database.connections.mysql.database");
        
        $conn = new \mysqli(config('database.connections.mysql.host'),config('database.connections.mysql.username'),config('database.connections.mysql.password'));

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName;";

        $conn->query($query);

        Artisan::call("migrate:fresh");

        Role::insert([
            ['name'=>'Master'],
            ['name'=>'Bendahara'],
            ['name'=>'Guru / Pegawai'],
            ['name'=>'Siswa'],
            ['name'=>'Kasir'],
            ['name'=>'Operator'],
        ]);

        $aktiva = Account::create([
            'account_code' => '1',
            'account_transaction_code' => 'TR-1',
            'name' => 'Aktiva',
            'pos' => 'Nrc',
            'normal_balance' => 'Db',
            'balance' => 0
        ]);

        $kas = Account::create([
            'parent_account_id' => $aktiva->id,
            'account_code' => '1-1',
            'account_transaction_code' => 'TR-1-1',
            'name' => 'Kas',
            'pos' => 'Nrc',
            'normal_balance' => 'Db',
            'balance' => 0
        ]);

        CategoryTypeAccount::create([
            'account_id' => $kas->id,
            'status'     => 'KAS'
        ]);

        Account::create([
            'account_code' => '2',
            'account_transaction_code' => 'TR-2',
            'name' => 'Hutang',
            'pos' => 'Nrc',
            'normal_balance' => 'Cr',
            'balance' => 0
        ]);

        $pasiva = Account::create([
            'account_code' => '3',
            'account_transaction_code' => 'TR-3',
            'name' => 'Pasiva',
            'pos' => 'Nrc',
            'normal_balance' => 'Cr',
            'balance' => 0
        ]);

        $modal = Account::create([
            'parent_account_id' => $pasiva->id,
            'account_code' => '3-1',
            'account_transaction_code' => 'TR-3-1',
            'name' => 'Modal',
            'pos' => 'Nrc',
            'normal_balance' => 'Cr',
            'balance' => 0
        ]);

        CategoryTypeAccount::create([
            'account_id' => $modal->id,
            'status'     => 'KAS'
        ]);

    }
}
