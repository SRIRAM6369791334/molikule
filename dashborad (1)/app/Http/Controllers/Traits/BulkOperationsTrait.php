<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

trait BulkOperationsTrait
{
    /**
     * Handle bulk operations for any model
     */
    public function handleBulkUpdate(Request $request, $modelClass, $service, $idField = 'id')
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:' . (new $modelClass)->getTable() . ',' . $idField,
            'action' => 'required|in:activate,deactivate,delete,update_status'
        ]);

        $ids = $validated['ids'];
        $action = $validated['action'];

        try {
            DB::beginTransaction();

            $count = 0;
            $skipped = 0;
            $message = '';

            switch ($action) {
                case 'activate':
                    $affected = $modelClass::whereIn($idField, $ids)->update(['is_active' => 1, 'active' => 1]);
                    $count = $affected;
                    $message = "Successfully activated {$affected} item" . ($affected !== 1 ? 's' : '');
                    break;

                case 'deactivate':
                    $affected = $modelClass::whereIn($idField, $ids)->update(['is_active' => 0, 'active' => 0]);
                    $count = $affected;
                    $message = "Successfully deactivated {$affected} item" . ($affected !== 1 ? 's' : '');
                    break;

                case 'delete':
                    // Check for dependencies before deletion
                    $hasDependencies = $this->checkDependencies($modelClass, $ids);
                    
                    if ($hasDependencies) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot delete items that have associated data. Remove dependencies first.'
                        ], 422);
                    }

                    $affected = $modelClass::whereIn($idField, $ids)->delete();
                    $count = $affected;
                    $message = "Successfully deleted {$affected} item" . ($affected !== 1 ? 's' : '');
                    break;

                case 'update_status':
                    $status = $request->get('status');
                    $affected = $modelClass::whereIn($idField, $ids)->update([
                        'is_active' => $status,
                        'active' => $status
                    ]);
                    $count = $affected;
                    $message = "Successfully updated status for {$affected} item" . ($affected !== 1 ? 's' : '');
                    break;
            }

            DB::commit();

            // Clear relevant caches if service supports it
            if (method_exists($service, 'clearCaches')) {
                $service->clearCaches();
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Bulk operation failed', [
                'model' => $modelClass,
                'action' => $action,
                'ids' => $ids,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again.'
            ], 500);
        }
    }

    /**
     * Check for dependencies before deletion
     */
    private function checkDependencies($modelClass, $ids)
    {
        $modelInstance = new $modelClass;
        
        switch ($modelClass) {
            case \App\Models\Category::class:
                return \App\Models\Product::whereIn('category_id', $ids)->exists();
                
            case \App\Models\Brand::class:
                return \App\Models\Product::whereIn('brand_id', $ids)->exists();
                
            case \App\Models\Banner::class:
                // Banners don't have dependencies, safe to delete
                return false;
                
            case \App\Models\Product::class:
                // Check if products have been ordered
                return \App\Models\OrderItem::whereIn('itemable_id', $ids)
                    ->where('itemable_type', $modelClass)
                    ->exists();
        }
        
        return false;
    }

}
