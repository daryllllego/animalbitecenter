<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    protected static $cachedCebuBranches = null;
    protected static $cachedBoholBranches = null;

    public static function getBranchesForRegion($region)
    {
        if ($region === 'Cebu') {
            if (self::$cachedCebuBranches === null) {
                self::$cachedCebuBranches = \DB::table('users')
                    ->where('is_branch_account', true)
                    ->where('email', 'like', '%cabc%')
                    ->pluck('branch')
                    ->unique()
                    ->toArray();
            }
            return self::$cachedCebuBranches;
        } elseif ($region === 'Bohol') {
            if (self::$cachedBoholBranches === null) {
                self::$cachedBoholBranches = \DB::table('users')
                    ->where('is_branch_account', true)
                    ->where('email', 'like', '%babc%')
                    ->pluck('branch')
                    ->unique()
                    ->toArray();
            }
            return self::$cachedBoholBranches;
        }
        return [];
    }

    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->is_super_admin) {
                $selectedBranch = session('selected_branch');
                $selectedRegion = session('selected_region', 'Cebu and Bohol');
                
                if ($selectedBranch && $selectedBranch !== 'All Branches') {
                    $builder->where($model->getTable() . '.branch', $selectedBranch);
                } elseif ($selectedRegion && $selectedRegion !== 'Cebu and Bohol') {
                    $branches = self::getBranchesForRegion($selectedRegion);
                    $builder->whereIn($model->getTable() . '.branch', $branches);
                }
                // If 'All Branches' under 'Cebu and Bohol', show everything
            } else {
                if ($user->position === 'Deduction Admin') {
                    if ($user->branch === 'Cebu') {
                        $branches = self::getBranchesForRegion('Cebu');
                        $builder->whereIn($model->getTable() . '.branch', $branches);
                    } elseif ($user->branch === 'Bohol') {
                        $branches = self::getBranchesForRegion('Bohol');
                        $builder->whereIn($model->getTable() . '.branch', $branches);
                    } else {
                        $builder->where($model->getTable() . '.branch', $user->branch);
                    }
                } else {
                    $builder->where($model->getTable() . '.branch', $user->branch);
                }
            }
        }
    }
}
