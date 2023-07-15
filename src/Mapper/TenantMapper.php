<?php

declare(strict_types=1);

namespace Mine\Mapper;

use Mine\Model\TenantModel;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * Class SystemCorpMapper
 * @package App\System\Mapper
 */
class TenantMapper extends AbstractMapper
{
    /**
     * @var TenantModel
     */
    public $model;

    public function assignModel(): void
    {
        $this->model = TenantModel::class;
    }

    /**
     * 获取租户列表信息
     * @param array|null $params
     * @param bool $isScope
     * @param string $pageName
     * @return array
     */
    public function getPageList(?array $params, bool $isScope = true, string $pageName = 'page'): array
    {
        $params = ['select' => [
            'id', 'tenant_id', 'tenant_name', 'tenant_logo', 'audit', 'corporation_name', 'sex'
        ]];

        return parent::getPageList($params, $isScope, $pageName);
    }

    /**
     * @param string $corpCode
     * @param int $audit
     * @return bool
     */
    public function audit(string $corpCode, int $audit): bool
    {
        return parent::updateByCondition(['tenant_id' => $corpCode], ['audit' => $audit]);
    }


    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {

        if (isset($params['tenant_name'])) {
            $query->where('tenant_name', 'like', '%' . $params['tenant_name'] . '%');
        }

        if (isset($params['corporation_name'])) {
            $query->where('corporation_name', 'like', '%' . $params['corporation_name'] . '%');
        }

        if (isset($params['corporate_name'])) {
            $query->where('corporate_name', 'like', '%' . $params['corporate_name'] . '%');
        }

        if (isset($params['id_card'])) {
            $query->where('id_card', 'like', '%' . $params['id_card'] . '%');
        }

        if (isset($params['sex'])) {
            $query->where('sex', $params['sex']);
        }

        if (isset($params['corp_code'])) {
            $query->where('corp_code', $params['corp_code']);
        }
        if (isset($params['audit'])) {
            $query->where('audit', '=', $params['audit']);
        }

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }

        if (isset($params['expiration_at'])) {
            $query->whereBetween('expiration_at', $params['expiration_at']);
        }

        return $query;
    }
}
