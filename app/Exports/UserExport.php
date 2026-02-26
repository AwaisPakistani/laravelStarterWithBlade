<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserExport extends BaseExport
{
    /**
     * Build the query with advanced filtering
     */
    public function query(): Builder
    {
        $baseQuery = User::query()
            ->with(['roles']) // Eager load relationships
            ->latest();
        return $baseQuery;
        // Use Spatie Query Builder for advanced filtering
        // return QueryBuilder::for($baseQuery)
        //     ->allowedFilters([
        //         AllowedFilter::partial('name'),
        //         AllowedFilter::partial('email'),
        //         AllowedFilter::exact('is_active'),
        //         AllowedFilter::scope('created_between'), // Custom scope
        //         AllowedFilter::callback('role', function ($query, $value) {
        //             $query->whereHas('roles', fn($q) => $q->where('name', $value));
        //         }),
        //     ])
        //     ->allowedSorts(['name', 'email', 'created_at', 'updated_at'])
        //     ->defaultSort('-created_at')
        //     ->getQuery();
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Roles',
            'Status',
            'Last Login',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map user data to rows
     */
   public function map($user): array
    {
        // Convert roles collection to string
        $roles = $user->roles->pluck('name')->implode(', ');

        // If profile is a relationship, handle it carefully
        $profileData = '';
        if ($user->profile) {
            $profileData = $user->profile->toJson(); // or specific fields
        }

        // If specific columns are selected, filter the output
        if (!empty($this->selectedColumns)) {
            return $this->mapSelectedColumns($user);
        }

        return [
            $user->id,
            $user->name,
            $user->email,
            $roles, // Now this is a string, not a collection
            $user->is_active ? 'Active' : 'Inactive',
            $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never',
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Map only selected columns
     */
    protected function mapSelectedColumns($user): array
    {
        $mapped = [];
        foreach ($this->selectedColumns as $column) {
            $mapped[] = match($column) {
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->implode(', '),
                'status' => $user->is_active ? 'Active' : 'Inactive',
                'last_login' => $user->last_login_at?->format('Y-m-d H:i:s'),
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                default => 'N/A',
            };
        }
        return $mapped;
    }
}
