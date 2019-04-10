<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GetdownloaddatasController;

class updatedownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatedownloads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update downloads database';

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
        $download = new GetdownloaddatasController();
        $this->info('下载网站-更新音范丝');
        // dump($download->clewer_yinfansi());
        $this->info('下载网站-更新电影天堂');
        // dump($download->clewer_dytt());
        $this->info('下载网站-更新BT之家');
      //  dump($download->clewer_btzhijia());
        $this->info('下载网站-66V');
        dump($download->clewer_s66());
        $this->info('下载网站-讯影网');//0
        // dump($download->clewer_xunying());//0
        $this->info('下载网站-BTBT电影网 ');
        // dump($download->clewer_btbtdy());//0
        $this->info('下载网站-迅雷720');
        // dump($download->clewer_xl720());
        $this->info('下载网站-优质电影网');
        // dump($download->clewer_youzhidy());
        $this->info('下载网站-pvideos电影网(dead)');
        // dump($download->clewer_pvideos());
        $this->info('更新任务完成');
    }
}
