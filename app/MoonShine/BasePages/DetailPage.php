<?php

namespace App\MoonShine\BasePages;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use MoonShine\Laravel\Models\MoonshineUser;

abstract class DetailPage  extends \MoonShine\Laravel\Pages\Crud\DetailPage
{
    private ?Company $company = null;
    private ?MoonshineUser $user = null;

    public function getCompany(): Company
    {
        if (!$this->company) {
            $this->company = Company::query()
                ->find($this->getUser()->company_id);
        }

        return $this->company;
    }

    protected function getUser(): MoonshineUser
    {
        if (!$this->user) {
            $this->user = Auth::user();
        }

        return $this->user;
    }
}
