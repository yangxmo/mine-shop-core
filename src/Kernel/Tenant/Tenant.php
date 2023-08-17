<?php

namespace Mine\Kernel\Tenant;

use Hyperf\Support\Traits\StaticInstance;

class Tenant
{
    use StaticInstance;

    public string $tenantId = '';

    /**
     * @param string|null $tenantId
     * @return void
     */
    public function setTenantId(string $tenantId = null): void
    {
        if (is_null($tenantId)) {
            $tenantId = context_get('tenant_id', 'default');
        }

        $this->tenantId = $tenantId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->tenantId;
    }

}