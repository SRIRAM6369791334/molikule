<?php

namespace App\Services;

use App\Models\Pincode;

class PincodeService
{
    public function getTotalPincodes()
    {
        return Pincode::count();
    }

    public function getTotalActivePincodes()
    {
        return Pincode::active()->count();
    }

    public function updatePincodeStatus($id, $status)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->is_active = $status;
        $pincode->save();
        return $pincode;
    }

    public function getFilteredPincodes(array $filters, $perPage = 16)
    {
        $query = Pincode::query();

        // Search filter
        if (isset($filters['search']) && $filters['search']) {
            $searchTerm = $filters['search'];
            $query->search($searchTerm);
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort order
        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            switch($filters['sort_by']) {
                case 'pincode':
                    $query->orderBy('pincode', $direction);
                    break;
                case 'city':
                    $query->orderBy('city', $direction);
                    break;
                case 'state':
                    $query->orderBy('state', $direction);
                    break;
                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                default:
                    $query->orderBy('pincode', 'asc');
            }
        } else {
            $query->orderBy('pincode');
        }

        return $query->paginate($perPage);
    }

    public function getPincodesWithStats()
    {
        $totalPincodes = Pincode::count();
        $activePincodes = Pincode::active()->count();
        $inactivePincodes = $totalPincodes - $activePincodes;

        // Get states count
        $statesCount = Pincode::select('state')->distinct()->count('state');

        // Get cities count
        $citiesCount = Pincode::select('city')->distinct()->count('city');

        return [
            'total_pincodes' => $totalPincodes,
            'active_pincodes' => $activePincodes,
            'inactive_pincodes' => $inactivePincodes,
            'states_count' => $statesCount ?? 0,
            'cities_count' => $citiesCount ?? 0,
        ];
    }

    public function bulkUpdatePincodes(array $pincodeIds, array $updateData)
    {
        $pincodes = Pincode::whereIn('id', $pincodeIds)->get();

        $updated = 0;
        foreach ($pincodes as $pincode) {
            $pincode->update($updateData);
            $updated++;
        }

        return $updated;
    }

    public function bulkActivatePincodes(array $pincodeIds)
    {
        return $this->bulkUpdatePincodes($pincodeIds, ['is_active' => true]);
    }

    public function bulkDeactivatePincodes(array $pincodeIds)
    {
        return $this->bulkUpdatePincodes($pincodeIds, ['is_active' => false]);
    }

    public function bulkDeletePincodes(array $pincodeIds)
    {
        $pincodes = Pincode::whereIn('id', $pincodeIds)->get();

        $deleted = 0;
        foreach ($pincodes as $pincode) {
            $pincode->delete();
            $deleted++;
        }

        return $deleted;
    }

    public function searchPincodes(string $query, int $limit = 10)
    {
        return Pincode::search($query)
                      ->active()
                      ->take($limit)
                      ->get(['id', 'pincode', 'city', 'state']);
    }

    /**
     * Get pincodes for dropdown/filtering
     */
    public function getPincodesForSelect($includeInactive = false)
    {
        $query = Pincode::orderBy('pincode');

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->get(['id', 'pincode', 'city', 'state']);
    }

    /**
     * Validate if pincode is serviceable
     */
    public function validatePincodeForDelivery(string $pincode)
    {
        $pincodeRecord = Pincode::where('pincode', $pincode)->active()->first();

        if (!$pincodeRecord) {
            return [
                'is_valid' => false,
                'message' => 'Pincode not found or not serviceable',
                'data' => null
            ];
        }

        return [
            'is_valid' => true,
            'message' => 'Delivery available',
            'data' => [
                'id' => $pincodeRecord->id,
                'pincode' => $pincodeRecord->pincode,
                'city' => $pincodeRecord->city,
                'state' => $pincodeRecord->state,
                'location' => $pincodeRecord->formatted_location
            ]
        ];
    }
}
