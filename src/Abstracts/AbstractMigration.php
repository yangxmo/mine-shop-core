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

declare (strict_types = 1);
namespace Mine\Abstracts;

use Hyperf\Database\Migrations\Migration;

/**
 * Class AbstractMigration
 * @package Mine\Abstracts
 */
abstract class AbstractMigration extends Migration
{
    protected string $connection = '';
}
