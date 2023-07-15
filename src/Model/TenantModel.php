<?php

namespace Mine\Model;

use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id
 * @property string $tenant_id
 * @property string $tenant_name
 * @property string $tenant_logo
 * @property int $audit
 * @property string $expiration_at
 * @property string $corporation_name
 * @property int $sex
 * @property string $id_card
 * @property string $card_front
 * @property string $card_back
 * @property string $expiration_start
 * @property string $expiration_end
 * @property string $auth_at
 * @property string $corporate_name
 * @property string $usci
 * @property string $business_license
 * @property string $term
 * @property string $address
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class TenantModel extends \Mine\MineModel
{
    use SoftDeletes;

    /**
     * @var string|null
     */
    protected ?string $table = 'tenant';

    protected array $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'tenant_id', 'tenant_name', 'corp_logo', 'audit', 'expiration_at', 'corporation_name', 'sex', 'id_card', 'card_front', 'card_back', 'expiration_start', 'expiration_end', 'auth_at', 'corporate_name', 'usci', 'business_license', 'term', 'address', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'audit' => 'integer', 'sex' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

}