<?php ob_start(); ?>
<style>
    .form-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); max-width: 720px; }
    .form-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; color: #43474f; margin-bottom: 6px; }
    .form-group input, .form-group select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; color: #0b1c30; background: #f9fafb; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: #0054cb; background: #fff; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }
    .btn-secondary { display: inline-flex; align-items: center; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; text-decoration: none; }
</style>
<div class="form-card">
    <h3>Edit Counselor</h3>
    <form method="POST" action="<?php echo url('/admin/counselors/' . $counselor['id'] . '/update'); ?>">
        <div class="form-group"><label>Name</label><input type="text" name="name" value="<?php echo e($counselor['name']); ?>" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo e($counselor['email']); ?>" required></div>
        <div class="form-group"><label>Specialization</label><input type="text" name="specialization" value="<?php echo e($counselor['specialization'] ?? ''); ?>" required></div>
        <div class="form-group"><label>Max Students</label><input type="number" name="max_students" value="<?php echo e($counselor['max_students'] ?? 50); ?>"></div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="available" <?php echo ($counselor['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="unavailable" <?php echo ($counselor['status'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Counselor</button>
            <a href="<?php echo url('/admin/counselors'); ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); include __DIR__ . '/../../layouts/admin-layout.php';