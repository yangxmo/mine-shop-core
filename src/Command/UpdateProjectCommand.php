<?php

/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

declare(strict_types=1);

namespace Mine\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Database\Seeders\Seed;
use Hyperf\Database\Migrations\Migrator;
use Mine\Kernel\Tenant\Tenant;
use Mine\Mapper\TenantMapper;
use Mine\MineCommand;
use Mine\Mine;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class UpdateProjectCommand
 * @package System\Command
 */
#[Command]
class UpdateProjectCommand extends MineCommand
{
    /**
     * 更新项目命令
     * @var string|null
     */
    protected ?string $name = 'mine:update';

    protected array $database = [];

    protected Seed $seed;

    protected Migrator $migrator;

    protected TenantMapper $tenantMapper;

    /**
     * UpdateProjectCommand constructor.
     * @param Migrator $migrator
     * @param Seed $seed
     * @param TenantMapper $tenantMapper
     */
    public function __construct(Migrator $migrator, Seed $seed, TenantMapper $tenantMapper)
    {
        parent::__construct();
        $this->migrator = $migrator;
        $this->seed = $seed;
        $this->tenantMapper = $tenantMapper;
    }

    public function configure()
    {
        parent::configure();
        $this->setHelp('run "php bin/hyperf.php mine:update" Update MineAdmin system');
        $this->setDescription('MineAdmin system update command');
    }

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        $modules = make(Mine::class)->getModuleInfo();
        $basePath = BASE_PATH . '/app/';

        $isInit = $this->input->getArgument('init');

        if ($isInit) {
            $this->exec($modules, $basePath);
        } else {
            // 获取所有租户ID
            $tenantList = $this->tenantMapper->getListForCursor(['select' => ['tenant_id']]);

            // 执行
            foreach ( $tenantList as $tenant)
            {
                 $this->exec($modules, $basePath, $tenant->tenant_id);
            }
        }

        $this->line($this->getGreenText('updated successfully...'));
    }

    protected function exec (array $modules,string $basePath, string $poolName = 'default'): void
    {
        // 设置connect
        $this->migrator->setConnection($poolName);

        foreach ($modules as $name => $module) {
            $seedPath = $basePath . $name . '/Database/Seeders/Update';
            $migratePath = $basePath . $name . '/Database/Migrations/Update';

            if (is_dir($migratePath)) {
                $this->migrator->run([$migratePath]);
            }

            if (is_dir($seedPath)) {
                $this->seed->run([$seedPath]);
            }
        }
    }

    protected function getArguments(): array
    {
        return [
            ['init', InputArgument::OPTIONAL, 'The run seeder class of the init', false],
        ];
    }
}
