<?php

declare(strict_types=1);

/**
 *
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 * 
 * @Author X.Mo <root@imoi.cn>
 * @Link   https://www.mineadmin.com/
 * @Github  https://github.com/kanyxmo
 * @Document https://doc.mineadmin.com/
 *
 */

namespace Mine;

use Mine\Kernel\Db\ConnectionResolver;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            // 合并到  config/autoload/dependencies.php 文件
            'dependencies' => [
                \Hyperf\Database\ConnectionResolverInterface::class => ConnectionResolver::class
            ],
            'middlewares' => [
                'http' => [
                ]
            ],
            // 合并到  config/autoload/annotations.php 文件
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
                'class_map' => [
                    Hyperf\ClassMap\Crooutine::class => __DIR__ . '/Kernel/ClassMap/Coroutine.php',
                    Hyperf\ClassMap\ResolverDispatcher::class => __DIR__ . '/Kernel/ClassMap/ResolverDispatcher.php',
                    Hyperf\Redis\Redis::class => __DIR__ . '/Kernel/Redis/Redis.php',
                    Hyperf\Database\Migrations::class => __DIR__ . '/Kernel/ClassMap/Migration.php.php',
                ]
            ],
            // 默认 Command 的定义，合并到 Hyperf\Contract\ConfigInterface 内，换个方式理解也就是与 config/autoload/commands.php 对应
            'commands' => [],
            // 与 commands 类似
            'listeners' => [],
            // 组件默认配置文件，即执行命令后会把 source 的对应的文件复制为 destination 对应的的文件
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'mineadmin config file.', // 描述
                    // 建议默认配置放在 publish 文件夹中，文件命名和组件名称相同
                    'source' => __DIR__ . '/../publish/mineadmin.php',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/mineadmin.php', // 复制为这个路径下的该文件
                ],
            ],
            // 亦可继续定义其它配置，最终都会合并到与 ConfigInterface 对应的配置储存器中
        ];
    }
}
