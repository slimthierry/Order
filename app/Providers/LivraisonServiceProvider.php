<?php

namespace App\Providers;

use App\Models\Action;
use App\Models\Address;
use App\Models\Category;
use App\Models\Deliverance;
use App\Models\Employe;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Profil;
use App\Models\Rating;
use App\Models\Ship;
use App\Models\SiteDeliverance;
use App\Models\Society;
use App\Models\StateDeliverance;
use App\Models\Transit;
use App\Models\TransitProduct;
use App\Models\UserMember;
use App\Models\Customer;
use App\Models\Status;
use App\Models\DeliveranceEvalution;
use App\Models\ServiceHour;
use App\Models\Contact;
use App\Models\DeliveranceStatus;
use App\Models\WorkedDays;

use App\Notifications\LivraisonNotifications;
use Illuminate\Support\ServiceProvider;

class LivraisonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        // Services concrete classes bindings

        // $this->app->bind('App\Notifications\LivraisonNotifications', function($app) {
        //     return new App\Notifications\LivraisonNotifications();
        // });

        $this->app->when(\App\Services\ActionsDataProvider::class)
        ->give(function () {
            return new  Action;
        });


        $this->app->when(\App\Services\AddressDataProvider::class)
        ->give(function () {
            return new Address;
        });

        $this->app->when(\App\Services\CategoriesDataProvider::class)
        ->give(function () {
            return new Category;
        });

        $this->app->when(\App\Services\DeliverancesDataProvider::class)
        ->give(function () {
            return new Deliverance;
        });

        $this->app->when(\App\Services\EmployesDataProvider::class)
        ->give(function () {
            return new Employe;
        });


        $this->app->when(\App\Services\PaymentMethodsDataProvider::class)
        ->give(function () {
            return new PaymentMethod;
        });


        $this->app->when(\App\Services\ProductsDataProvider::class)
        ->give(function () {
            return new Product;
        });


        $this->app->when(\App\Services\ProfilsDataProvider::class)
        ->give(function () {
            return new Profil;
        });


        $this->app->when(\App\Services\RatingsDataProvider::class)
        ->give(function () {
            return new Rating;
        });


        $this->app->when(\App\Services\ShipsDataProvider::class)
        ->give(function () {
            return new Ship;
        });

        $this->app->when(\App\Services\SocietiesDataProvider::class)
        ->give(function () {
            return new Society;
        });


        $this->app->when(\App\Services\SiteDeliverancesDataProvider::class)
        ->give(function () {
            return new SiteDeliverance;
        });


        $this->app->when(\App\Services\StateDeliverancesDataProvider::class)
        ->give(function () {
            return new StateDeliverance;
        });

        $this->app->when(\App\Services\TransitsProductsDataProvider::class)
        ->give(function () {
            return new TransitProduct;
        });

        $this->app->when(\App\Services\TransitsDataProvider::class)
        ->give(function () {
            return new Transit;
        });

        $this->app->when(\App\Services\UserMembersDataProvider::class)
        ->give(function () {
            return new UserMember;
        });

        $this->app->when(\App\Services\CustomersDataProvider::class)
        ->give(function () {
            return new Customer;
        });

        $this->app->when(\App\Services\DeliveranceStatusDataProvider::class)
        ->give(function () {
            return new DeliveranceStatus;
        });

        $this->app->when(\App\Services\ServiceHourDataProvider::class)
        ->give(function () {
            return new ServiceHour;
        });

        $this->app->when(\App\Services\WorkedDaysDataProvider::class)
        ->give(function () {
            return new WorkedDays;
        });

        $this->app->when(\App\Services\StatusDataProvider::class)
        ->give(function () {
            return new Status;
        });

        $this->app->when(\App\Services\ContactsDataProvider::class)
        ->give(function () {
            return new Contact;
        });

        $this->app->when(\App\Services\DeliveranceEvalutionsDataProvider::class)
        ->give(function () {
            return new DeliveranceEvalution;
        });

        // $this->app->when(\App\Services\DeliveranceEvalutionsDataProvider::class)
        // ->give(function () {
        //     return new DeliveranceEvalution;
        // });

        // Services interface bindings

        $this->app->bind(
            \App\Services\Contracts\IActionsDataProvider::class,
            \App\Services\ActionsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IAddressDataProvider::class,
            \App\Services\AddressDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\ICategoriessDataProvider::class,
            \App\Services\CategoriesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IDeliverancesDataProvider::class,
            \App\Services\DeliverancesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IEmployesDataProvider::class,
            \App\Services\EmployesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IPaymentMethodsDataProvider::class,
            \App\Services\PaymentMethodsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IProductsDataProvider::class,
            \App\Services\ProductsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IProfilsDataProvider::class,
            \App\Services\ProfilsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IRatingsDataProvider::class,
            \App\Services\RatingsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IShipsDataProvider::class,
            \App\Services\ShipsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\ISiteDeliverancesDataProvider::class,
            \App\Services\SiteDeliverancesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IStateDeliverancesDataProvider::class,
            \App\Services\StateDeliverancesDataProvider::class
        );


        $this->app->bind(
            \App\Services\Contracts\ISocietiesDataProvider::class,
            \App\Services\SocietiesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IStatesDataProvider::class,
            \App\Services\StatesDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\ITransitsDataProvider::class,
            \App\Services\TransitsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\ITransitsProductsDataProvider::class,
            \App\Services\TransitsProductsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IUserMembersDataProvider::class,
            \App\Services\UserMembersDataProvider::class
        );


        $this->app->bind(
            \App\Services\Contracts\ICustomersDataProvider::class,
            \App\Services\CustomersDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IServiceHoursDataProvider::class,
            \App\Services\ServiceHoursDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IDeliveranceEvalutionsDataProvider::class,
            \App\Services\DeliveranceEvalutionsDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IDeliveranceStatusDataProvider::class,
            \App\Services\DeliveranceStatusDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IStatusDataProvider::class,
            \App\Services\StatusDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IWorkedDaysDataProvider::class,
            \App\Services\WorkedDaysDataProvider::class
        );

        $this->app->bind(
            \App\Services\Contracts\IContactsDataProvider::class,
            \App\Services\ContactsDataProvider::class
        );

        $this->routesBindings($this->app);
    }

    /**
     * Initialize route definitions for the formcontrol package
     *
     * @param mixed $callback
     * @return void
     */
    protected function routesBindings($callback = null)
    {
        // Bind uploaded file get and delete routes
        $routerOptions = [
            'namespace' => 'App\\Http\\Controllers',
            'prefix' => 'api'
        ];

    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
