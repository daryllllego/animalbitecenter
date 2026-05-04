<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->is_super_admin) {
                $selectedBranch = session('selected_branch');
                if ($selectedBranch && $selectedBranch !== 'All Branches') {
                    $builder->where($model->getTable() . '.branch', $selectedBranch);
                }
                // If 'All Branches' or no session, show everything
            } else {
                $builder->where($model->getTable() . '.branch', $user->branch);
            }
        }
    }
}
