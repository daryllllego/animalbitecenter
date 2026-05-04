<?php

namespace App\Traits;

use App\Scopes\BranchScope;

trait HasBranch
{
    public static function bootHasBranch()
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function ($model) {
            if (!$model->branch && auth()->check()) {
                if (auth()->user()->is_super_admin) {
                    $selected = session('selected_branch');
                    if ($selected && $selected !== 'All Branches') {
                        $model->branch = $selected;
                    } else {
                        // Default to something or prevent creation if "All Branches" is selected
                        // For now, let's allow it to be null if they are in "All Branches" mode
                        // but usually they should select a branch first.
                    }
                } else {
                    $model->branch = auth()->user()->branch;
                }
            }
        });
    }
}
