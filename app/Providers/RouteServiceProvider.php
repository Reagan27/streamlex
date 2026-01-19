<?php

namespace Vanguard\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\AdminContract;
use Vanguard\ContractVersion;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    protected string $webNamespace = 'Vanguard\Http\Controllers\Web';
    protected string $apiNamespace = 'Vanguard\Http\Controllers\Api';

    public function boot(): void
    {
        parent::boot();

        $this->bindUser();
        $this->bindRole();
        $this->bindSession();
        $this->bindContract();
        $this->bindContractVersion();
        Route::model('contract', AdminContract::class);
    }

   public function map(): void
    {
      
        $this->mapApiRoutes();

        
        $this->mapExcelDataRoutes();

    
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::group([
            'namespace' => $this->webNamespace,
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    protected function mapApiRoutes(): void
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->apiNamespace,
            'prefix' => 'api',
        ], function () {
            require base_path('routes/api.php');
        });
    }

    protected function mapExcelDataRoutes(): void
    {
        Route::group([
            'middleware' => ['api'],
            'namespace' => $this->apiNamespace,
            'prefix' => 'api',
        ], function () {
            Route::prefix('v1')->group(function () {
                Route::get('excel-data', 'ExcelDataController@index');
                Route::get('excel-data/{fileIdentifier}', 'ExcelDataController@show')
                    ->where('fileIdentifier', '.*');
            });
        });
    }

    private function bindUser(): void
    {
        $this->bindUsingRepository('user', UserRepository::class);
    }

    private function bindRole(): void
    {
        $this->bindUsingRepository('role', RoleRepository::class);
    }

    private function bindSession(): void
    {
        $this->bindUsingRepository('session', SessionRepository::class);
    }

    private function bindContract(): void
    {
        Route::bind('contract', function ($value) {
            try {
                return AdminContract::findOrFail($value);
            } catch (\Exception $e) {
                throw new NotFoundHttpException('Contract not found.');
            }
        });
    }

    private function bindContractVersion(): void
    {
        Route::bind('version', function ($value) {
            try {
                return ContractVersion::findOrFail($value);
            } catch (\Exception $e) {
                throw new NotFoundHttpException('Contract version not found.');
            }
        });
    }

    private function bindUsingRepository($entity, $repository, $method = 'find'): void
    {
        Route::bind($entity, function ($id) use ($repository, $method) {
            if ($object = app($repository)->$method($id)) {
                return $object;
            }

            throw new NotFoundHttpException('Resource not found.');
        });
    }
}
