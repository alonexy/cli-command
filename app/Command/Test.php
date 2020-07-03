<?php
namespace App\Command;

use Predis\Client;
use Swoole\Coroutine as Co;

/**
 * Created by PhpStorm.
 * User: alonexy
 * Date: 20/7/2
 * Time: 17:12
 */
class Test extends BaseCommand
{

    public $commandName = 'test:one {user=1 : 用户ID} {--param2 : 参数2}';
    public $commandDesc = 'Test DEsc';


    public function handle()
    {

        $user = $this->argument('user');
        $param2 = $this->option('param2');

        //设置当前进程信号异步处理
        pcntl_async_signals(true);

        //安装信号触发器器
        pcntl_signal(
            SIGINT, function ($signo) {
            switch ($signo) {
                case SIGUSR1:
                    echo "SIGUSR1 " . date("Y-m-d H:i:s", time()) . PHP_EOL;
                    break;
                case SIGALRM:
                    echo "SIGALRM " . date("Y-m-d H:i:s", time()) . PHP_EOL;
                    break;
                case SIGINT:
                    echo "SIGINT " . date("Y-m-d H:i:s", time()) . PHP_EOL;
                    sleep(1);
                    posix_kill(getmypid(),SIGQUIT);
                    break;
                default:
                    echo "unknow " . date("Y-m-d H:i:s", time()) . PHP_EOL;
                    break;
            }
//            pcntl_alarm(5);
        }, false);

//        pcntl_alarm(5); // 5秒后向进程发送一个SIGALRM信号
        while (true) {
            echo "SLEEP " . date("Y-m-d H:i:s", time()). PHP_EOL ;
            sleep(10);
            pcntl_signal_dispatch();
        }


//        //创建Server对象，监听 127.0.0.1:9501端口
//        $serv = new \Swoole\Server("127.0.0.1", 9880,SWOOLE_BASE,SWOOLE_SOCK_TCP);
//        $serv->on(
//            'Connect', function ($serv, $fd) {
//            echo "Client: {$fd} Connect.\n";
//        });
//
//        $serv->on(
//            'Receive', function ($serv, $fd, $from_id, $data) {
//            $serv->send($fd, "Server: " . $data);
//        });
//
//        $serv->on(
//            'Close', function ($serv, $fd) {
//            echo "Client: {$fd} Close.\n";
//        });
//        $serv->start();

    }
}