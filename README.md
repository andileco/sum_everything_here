# Sum Everything Here

This module creates a Views field handler that enables you to calculate
the per-row sum of other fields in your View, as selected within the field
settings.

## Instructions

After you enable this module (you may need to run cron/clear caches):
1) Create/edit your view.
2) Add one or more fields that outputs numbers. These are considered the 
   "data fields" for purposes of this module.
3) Add the "Global: Sum Everything Field" field (created by this module)
4) In sum everything field's field settings, select the fields for which 
   you want a sum.

That's it!

## Credit
**Author:** Daniel Cothran (andileco)
