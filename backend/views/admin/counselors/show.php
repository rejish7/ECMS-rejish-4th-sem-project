<?php ob_start(); ?>
<style>
    .detail-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); max-width: 720px; }
    .detail-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; }
    .detail-value { font-size: 14px; color: #0b1c30; }
    .detail-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
    .btn-danger { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #ef4444; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
</style>
<div class="detail-card">
    <h3>Counselor Details</h3>
    <div class="detail-row"><span class="detail-label">Name</span><span class="detail-value"><?php echo e($counselor['name']); ?></span></div>
    <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><?php echo e($counselor['email']); ?></span></div>
    <div class="detail-row"><span class="detail-label">Specialization</span><span class="detail-value"><?php echo e($counselor['specialization'] ?? '-'); ?></span></div>
    <div class="detail-row"><span class="detail-label">Max Students</span><span class="detail-value"><?php echo e($counselor['max_students'] ?? 50); ?></span></div>
    <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value"><?php echo e($counselor['status'] ?? 'available'); ?></span></div>
    <div class="detail-actions">
        <a href="<?php echo url('/admin/counselors/' . $counselor['id'] . '/edit'); ?>" class="btn-primary">Edit</a>
        <form method="POST" action="<?php echo url('/admin/counselors/' . $counselor['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-danger">Delete</button>
        </form>
        <a href="<?php echo url('/admin/counselors'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';