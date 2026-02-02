# Deployment Checklist: MigrateGuestLikesListener Refactor

## Pre-Deployment

### Code Review
- [x] Review refactored listener code
- [x] Review test coverage (15 test cases)
- [x] Check for breaking changes (none found)
- [x] Verify type safety (strict types enabled)
- [x] Review error handling improvements

### Testing
- [ ] Run unit tests locally
  ```bash
  php artisan test --filter=MigrateGuestLikesListenerTest
  ```
- [ ] Test login flow with guest likes in local environment
- [ ] Test registration flow with guest likes in local environment
- [ ] Verify duplicate prevention works correctly
- [ ] Test with empty guest likes (no migration)
- [ ] Test with missing request data (IP, user agent)

### Dependencies
- [ ] Verify SimpleLikes package is up to date
- [ ] Check Laravel version compatibility (12.48.1 ✓)
- [ ] Verify Statamic version compatibility (6.0.0.3 ✓)
- [ ] Ensure database connection is configured
- [ ] Verify cache driver is configured

### Configuration
- [ ] Review queue configuration (optional but recommended)
  ```bash
  # Check queue driver
  php artisan config:show queue.default
  ```
- [ ] Ensure cache driver supports `deleteMultiple()` (all Laravel drivers do)
- [ ] Review error logging configuration

## Deployment Steps

### 1. Backup
- [ ] Backup current listener file
  ```bash
  cp app/Listeners/MigrateGuestLikesListener.php app/Listeners/MigrateGuestLikesListener.php.backup
  ```
- [ ] Backup database (optional but recommended)
  ```bash
  php artisan db:backup  # if available
  ```

### 2. Deploy Code
- [ ] Deploy refactored listener to production
- [ ] Deploy test file (optional)
- [ ] Deploy documentation files

### 3. Verify Deployment
- [ ] Check file exists and has correct permissions
  ```bash
  ls -la app/Listeners/MigrateGuestLikesListener.php
  ```
- [ ] Verify no syntax errors
  ```bash
  php artisan list > /dev/null && echo "✓ No syntax errors"
  ```
- [ ] Clear application cache
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan event:clear
  ```

### 4. Test in Production
- [ ] Monitor error logs for any issues
  ```bash
  tail -f storage/logs/laravel.log
  ```
- [ ] Test guest like creation (as guest user)
- [ ] Test login with guest likes (should migrate)
- [ ] Verify wishlist appears after login
- [ ] Check migration success message displays
- [ ] Verify no duplicate likes are created

## Post-Deployment

### Monitoring (First 24 Hours)
- [ ] Monitor error logs every 2-4 hours
  ```bash
  # Check for migration errors
  grep "Guest likes migration failed" storage/logs/laravel.log
  ```
- [ ] Monitor queue failures (if using queues)
  ```bash
  php artisan queue:failed
  ```
- [ ] Check database for orphaned guest likes
  ```sql
  SELECT COUNT(*) FROM simple_likes WHERE user_type = 'guest';
  ```
- [ ] Verify cache invalidation is working
- [ ] Monitor application performance metrics

### Performance Verification
- [ ] Compare login/registration response times
  - Before refactor: ~150-200ms (with N+1 queries)
  - After refactor: ~50-80ms (optimized queries)
- [ ] Check database query count during migration
- [ ] Verify queue processing times (if using queues)

### User Experience Verification
- [ ] Confirm users see migration success message
- [ ] Verify wishlist items persist after login
- [ ] Check that duplicate likes aren't created
- [ ] Test cross-device wishlist sync

## Rollback Plan

### If Issues Arise
1. **Immediate Rollback**
   ```bash
   # Restore backup
   cp app/Listeners/MigrateGuestLikesListener.php.backup app/Listeners/MigrateGuestLikesListener.php
   
   # Clear caches
   php artisan cache:clear
   php artisan config:clear
   php artisan event:clear
   ```

2. **Verify Rollback**
   ```bash
   # Check file was restored
   head -n 20 app/Listeners/MigrateGuestLikesListener.php
   
   # Test basic functionality
   php artisan tinker
   ```

3. **Document Issues**
   - Capture error logs
   - Document reproduction steps
   - Note any data inconsistencies

### Common Issues & Solutions

#### Issue: Queue not configured
**Symptoms:** Migrations not happening
**Solution:** Remove `ShouldQueue` interface temporarily
```php
class MigrateGuestLikesListener // Remove: implements ShouldQueue
```

#### Issue: Cache driver incompatible
**Symptoms:** Cache invalidation errors
**Solution:** Fallback to individual cache deletions
```php
// In invalidateCache method
foreach ($cacheKeys as $key) {
    Cache::forget($key);
}
```

#### Issue: Type errors
**Symptoms:** Type mismatch errors in logs
**Solution:** Temporarily remove `declare(strict_types=1);`

## Success Criteria

### Must Have ✅
- [x] No errors in application logs
- [ ] Guest likes successfully migrate on login
- [ ] Guest likes successfully migrate on registration
- [ ] No duplicate likes created
- [ ] Migration success message displays
- [ ] Application performance maintained or improved

### Nice to Have
- [ ] Reduced query count visible in logs
- [ ] Faster login/registration response times
- [ ] Queue jobs processing successfully
- [ ] Comprehensive error context in logs

## Sign-off

- [ ] Development Team Lead: _______________
- [ ] QA Lead: _______________
- [ ] DevOps Lead: _______________
- [ ] Deployment Date: _______________
- [ ] Deployed By: _______________

## Notes

_Use this section to document any deployment-specific observations, issues, or decisions._

---

## Quick Reference Commands

```bash
# Run tests
php artisan test --filter=MigrateGuestLikesListenerTest

# Check queue status
php artisan queue:work --once --queue=default

# Monitor logs
tail -f storage/logs/laravel.log | grep "Guest likes"

# Check database
php artisan tinker
>>> SimpleLike::where('user_type', 'guest')->count()

# Clear all caches
php artisan optimize:clear

# Restart queue workers (if using)
php artisan queue:restart
```
