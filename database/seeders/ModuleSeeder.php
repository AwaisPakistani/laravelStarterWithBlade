<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class ModuleSeeder extends Seeder
{
    public function run()
    {
        // Module::factory()->count(10)->create();
        $modules = [
            'superadmin',
            'admin',
            'employee',
        ];

        $subModules = [
            [
                'parent_id' => 1, //superadmin Module
                'modules' => [
                    'Super Admin Dashboard',
                    'Users',
                    'Roles',
                    'Permissions',
                    'Promotion Demotion',
                    'Sanctioned Post',
                    'Employee Transfer Record',
                    'Leave Absent',
                    'Complaint',
                    'Employee Traning',
                    'Employee Employment Record',
                    'Employee Service Record',
                    'Employee Promotion',
                    'Employee Present Service Information',
                    'Area',
                    'Device',
                    'Course',
                    'Leave',
                    'Leave Account',
                    'Transfer',
                    'Employee Education',
                    'Employee Date Of Confirmation',
                    'Financial Benefit',
                    'Court Sanctioned Post',
                    'Acr And Pcr',
                    'Transaction',
                    'Daily Transaction',
                    'Schedule',
                    'Department Reports',
                    'Total Employee',
                    'Designation Report',
                    'Department Wise Report',
                    'Appointment Report',
                    'Retirement Report',
                    'Scale Report',
                    'Training Report',
                    'Acr Report',
                ]
            ],
            [
                'parent_id' => 2, //admin Module
                'modules' => [
                    'Dashboard',
                    'Category',
                    'Item',
                    'Stock',
                    'Stock In',
                    'Stock Out',
                    'Asset Handling',
                    'Faulty Items',
                    'Supplier',
                    'Stock Reports',
                    'Stock In Reports',
                    'Stock Out Reports',
                    'Supplier Reports',
                    'Employee Reports',
                    'Department Reports',
                    'Maximum Reports',
                    'Total Stock',
                ]
            ],
            [
                'parent_id' => 3, // employee Module
                'modules' => [
                    'Dashboard',
                    'Budgetbook Configuration',
                    'BudgetBook Head',
                    'Court BudgetBook',
                    'Court Budget Revised',
                    'Expense',
                    'Ta Expense',
                    'Pay Chart',
                    'Expenditure',
                    'Fund Request',
                    'Recived Fund Request',
                    'Vacant Post And Saving',
                    'Transfer Fund',
                    'Justice',
                    'Reports',
                    'Expense Yearly Report',
                    'Expense Report',
                    'Ta Expense Report',
                ]
            ],
        ];


        foreach ($modules as $module) {
            Module::create([
                'name' => $module,
                'slug' => Str::slug($module),
            ]);
        }

        foreach ($subModules as $subModule) {
            $module_array = [];
            foreach ($subModule['modules'] as $module) {
                array_push($module_array, [
                    'name' => $module,
                    'slug' => Str::slug($module),
                    'parent_id' => $subModule['parent_id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            Module::insert($module_array);
        }

    }
}
