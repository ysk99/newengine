<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GetController;


class Updateonlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateonlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update onlines';

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
        $this->info('更新任务即将开始');
        $go = new GetController();
        // $download = new GetdownloaddatasController();
        // $this->info('在线网站-天天云影院');
        //dump($go->clewer_ttyyy());//0
        // $this->info('在线网站-逗别看');
        // dump($go->clewer_doubiekan());
        // $this->info('在线网站-在线之家');
        // dump($go->clewer_zxzj());
        // $this->info('在线网站-15影院');
        // dump($go->clewer_yc15());
        // $this->info('在线网站-88影院');
        // dump($go->clewer_ys88());
        // $this->info('在线网站-田鸡影院网站');
        // dump($go->clewer_mtotoo());
        // $this->info('在线网站-云播影院');
        // dump($go->clewer_yunbtv());
        // $this->info('在线网站-BTtwo影院');
        // dump($go->clewer_bttwo());
        // $this->info('在线网站-gimy影院');
        // dump($go->clewer_gimy());
		// $this->info('在线网站-海兔影院');
        // dump($go->clewer_haitu());
        $this->info('在线网站-看看屋');
        dump($go->clewer_kankanwu());
        
        $this->info('更新任务完成');
    }
}
