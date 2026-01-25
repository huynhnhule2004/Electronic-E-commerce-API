# Branch Module

## Purpose
Store branches with geo coordinates and cached listing.

## Structure
- app/Http/Controllers: `BranchController`
- app/Models: `Branch`
- app/Resources: `BranchResource`
- app/Services: `BranchCacheService`
- database/migrations: branches
- routes/api.php
- app/Providers: `BranchServiceProvider`

## API
- GET /api/branches

## Notes
- Branch list cached; invalidated via observer.
