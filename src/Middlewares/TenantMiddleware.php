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
namespace Mine\Middlewares;

use Mine\Exception\MineException;
use Mine\Kernel\Tenant\Tenant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TenantMiddleware  implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 检查租户
        if (!$request->hasHeader('X-Tenant-Id') || empty($tenantId = $request->getHeaderLine('X-Tenant-Id'))) {
            throw new MineException(t('mineadmin.tenant_notfound'));
        }

        Tenant::instance()->setTenantId($tenantId);

        context_set('tenant_id', $tenantId);

        return $handler->handle($request);
    }
}