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
namespace Mine\Aspect;

use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Exception\Exception;
use Mine\Annotation\Api\MApiAuth;
use Mine\Exception\TokenException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class MApiAuthAspect
 * @package Mine\Aspect
 */
#[Aspect]
class MApiAuthAspect extends AbstractAspect
{

    public array $annotations = [
        MApiAuth::class
    ];

    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|InvalidArgumentException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $scene = 'default';

        /** @var $auth MApiAuth */
        if (isset($proceedingJoinPoint->getAnnotationMetadata()->class[MApiAuth::class])) {
            $auth = $proceedingJoinPoint->getAnnotationMetadata()->class[MApiAuth::class];
            $scene = $auth->scene ?? 'default';
        }

        if (isset($proceedingJoinPoint->getAnnotationMetadata()->method[MApiAuth::class])) {
            $auth = $proceedingJoinPoint->getAnnotationMetadata()->method[MApiAuth::class];
            $scene = $auth->scene ?? 'default';
        }

        $loginUser = app_verify($scene);

        if (! $loginUser->check(null, $scene)) {
            throw new TokenException(t('jwt.validate_fail'));
        }

        return $proceedingJoinPoint->process();
    }
}