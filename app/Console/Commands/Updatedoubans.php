<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DoubandatasController;

class Updatedoubans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatedoubans';

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
        //
        $douban = new DoubandatasController();
        $this->info('下载网站-豆瓣TOP250');
        dump($douban->doubandatas());
        $this->info('下载网站-猫眼TOP100');
        dump($douban->maoyandatas());
        $this->info('更新任务完成');
    }
}
